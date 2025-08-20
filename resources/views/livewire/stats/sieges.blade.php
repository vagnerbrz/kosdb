<div>
    <div class="flex flex-col">
        <div class="justify-items-center">
            <h4
                class="justify-center m-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-3xl dark:text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r to-green-400 from-sky-400">Sieges</span>
            </h4>
            <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
            ['label' => 'Sieges', 'url' => route('sieges')],
        ]" />
        </div>
    </div>

  {{-- Header permanece igual --}}

<div class="w-full max-w-7xl mx-auto px-4 py-8">
  {{-- Barra de ações --}}
  <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <div class="text-sm text-zinc-600 dark:text-zinc-400">
      Próximas guerras de castelo • fique de olho!
    </div>
  </div>

  {{-- Grid de castelos --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-5">
    @forelse ($castelos as $castle)
      @php
        $uid = (int)($castle['id'] ?? $castle['castle_id'] ?? 0);
        if ($uid === 0) { $uid = (int) sprintf('%u', crc32($castle['display_name'] ?? 'castle')); }
        // Se você tiver timestamp em segundos para a próxima siege:
        $nextUnix = $castle['sdate_unix'] ?? null; // opcional
      @endphp

      <div class="relative group" wire:key="castle-{{ $uid }}">

        {{-- card com borda gradiente --}}
        <div class="relative rounded-3xl p-[2px] bg-gradient-to-r from-sky-500 to-emerald-400 shadow">
          <div class="rounded-[22px] bg-white dark:bg-zinc-900 p-5 h-full flex flex-col">
            {{-- header do card --}}
            <div class="flex items-start justify-between gap-3">
              <div>
                <h5 class="text-lg font-extrabold tracking-tight text-zinc-900 dark:text-white">
                  {{ $castle['display_name'] }}
                </h5>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                  Taxa atual: <span class="font-medium text-amber-600 dark:text-amber-400">{{ $castle['stax'] }}%</span>
                </p>
              </div>

              {{-- status “próxima siege” --}}
              <div
                x-data="countdown({{ $nextUnix ? (int)$nextUnix : 'null' }})"
                x-init="init()"
                class="text-right"
              >
                @if ($nextUnix)
                  <div class="text-[10px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Falta</div>
                  <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100" x-text="label">--:--:--</div>
                  <div class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                    {{ $castle['sdate_formatted'] }}
                  </div>
                @else
                  <span class="inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-800 px-2 py-1 text-[11px] text-zinc-600 dark:text-zinc-300">
                    Sem data
                  </span>
                @endif
              </div>
            </div>

            {{-- dono / clã / ally --}}
            <div class="mt-4 grid grid-cols-2 gap-3">
              <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 p-3">
                <div class="text-[11px] uppercase tracking-wide text-zinc-500">Clã</div>
                <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                  {{ $castle['clan_name'] ?? '—' }}
                </div>
              </div>
              <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 p-3">
                <div class="text-[11px] uppercase tracking-wide text-zinc-500">Líder</div>
                <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                  {{ $castle['char_name'] ?? '—' }}
                </div>
              </div>
              <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 p-3 col-span-2">
                <div class="text-[11px] uppercase tracking-wide text-zinc-500">Aliança</div>
                <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">
                  {{ $castle['ally_name'] ?? '—' }}
                </div>
              </div>
            </div>

            {{-- atacantes / defensores --}}
            <div class="mt-4 space-y-2">
              <div>
                <div class="text-[11px] uppercase tracking-wide text-zinc-500 mb-1">⚔️ Ataque</div>
                <div class="flex flex-wrap gap-1.5">
                  @if (!empty($castle['attackers']))
                    @foreach ($castle['attackers'] as $clan)
                      <span class="rounded-md bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300 border border-rose-300/70 dark:border-rose-700/60 px-2 py-0.5 text-[11px] font-medium">{{ $clan }}</span>
                    @endforeach
                  @else
                    <span class="text-xs text-zinc-500">Nenhum atacante</span>
                  @endif
                </div>
              </div>
              <div>
                <div class="text-[11px] uppercase tracking-wide text-zinc-500 mb-1">🛡️ Defesa</div>
                <div class="flex flex-wrap gap-1.5">
                  @if (!empty($castle['defenders']))
                    @foreach ($castle['defenders'] as $clan)
                      <span class="rounded-md bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-300 border border-sky-300/70 dark:border-sky-700/60 px-2 py-0.5 text-[11px] font-medium">{{ $clan }}</span>
                    @endforeach
                  @else
                    <span class="text-xs text-zinc-500">Nenhum defensor</span>
                  @endif
                </div>
              </div>
            </div>

            {{-- rodapé --}}
            <div class="mt-5 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
              <span>Última atualização: {{ now()->format('d/m H:i') }}</span>
              {{-- <a href="{{ route('sieges') }}" class="inline-flex items-center gap-1 hover:text-sky-600 dark:hover:text-sky-400">
                Detalhes
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
              </a> --}}
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full">
        <div class="rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-8 text-center">
          <p class="text-sm text-zinc-500 dark:text-zinc-400">Nenhum castelo encontrado.</p>
        </div>
      </div>
    @endforelse
  </div>
</div>

{{-- Alpine helper: contador regressivo opcional (usa timestamp em segundos, UTC) --}}
<script>
  function countdown(unixTs) {
    return {
      label: '—',
      t: null,
      init() {
        if (!unixTs) { this.label = '—'; return; }
        const tick = () => {
          const now = Math.floor(Date.now()/1000);
          let diff = unixTs - now;
          if (diff <= 0) { this.label = 'Começando!'; clearInterval(this.t); return; }
          const d = Math.floor(diff/86400); diff %= 86400;
          const h = Math.floor(diff/3600); diff %= 3600;
          const m = Math.floor(diff/60);   const s = diff%60;
          this.label = `${d}d ${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        };
        tick();
        this.t = setInterval(tick, 1000);
      }
    }
  }
</script>

