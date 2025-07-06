<?php

namespace App\Livewire\Stats;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use App\Services\KosgladApiService;

class Sieges extends Component
{

    public array $castelos = [];
    
    public function render()
    {
        return view('livewire.stats.sieges');
    }

    public function mount()
    {
        $this->loadData();
    }

    private function token(KosgladApiService $api)
    {
        $token = $api->getToken();
        return $token;
    }

    public function loadData()
    {
        $token = $this->token(app(KosgladApiService::class));

        $response = Http::withoutVerifying()
        ->withToken($token)
        ->get('https://cdn2008.kosglad.com.br/api/stats/siege');

        if ($response->successful()) {
            $this->castelos = collect($response->json())
                ->map(function ($castelo) {
                    $castelo['display_name'] = self::castleName($castelo['name']);
                    $castelo['sdate_formatted'] = \Carbon\Carbon::createFromTimestamp($castelo['sdate'])->timezone('-4')->format('d/m/Y H:i');
                    $castelo['attackers'] = [];
                    $castelo['defenders'] = [];
                    // Buscar atacantes e defensores
                    $token = $this->token(app(KosgladApiService::class));
                    $participants = Http::withoutVerifying()->withToken($token)->get("https://cdn2008.kosglad.com.br/api/stats/siege/{$castelo['id']}/participants");

                    if ($participants->successful()) {
                        foreach ($participants->json() as $clan) {
                            if ($clan['type'] == 1) {
                                $castelo['attackers'][] = $clan['clan_name'];
                            } elseif ($clan['type'] == 3) {
                                $castelo['defenders'][] = $clan['clan_name'];
                            }
                        }
                    }

                    return $castelo;
                })
                ->sortBy('sdate')
                ->values()
                ->toArray();
        } else {
            $this->castelos = [];
        }
    }

    public static function castleName(string $key): string
    {
        return match ($key) {
            'gludio_castle' => 'Gludio',
            'dion_castle' => 'Dion',
            'giran_castle' => 'Giran',
            'oren_castle' => 'Oren',
            'aden_castle' => 'Aden',
            'innadril_castle' => 'Innadril',
            'goddard_castle' => 'Goddard',
            'rune_castle' => 'Rune',
            'schuttgart_castle' => 'Schuttgart',
            default => ucwords(str_replace('_castle', '', str_replace('_', ' ', $key))),
        };
    }
}
