<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class KosgladApiService
{
    private const CACHE_KEY = 'kosglad_api_token';
    private const CACHE_TTL_FALLBACK = 3600; // 60 minutos
    private const TOKEN_REFRESH_BUFFER = 60; // renovar 1 min antes de expirar

    /**
     * Lista de contas disponíveis — cada uma com login, senha e x-app-signature.
     */
    protected array $accounts = [
        [
            'login' => 'kosdb',
            'password' => 'kosdb',
            'signature' => '8c254d03f200ba33dc505e302c86962bde94a05b2d4ed2c85ebd2005f2696ee3',
        ],
        [
            'login' => 'kosdb01',
            'password' => 'kosdb',
            'signature' => 'f028b490a164854a7aa6539dc688692b49063d47a380e9b3774f7d00d4131432',
        ],
        [
            'login' => 'kosdb02',
            'password' => 'kosdb',
            'signature' => '03be07d130b9691a9e1bc5cf5cba6ec5c6fee4bc1a93ec7202192457dadf3c99',
        ],
        [
            'login' => 'kosdb03',
            'password' => 'kosdb',
            'signature' => 'c725fa58f879c5f184c53f0c7341ea4e0a145722aad417a434e06c0b568e8978',
        ],
        [
            'login' => 'kosdb04',
            'password' => 'kosdb',
            'signature' => '8c650855cdd366e09c6c7ac86233bbfdafc59a6db06dceceb59082dbfa87dece',
        ],
        [
            'login' => 'kosdb05',
            'password' => 'kosdb',
            'signature' => 'e33d30671289f7d6ae12c6a789bb0ad2d284415432c92d2cd7c5ecf840f38911',
        ],
        // Adicione outras contas aqui...
    ];

    public function getToken(): ?string
    {
        $cachedToken = Cache::get(self::CACHE_KEY);
        if (!empty($cachedToken)) {
            return $cachedToken;
        }
        return $this->requestNewToken();
    }

    public function getAuthenticated(string $endpoint): Response
    {
        $token = $this->getToken();

        if (empty($token)) {
            return Http::response(['message' => 'Unable to obtain Kosglad token'], 500);
        }

        $response = $this->sendAuthenticatedRequest($endpoint, $token);

        if ($this->tokenExpired($response)) {
            $this->invalidateToken();
            $freshToken = $this->requestNewToken();
            if (empty($freshToken)) {
                return $response;
            }
            $response = $this->sendAuthenticatedRequest($endpoint, $freshToken);
        }

        return $response;
    }

    /**
     * Faz login com uma conta aleatória e salva o token em cache.
     */
    protected function requestNewToken(): ?string
    {
        if (empty($this->accounts)) {
            return null;
        }

        // Seleciona uma conta aleatória
        $account = $this->accounts[array_rand($this->accounts)];

        $response = Http::withoutVerifying()
            ->withHeaders($this->defaultHeaders($account['signature']))
            ->post('https://cdn2008.kosglad.com.br/api/auth/login', [
                'login' => $account['login'],
                'password' => $account['password'],
            ]);

        if ($response->failed()) {
            return null;
        }

        $token = $response->json('token');

        if (empty($token)) {
            return null;
        }

        // Cacheia o token junto com o signature usado
        $this->cacheToken($token, $account['signature']);

        return $token;
    }

    protected function sendAuthenticatedRequest(string $endpoint, string $token): Response
    {
        // Recupera o signature usado na geração do token
        $signature = Cache::get(self::CACHE_KEY . '_signature');

        return Http::withoutVerifying()
            ->withHeaders($this->defaultHeaders($signature))
            ->withToken($token)
            ->get("https://cdn2008.kosglad.com.br/api/{$endpoint}");
    }

    protected function tokenExpired(Response $response): bool
    {
        return in_array($response->status(), [401, 403], true);
    }

    protected function invalidateToken(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY . '_signature');
    }

    protected function cacheToken(string $token, string $signature): void
    {
        $ttl = $this->extractTtlFromToken($token) ?? self::CACHE_TTL_FALLBACK;

        Cache::put(self::CACHE_KEY, $token, now()->addSeconds($ttl));
        Cache::put(self::CACHE_KEY . '_signature', $signature, now()->addSeconds($ttl));
    }

    protected function extractTtlFromToken(string $token): ?int
    {
        $parts = explode('.', $token);
        if (count($parts) < 2) return null;

        $payload = base64_decode(strtr($parts[1], '-_', '+/'));
        if ($payload === false) return null;

        $decoded = json_decode($payload, true);
        if (!is_array($decoded) || empty($decoded['exp'])) return null;

        $ttl = (int)$decoded['exp'] - time() - self::TOKEN_REFRESH_BUFFER;
        return $ttl > 0 ? $ttl : null;
    }

    protected function defaultHeaders(string $signature): array
    {
        return [
            'x-app-signature' => $signature,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
