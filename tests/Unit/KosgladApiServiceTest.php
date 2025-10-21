<?php

namespace Tests\Unit;

use App\Services\KosgladApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class KosgladApiServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        Http::preventStrayRequests();
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 0, 0, 0));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Cache::flush();

        parent::tearDown();
    }

    public function test_it_reuses_cached_token_until_api_says_otherwise(): void
    {
        $token = $this->makeJwtToken(expSeconds: 3600);

        Http::fake([
            'https://cdn2008.kosglad.com.br/api/auth/login' => Http::response(['token' => $token], 200),
            'https://cdn2008.kosglad.com.br/api/stats*' => Http::response(['ok' => true], 200),
        ]);

        $service = new KosgladApiService();

        $service->getAuthenticated('stats');
        $service->getAuthenticated('stats');

        Http::assertSentCount(3);

        Http::assertSent(function ($request) {
            if (Str::endsWith($request->url(), '/auth/login')) {
                static $calls = 0;
                $calls++;

                return $calls === 1;
            }

            return Str::contains($request->url(), '/api/stats');
        });
    }

    public function test_it_refreshes_token_when_api_returns_unauthorized(): void
    {
        $firstToken = $this->makeJwtToken(expSeconds: 3600);
        $secondToken = $this->makeJwtToken(expSeconds: 7200);

        $loginCalls = 0;
        $statsCalls = 0;

        Http::fake(function ($request) use (&$loginCalls, &$statsCalls, $firstToken, $secondToken) {
            if (Str::endsWith($request->url(), '/auth/login')) {
                $loginCalls++;

                $token = $loginCalls === 1 ? $firstToken : $secondToken;

                return Http::response(['token' => $token], 200);
            }

            if (Str::contains($request->url(), '/api/stats')) {
                $statsCalls++;

                return $statsCalls === 1
                    ? Http::response(['message' => 'unauthorized'], 401)
                    : Http::response(['ok' => true], 200);
            }

            return Http::response(null, 404);
        });

        $service = new KosgladApiService();

        $response = $service->getAuthenticated('stats');

        $this->assertTrue($response->successful());
        $this->assertSame(2, $loginCalls);
        $this->assertSame(2, $statsCalls);
    }

    private function makeJwtToken(int $expSeconds): string
    {
        $header = $this->base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = $this->base64UrlEncode(json_encode([
            'exp' => Carbon::now()->addSeconds($expSeconds)->timestamp,
        ]));

        return "{$header}.{$payload}.signature";
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
