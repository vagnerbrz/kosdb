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
    public string $q = '';
    public bool $onlyFav = false;
    public array $favoriteBosses = []; // ids dos bosses favoritados

    protected $listeners = ['setBossFavorites' => 'setBossFavorites'];
    
    public function render()
    {
        return view('livewire.stats.bosses', [
        'filteredBosses' => $this->filteredBosses, // chama a computed
    ]);
    }
    

    public function mount()
    {
        $this->loadData(app(KosgladApiService::class));
    }

    public function poll(KosgladApiService $api): void
    {
        $this->loadData($api);
    }

    public function loadData(KosgladApiService $api)
    {

        $token = $api->getToken();

        $response = Http::withoutVerifying()
        ->withToken($token)
        ->withHeaders([
                    'x-app-signature' => 'db4d78a29d353e2de33923f19c370ef2400a6b64967c037168016970b7b3fb33',
                    'Accept'          => 'application/json',
                    'Content-Type'    => 'application/json',
                ])  
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

    public function getFilteredBossesProperty()
    {
        // $this->bosses = sua coleção original (array/Collection) – ajuste conforme sua origem
        $items = collect($this->bosses ?? []);

        // normaliza ids e favoritos
        $fav = array_map('intval', $this->favoriteBosses);

        // busca por nome
        if ($this->q !== '') {
            $q = mb_strtolower($this->q);
            $items = $items->filter(function ($b) use ($q) {
                $name = mb_strtolower($b['display_name'] ?? '');
                return str_contains($name, $q);
            });
        }

        // só favoritos
        if ($this->onlyFav) {
            $items = $items->filter(function ($b) use ($fav) {
                $id = (int)($b['id'] ?? $b['boss_id'] ?? crc32($b['display_name'] ?? ''));
                return in_array($id, $fav, true);
            });
        }

        // ordenação: favoritos primeiro, depois nome
        return $items->sort(function ($a, $b) use ($fav) {
            $aId = (int)($a['id'] ?? $a['boss_id'] ?? crc32($a['display_name'] ?? ''));
            $bId = (int)($b['id'] ?? $b['boss_id'] ?? crc32($b['display_name'] ?? ''));
            $aFav = in_array($aId, $fav, true);
            $bFav = in_array($bId, $fav, true);
            if ($aFav !== $bFav) return $aFav ? -1 : 1;

            return strcmp($a['display_name'] ?? '', $b['display_name'] ?? '');
        })->values();
    }

    // Toggle favorito (id numérico)
    public function toggleBossFavorite(int $bossId): void
    {
        if (in_array($bossId, $this->favoriteBosses, true)) {
            $this->favoriteBosses = array_values(array_filter(
                $this->favoriteBosses, fn ($id) => (int)$id !== $bossId
            ));
        } else {
            $this->favoriteBosses[] = $bossId;
        }

        // salva no storage (front)
        $this->dispatch('save-boss-favorites', favorites: $this->favoriteBosses);
    }

    // Carrega favoritos do localStorage
    public function setBossFavorites(array $payload): void
    {
        $this->favoriteBosses = array_map('intval', $payload['favorites'] ?? []);
    }
}
