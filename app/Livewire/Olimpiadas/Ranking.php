<?php

// app/Livewire/Olimpiadas/Ranking.php
namespace App\Livewire\Olimpiadas;

use App\Models\FavoriteClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Collection;

class Ranking extends Component
{
    /** @var array<int,array> */
    public array $rows = [];          // dados do ranking (traga do seu service/API)
    public string $q = '';            // busca
    public array $favorites = [];     // lista de class_code favoritadas
    public bool $onlyFav = false;     // filtro: só favoritos
    public Collection $groupedPlayers;
    public $lastUpdated;

    public function mount(): void
    {
        // 1) Carregue seus dados reais (exemplo mock)
        $this->rows = $this->loadRanking();

        // 2) Carrega favoritos do usuário OU do localStorage via evento (ver Blade)
        if (Auth::check()) {
            $this->favorites = FavoriteClass::where('user_id', Auth::id())
                ->pluck('class_code')->all();
        }
    }

    public function loadData()
    {
        // $response = Http::get('https://cdn2008.kosglad.com.br/api/stats/olympiad/ranking');
        // $token = $api->getToken();

        $response = Http::withoutVerifying()
        ->withToken("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MTYwOSwibG9naW4iOiJrYXJpbG93IiwiaWF0IjoxNzU1NjIwMDM1LCJleHAiOjE3NTU2NjMyMzV9.iNree01gIIlZcASmMxVw7jxTHlz0UajIWcuDOcKGCk0")
        ->withHeaders([
                    'x-app-signature' => '6d3b5711416e919104ca64c3281867cfec6d49788a85ce3681491a66a24cba7d',
                    'Accept'          => 'application/json',
                    'Content-Type'    => 'application/json',
                ])
        ->get('https://cdn2008.kosglad.com.br/api/stats/olympiad/ranking');
        
        // dd($response);
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
             ->sortBy(function ($item) {
                // Favoritos primeiro (0), depois os demais (1)
                return in_array($item['id'], $this->favoriteClasses) ? 0 : 1;
            });
             
        } else {
            $this->groupedPlayers = collect();
        }
        $this->lastUpdated = now()->format('H:i:s');
    }

    /** Exemplo de carga: troque pelo seu service que chama a API */
    protected function loadRanking()
    {
        $response = Http::withoutVerifying()
        ->withToken("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MTYwOSwibG9naW4iOiJrYXJpbG93IiwiaWF0IjoxNzU1NjIwMDM1LCJleHAiOjE3NTU2NjMyMzV9.iNree01gIIlZcASmMxVw7jxTHlz0UajIWcuDOcKGCk0")
        ->withHeaders([
                    'x-app-signature' => '6d3b5711416e919104ca64c3281867cfec6d49788a85ce3681491a66a24cba7d',
                    'Accept'          => 'application/json',
                    'Content-Type'    => 'application/json',
                ])
        ->get('https://cdn2008.kosglad.com.br/api/stats/olympiad/ranking');
        
        // dd($response);
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
             ->sortBy('id');
             
        } else {
            $this->groupedPlayers = collect();
        }

        $this->lastUpdated = now()->format('H:i:s');
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

    public function getFilteredProperty(): array
    {
        $data = $this->rows;

        if ($this->q !== '') {
            $q = Str::lower($this->q);
            $data = array_values(array_filter($data, fn ($r) =>
                Str::contains(Str::lower($r['class_name']), $q) ||
                Str::contains(Str::lower($r['class_code']), $q)
            ));
        }

        if ($this->onlyFav) {
            $data = array_values(array_filter($data, fn ($r) =>
                in_array($r['class_code'], $this->favorites, true)
            ));
        }

        return $data;
    }

    public function toggleFavorite(string $classCode): void
    {
        if (in_array($classCode, $this->favorites, true)) {
            // remover
            $this->favorites = array_values(array_filter(
                $this->favorites, fn ($c) => $c !== $classCode
            ));
            if (Auth::check()) {
                FavoriteClass::where('user_id', Auth::id())
                    ->where('class_code', $classCode)->delete();
            } else {
                // visitante: avisa front para atualizar localStorage
                $this->dispatch('guest-fav-sync', code: $classCode, op: 'remove');
            }
        } else {
            // adicionar
            $this->favorites[] = $classCode;
            if (Auth::check()) {
                FavoriteClass::firstOrCreate([
                    'user_id'    => Auth::id(),
                    'class_code' => $classCode,
                ]);
            } else {
                $this->dispatch('guest-fav-sync', code: $classCode, op: 'add');
            }
        }
    }

    #[On('guest-favs-loaded')]
    public function setGuestFavs(array $codes): void
    {
        // chamado pelo front quando visitante carrega do localStorage
        if (!Auth::check()) {
            $this->favorites = $codes;
        }
    }

    public function render()
    {
        return view('livewire.olimpiadas.ranking', [
            'items' => $this->filtered, // propriedade computada
        ]);
    }
}

