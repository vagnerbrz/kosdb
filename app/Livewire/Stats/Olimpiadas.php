<?php

namespace App\Livewire\Stats;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Services\KosgladApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Olimpiadas extends Component
{
    public Collection $groupedPlayers;
    public string $lastUpdated = '';
    public array $favoriteClasses = [];

    // Bridge com o front (localStorage) – carrega favs do visitante
    protected $listeners = ['setFavorites'];

    public function mount(KosgladApiService $api): void
    {
        $this->loadData($api);
    }

    public function loadData(KosgladApiService $api): void
    {
        // Usa o service autenticado (Bearer + x-app-signature já aplicados)
        $response = Http::withoutVerifying()
        ->withToken("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MTYwOSwibG9naW4iOiJrYXJpbG93IiwiaWF0IjoxNzU1NjIwMDM1LCJleHAiOjE3NTU2NjMyMzV9.iNree01gIIlZcASmMxVw7jxTHlz0UajIWcuDOcKGCk0")
        ->withHeaders([ 
            'x-app-signature' => '6d3b5711416e919104ca64c3281867cfec6d49788a85ce3681491a66a24cba7d', 
            'Accept' => 'application/json', 
            'Content-Type' => 'application/json', 
            ]) 
        ->get('https://cdn2008.kosglad.com.br/api/stats/olympiad/ranking');

        $fav = array_map('intval', $this->favoriteClasses);
        // dd($response->json());
        if ($response->successful()) {
            $players = collect($response->json());

            // Normaliza favoritos para int (evita falhas com in_array estrito)
            $fav = array_map('intval', $this->favoriteClasses);

            $this->groupedPlayers = $players
                ->sortByDesc('olympiad_point')
                ->groupBy('subjob0_class')
                ->mapWithKeys(function ($group, $classId) {
                    $id = (int) $classId;
                    return [
                        $id => [
                            'id'      => $id,
                            'name'    => self::className($id),
                            'players' => $group->take(3)->values(),
                            'points'  => (int) $group->sum('olympiad_point'),
                        ],
                    ];
                })
                // Favoritos primeiro (0) e, dentro de cada grupo, ID crescente
                ->sortBy(function ($item) use ($fav) {
                    return [
                        in_array((int)$item['id'], $fav, true) ? 0 : 1, // favoritos antes
                        (int)$item['id'],                               // ID asc
                    ];
                })
                ->values();
        } else {
            $this->groupedPlayers = collect();
        }

        $this->lastUpdated = now()->format('H:i:s');
    }

    // Wire-poll opcional: chame em <div wire:poll.30s="poll">
    public function poll(KosgladApiService $api): void
    {
        $this->loadData($api);
    }

    public function render()
    {
        return view('livewire.stats.olimpiadas');
    }

    /**
     * Recebe favoritos do front (visitante/localStorage) e recarrega a lista para
     * aplicar a ordenação “favoritos primeiro”.
     */
    public function setFavorites(array $favorites): void
{
    $this->favoriteClasses = array_map('intval', $favorites['favorites'] ?? []);
    $this->loadData(app(\App\Services\KosgladApiService::class));
}

    /**
     * Alterna favorito de uma classe (id numérico).
     * Salva no localStorage via evento; se quiser persistir no banco para usuários logados,
     * aqui é o lugar – basta checar Auth::check() e gravar.
     */
    public function toggleFavorite(int $classId): void
    {
        if (in_array($classId, $this->favoriteClasses, true)) {
            // remove
            $this->favoriteClasses = array_values(array_filter(
                $this->favoriteClasses,
                fn ($id) => (int)$id !== (int)$classId
            ));
        } else {
            // adiciona
            $this->favoriteClasses[] = (int)$classId;
        }

        // salva no localStorage (front)
        $this->dispatch('save-favorites', favorites: $this->favoriteClasses);

        // Recarrega para aplicar o “favoritos primeiro”
        $this->loadData(app(KosgladApiService::class));
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
            105 => "Eva's Saint",
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
            default => "Classe {$id}",
        };
    }
}
