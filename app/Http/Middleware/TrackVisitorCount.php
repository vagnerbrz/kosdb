<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se é uma nova sessão
        if (!$request->session()->has('visited')) {
            // Incrementa o contador global
            $this->incrementGlobalCount();
            
            // Marca a sessão como visitada
            $request->session()->put('visited', true);
        }

        return $next($request);
    }

    protected function incrementGlobalCount()
    {
        // Usa o sistema de cache para persistência
        cache()->lock('visitor_counter')->get(function () {
            $count = cache()->get('global_visitors', 0);
            cache()->forever('global_visitors', $count + 1);
        });
    }
}
