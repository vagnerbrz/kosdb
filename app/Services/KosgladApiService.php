<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class KosgladApiService
{
    private const CACHE_KEY = 'kosglad_api_token';
    private const CACHE_TTL_FALLBACK = 3600; // cache token for 60 minutes if we cannot parse JWT expiry
    private const TOKEN_REFRESH_BUFFER = 60; // refresh 1 minute before the JWT expires

    /**
     * Lista de contas disponíveis. Ao renovar o token escolhemos uma aleatoriamente.
     */
    protected array $accounts = [
        [
            'login' => 'user001',
            'password' => 'user001',
        ],
        [
            'login' => 'user002',
            'password' => 'user002',
        ],
        [
            'login' => 'user003',
            'password' => 'user003',
        ],
        [
            'login' => 'user004',
            'password' => 'user004',
        ],
        [
            'login' => 'user005',
            'password' => 'user005',
        ],
        [
            'login' => 'user006',
            'password' => 'user006',
        ],
        [
            'login' => 'user007',
            'password' => 'user007',
        ],
        [
            'login' => 'user008',
            'password' => 'user008',
        ],
        [
            'login' => 'user009',
            'password' => 'user009',
        ],
        [
            'login' => 'user010',
            'password' => 'user010',
        ],
        // Adicione outras contas aqui.
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
            // Mantém uma resposta de erro controlada quando não há token disponível.
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
     * Realiza o login usando uma conta aleatória e armazena o token em cache.
     */
    protected function requestNewToken(): ?string
    {
        if (empty($this->accounts)) {
            return null;
        }

        $credentials = $this->accounts[array_rand($this->accounts)];

        $response = Http::withoutVerifying()
            ->withHeaders($this->defaultHeaders())
            ->post('https://cdn2008.kosglad.com.br/api/auth/login', $credentials);

        if ($response->failed()) {
            return null;
        }

        $token = $response->json('token');

        if (empty($token)) {
            return null;
        }

        $this->cacheToken($token);

        return $token;
    }

    protected function sendAuthenticatedRequest(string $endpoint, string $token): Response
    {
        return Http::withoutVerifying()
            ->withHeaders($this->defaultHeaders())
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
    }

    protected function cacheToken(string $token): void
    {
        $ttl = $this->extractTtlFromToken($token) ?? self::CACHE_TTL_FALLBACK;

        Cache::put(self::CACHE_KEY, $token, now()->addSeconds($ttl));
    }

    protected function extractTtlFromToken(string $token): ?int
    {
        $parts = explode('.', $token);

        if (count($parts) < 2) {
            return null;
        }

        $payload = $parts[1];

        $payload = strtr($payload, '-_', '+/');
        $payload = base64_decode($payload);

        if ($payload === false) {
            return null;
        }

        $decoded = json_decode($payload, true);

        if (!is_array($decoded) || empty($decoded['exp'])) {
            return null;
        }

        $ttl = (int) $decoded['exp'] - time() - self::TOKEN_REFRESH_BUFFER;

        return $ttl > 0 ? $ttl : null;
    }

    protected function defaultHeaders(): array
    {
        return [
            'x-app-signature' => '19161de14a390447b8f8d0f6aa8867156655ea854d083a0c0f3985d42a3c0159',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
