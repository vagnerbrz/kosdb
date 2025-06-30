<?php

namespace App\Livewire\Stats;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class Olimpiadas extends Component
{

    public Collection $groupedPlayers;
    public $lastUpdated;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // $response = Http::get('https://cdn2008.kosglad.com.br/api/stats/olympiad/ranking');
        $response = Http::withoutVerifying()->get('https://cdn2008.kosglad.com.br/api/stats/olympiad/ranking');
        

        if ($response->successful()) {
            $players = collect($response->json());

            $this->groupedPlayers = $players
                ->sortByDesc('olympiad_point')
                ->groupBy('subjob0_class')
                ->mapWithKeys(function ($group, $classId) {
                    $className = self::className($classId);
                    return [
                        $classId => [
                            'id' => $classId,
                            'name' => $className,
                            'players' => $group->take(3)->values(),
                        ]
                    ];
            })
             ->sortKeys();
        } else {
            $this->groupedPlayers = collect();
        }
        $this->lastUpdated = now()->format('H:i:s');
    }

    public function poll()
    {
        $this->loadData(); // Atualiza os dados via API
    }

    public function render()
    {
        return view('livewire.stats.olimpiadas');
    }


    public static function className(int $id): string
    {
        return match ($id) {
            88 => 'Duelist',
            89 => 'Dreadnought',
            90 => 'Phoenix Knight',
            91 => 'Hell Knight',
            92 => 'Sagittarius',
            93 => 'Adventurer',
            94 => 'Archmage',
            95 => 'Soultaker',
            96 => 'Arcana Lord',
            97 => 'Cardinal',
            98 => 'Hierophant',
            99 => 'Eva Templar',
            100 => 'Sword Muse',
            101 => 'Wind Rider',
            102 => 'Moonlight Sentinel',
            103 => 'Mystic Muse',
            104 => 'Elemental Master',
            105 => 'Eva\'s Saint',
            106 => 'Shillien Templar',
            107 => 'Spectral Dancer',
            108 => 'Ghost Hunter',
            109 => 'Ghost Sentinel',
            110 => 'Storm Screamer',
            111 => 'Spectral Master',
            112 => 'Shillien Saint',
            113 => 'Titan',
            114 => 'Grand Khavatari',
            115 => 'Dominator',
            116 => 'Doomcryer',
            117 => 'Fortune Seeker',
            118 => 'Maestro',
            default => "Classe $id",
        };
    }

}


