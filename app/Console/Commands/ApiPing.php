<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\KosgladApiService;

class ApiPing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mantém o token da API ativo fazendo ping periódico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $token = app(\App\Services\KosgladApiService::class);

        $response = Http::withoutVerifying()
        ->withToken($token->getToken())
        ->withHeaders([ 
            'x-app-signature' => '6d3b5711416e919104ca64c3281867cfec6d49788a85ce3681491a66a24cba7d', 
            'Accept' => 'application/json', 
            'Content-Type' => 'application/json', 
            ]) 
        ->get('https://cdn2008.kosglad.com.br/api/stats/olympiad/ranking');

        if ($response->successful()) {
            Log::info('Ping OK', ['ts' => now()->toDateTimeString()]);
            $this->info('Ping enviado com sucesso.');
            return self::SUCCESS;
        } 

        Log::error('Ping FAIL', [
            'status' => $response->status(),
            'body'   => $response->body(),
            'ts'     => now()->toDateTimeString(),
        ]);
        $this->error('Falha no ping: '.$response->status());
        return self::FAILURE;
    }
}
