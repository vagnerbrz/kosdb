<?php

namespace App\Livewire\Stats;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use App\Services\KosgladApiService;


class Bosses extends Component
{

    public array $bosses = [];
    public $api =  'stats/raidboss/status';

    
    public function render()
    {
        return view('livewire.stats.bosses');
    }

    public function mount()
    {
        $this->loadData(app(KosgladApiService::class));
    }

    public function loadData(KosgladApiService $api)
    {


        $token = $api->getToken();

        $response = Http::withoutVerifying()
        ->withToken($token)
        ->get('https://cdn2008.kosglad.com.br/api/stats/raidboss/status');

        if ($response->successful()) {
            $this->bosses = collect($response->json())
                ->map(function ($boss) {
                    $boss['display_name'] = self::bossName($boss['npc_db_name']);
                    $boss['respawn_time'] = $boss['alive'] == 0
                        ? Carbon::createFromTimestamp($boss['time_low'])
                            ->timezone('-4') // ajusta para UTC-4
                            ->format('d/m/Y H:i:s')
                        : null;
                    return $boss;
                })
                ->sortBy('display_name')
                ->values()
                ->toArray();
        } else {
            $this->bosses = [];
        }
    }

    public static function bossName(string $npcDbName): string
    {
        return ucwords(str_replace('_', ' ', $npcDbName));
    }
}
