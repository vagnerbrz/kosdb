<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class KosgladApiService
{
    public function getToken(): ?string
    {
        return Cache::remember('kosglad_token', 3600, function () {
            $response = Http::withoutVerifying()->post('https://cdn2008.kosglad.com.br/api/auth/login', [
                'login' => '1101',
                'password' => '123321123',
            ]);

            return $response->json()['token'] ?? null;
        });
    }

    public function getAuthenticated(string $endpoint)
    {
        $token = $this->getToken();

        return Http::withoutVerifying()
            ->withToken($token)
            ->get("https://cdn2008.kosglad.com.br/api/{$endpoint}");
    }
}
