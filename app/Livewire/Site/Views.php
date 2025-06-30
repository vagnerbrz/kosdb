<?php

namespace App\Livewire\Site;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Views extends Component
{


    public function render()
    {

        $totalVisitors = Cache::get('global_visitors', 0);
        
        // Visitantes ativos nas últimas 24 horas (usando sessões)
        $activeVisitors = $this->getActiveVisitors();

        return view('livewire.site.views', compact('totalVisitors', 'activeVisitors'));
    }

    protected function getActiveVisitors()
    {
        $lifetime = config('session.lifetime') * 60; // Minutos para segundos
        $cutoff = time() - $lifetime;

        return DB::table('sessions')
            ->where('last_activity', '>=', $cutoff)
            ->count();
    }
}
