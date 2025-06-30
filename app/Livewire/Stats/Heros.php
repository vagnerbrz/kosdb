<?php

namespace App\Livewire\Stats;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Heros extends Component
{

    public Collection $players;


    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.stats.heros');
    }

    public function loadData()
    {
        // $response = Http::get('https://cdn2008.kosglad.com.br/api/stats/olympiad/ranking');
        $response = Http::withoutVerifying()->get('https://cdn2008.kosglad.com.br/api/stats/olympiad/heroes/current');
        

        if ($response->successful()) {
        $players = collect($response->json());

        $this->players = $players
            ->map(function ($player) {
                $player['class_name'] = self::className($player['base']);
                return $player;
            })
            ->sortBy('class_name') // ordena alfabeticamente pela classe
            ->values(); // reorganiza os índices (opcional)
        } else {
            $this->players = collect();
        }
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
