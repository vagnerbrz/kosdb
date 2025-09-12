<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class KosgladApiService
{
    public function getToken(): ?string
    {
        // return Cache::remember('kosglad_token', 3600, function () {
        //     $response = Http::withoutVerifying()
        //         ->withHeaders([
        //             'x-app-signature' => '09284518fbcd2779fd0bf38cbdff3b574234512651c1859fbc2d209fd53c2986',
        //             'Accept'          => 'application/json',
        //             'Content-Type'    => 'application/json',
        //         ])
        //         ->post('https://cdn2008.kosglad.com.br/api/auth/login', [
        //             'login'    => '1101',
        //             'password' => '123321123',
        //         ]);

        //     // Debug temporário
        //     dd($response->json());

        //     return $response->json()['token'] ?? null;

        // });
        return "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6NzI3LCJsb2dpbiI6IjExMDEiLCJpYXQiOjE3NTc3MjA2ODYsImV4cCI6MTc1Nzc2Mzg4Nn0.BGYrss8w_f5kz8XoM23j1pjkF9ALpDXzHsMNl9KGH9E";
    }

    public function getAuthenticated(string $endpoint)
    {
        // $token = $this->getToken();

    //     return Http::withoutVerifying()
    //         ->withHeaders([
    //             'x-app-signature' => '09284518fbcd2779fd0bf38cbdff3b574234512651c1859fbc2d209fd53c2986',
    //         ])
    //         ->withToken($token)
    //         ->get("https://cdn2008.kosglad.com.br/api/{$endpoint}");
        return "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6NzI3LCJsb2dpbiI6IjExMDEiLCJpYXQiOjE3NTc3MjA2ODYsImV4cCI6MTc1Nzc2Mzg4Nn0.BGYrss8w_f5kz8XoM23j1pjkF9ALpDXzHsMNl9KGH9E";
    }
}
