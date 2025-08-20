<?php

namespace App\Livewire\Stats;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use App\Services\KosgladApiService;

class Sieges extends Component
{
    public array $castelos = [];

    /** Ajuste se seu app usar outro TZ */
    private string $tz = 'America/Manaus';

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
        return $api->getToken();
    }

    public function loadData()
    {
        // Recomendado: usar o service autenticado (descomente quando quiser)
        $token = $this->token(app(KosgladApiService::class));
        // $response = Http::withoutVerifying()
        //     ->withToken($token)
        //     ->withHeaders([
        //         'x-app-signature' => 'SUA_ASSINATURA_AQUI',
        //         'Accept'          => 'application/json',
        //         'Content-Type'    => 'application/json',
        //     ])
        //     ->get('https://cdn2008.kosglad.com.br/api/stats/siege');

        // Temporário: chamada direta mantendo o que você tinha


        
        $response = Http::withoutVerifying()
        ->withToken($token)
            ->withHeaders([
                'x-app-signature' => '3f598c6a1b8c3944c87170d99c33622e9b76781d4eb6b818dc47115ebd9b865e',
                'Accept'          => 'application/json',
                'Content-Type'    => 'application/json',
            ])
            ->get('https://cdn2008.kosglad.com.br/api/stats/siege');

        if (! $response->successful()) {
            $this->castelos = [];
            return;
        }

        $base = collect($response->json())
            ->map(function (array $castelo) {
                // Nome amigável
                $castelo['display_name'] = self::castleName($castelo['name'] ?? '');

                // Normaliza sdate_unix (segundos) e sdate_formatted no TZ local
                $castelo['sdate_unix'] = null;
                if (isset($castelo['sdate']) && is_numeric($castelo['sdate'])) {
                    $ts = (int) $castelo['sdate'];
                    // se vier em milissegundos, normaliza
                    if ($ts > 9999999999) {
                        $ts = (int) floor($ts / 1000);
                    }
                    $castelo['sdate_unix'] = $ts;
                }

                if ($castelo['sdate_unix']) {
                    $castelo['sdate_formatted'] = Carbon::createFromTimestamp($castelo['sdate_unix'], 'UTC')
                        ->setTimezone($this->tz)
                        ->format('d/m/Y H:i');
                } else {
                    // mantém o que vier da API ou coloca '—'
                    $castelo['sdate_formatted'] = $castelo['sdate_formatted'] ?? '—';
                }

                // Placeholders
                $castelo['attackers'] = [];
                $castelo['defenders'] = [];

                return $castelo;
            })
            ->values();

        // Busca participantes (atacantes/defensores) para cada castelo
        $this->castelos = $base
            ->map(function (array $castelo) {
                $castleId  = $castelo['id'] ?? null;
                $ownerClan = $castelo['clan_name'] ?? null; // clã dono atual do castelo

                if ($castleId === null) {
                    return $castelo;
                }
                $signature = '';
                switch ($castleId)
                {
                    case 1:
                        $signature = '92a1e898c10a5ff8266851611de03d77f50506155682b91685104e3d64198469';
                        break;
                    case 2:
                        $signature = '2416fd0e2236572278d2d54127bc46e58f850f75281e597fe9214906d7c42996';
                        break;
                    case 3:
                        $signature = '7caeba885844022f8e8e30f1204ce0736dd1a1bf7d631d1e70c53a1bbb41a561';
                        break;
                    case 4:
                        $signature = 'b19db66ba460046a343caa265befb747e5186df7f4c27b032b91f3a485b5390f';
                        break;
                    case 5:
                        $signature = 'fdac482d7df4bd911f57841da45061bfc159fe0416da0a20ed5831b22873297f';
                        break;
                    case 6:
                        $signature = '59934f311a8cde206fd1a4324c071763241a4d5b2d2eddd43175d0337213c167';
                        break;
                    case 7:
                        $signature = 'd53889a6863ba61c81ffab4b53f940f148b7328aa5160c48c3fef93bd7b57be5';
                        break;
                    case 8:
                        $signature = '873a1059384ca5954283a7121e4014156d2a692cf514e64bc2a1f321e6cff4a1';
                        break;
                    case 9:
                        $signature = '3713002ff56fe625088345da075bd5deadf76703c130a74321f5f298a2b42d4b';
                        break;
                }
                $token = $this->token(app(KosgladApiService::class));
                $participants = Http::withoutVerifying()
                    ->withToken($token)
                    ->withHeaders([
                        'x-app-signature' => $signature,
                        'Accept'          => 'application/json',
                        'Content-Type'    => 'application/json',
                    ])
                    ->get("https://cdn2008.kosglad.com.br/api/stats/siege/{$castleId}/participants");
                // dd($participants->json());      
                $atk = [];
                $def = [];

                if ($participants->successful()) {
                    $data = $participants->json();

                    // 1) Estrutura já separada por chaves
                    if (is_array($data) && (isset($data['attackers']) || isset($data['defenders']))) {
                        foreach ((array)($data['attackers'] ?? []) as $row) {
                            $name = $row['clan_name'] ?? $row['name'] ?? null;
                            if ($name) $atk[] = $name;
                        }
                        foreach ((array)($data['defenders'] ?? []) as $row) {
                            $name = $row['clan_name'] ?? $row['name'] ?? null;
                            if ($name) $def[] = $name;
                        }
                    }
                    // 2) Estrutura plana: lista de clãs com type/role/side
                    elseif (is_array($data)) {
                        foreach ($data as $row) {
                            if (!is_array($row)) continue;

                            $name = $row['clan_name'] ?? $row['name'] ?? null;
                            if (!$name) continue;

                            $rawType = $row['type'] ?? $row['role'] ?? $row['side'] ?? null;
                            $side = null;

                            // numérico: 1=atk, 2/3=def (cubra variações)
                            if (is_numeric($rawType)) {
                                $t = (int)$rawType;
                                if ($t === 1) $side = 'atk';
                                elseif (in_array($t, [2,3], true)) $side = 'def';
                            }

                            // string: attacker/defender/owner...
                            if (!$side && is_string($rawType)) {
                                $rt = strtolower(trim($rawType));
                                if (in_array($rt, ['atk','attack','attacker'], true)) $side = 'atk';
                                if (in_array($rt, ['def','defense','defender','owner','holder'], true)) $side = 'def';
                            }

                            // fallback: se for o clã dono do castelo, considere defesa
                            if (!$side && $ownerClan && strcasecmp($name, $ownerClan) === 0) {
                                $side = 'def';
                            }

                            if ($side === 'atk') $atk[] = $name;
                            elseif ($side === 'def') $def[] = $name;
                            else {
                                // não identificado: por segurança, não classifica
                            }
                        }
                    }
                }

                // únicos + ordenados
                $castelo['attackers'] = collect($atk)->filter()->unique()->sort(SORT_NATURAL|SORT_FLAG_CASE)->values()->all();
                $castelo['defenders'] = collect($def)->filter()->unique()->sort(SORT_NATURAL|SORT_FLAG_CASE)->values()->all();

                return $castelo;
            })
            // Ordena: com data primeiro (mais próximas primeiro), depois sem data
            ->sort(function ($a, $b) {
                $aTs = $a['sdate_unix'] ?? null;
                $bTs = $b['sdate_unix'] ?? null;

                if ($aTs && $bTs) {
                    return $aTs <=> $bTs; // mais cedo primeiro
                }
                if ($aTs && ! $bTs) return -1; // com data antes
                if (! $aTs && $bTs) return 1;  // sem data depois
                return strcmp($a['display_name'] ?? '', $b['display_name'] ?? '');
            })
            ->values()
            ->toArray();
    }

    public static function castleName(string $key): string
    {
        return match ($key) {
            'gludio_castle'     => 'Gludio',
            'dion_castle'       => 'Dion',
            'giran_castle'      => 'Giran',
            'oren_castle'       => 'Oren',
            'aden_castle'       => 'Aden',
            'innadril_castle'   => 'Innadril',
            'goddard_castle'    => 'Goddard',
            'rune_castle'       => 'Rune',
            'schuttgart_castle' => 'Schuttgart',
            default             => ucwords(str_replace('_castle', '', str_replace('_', ' ', $key))),
        };
    }
}
