{{-- Header permanece igual --}}

<div class="w-full max-w-6xl mx-auto px-4 py-8">

    <div class="flex flex-col">
        <div class="justify-items-center">
            <h4
                class="justify-center m-4 text-4xl font-extrabold leading-none tracking-tight text-zinc-900 md:text-5xl lg:text-3xl dark:text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r to-amber-400 from-yellow-600">Heros</span>
            </h4>
            <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
            ['label' => 'Heros', 'url' => route('heros')],
        ]" />
        </div>
    </div>
    {{-- grade de cards --}}
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($players as $player)
            <div class="relative group">
  {{-- glow atrás --}}
  <div
    class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-amber-200 via-amber-500 to-yellow-300 opacity-10 blur-2xl group-hover:opacity-90 transition"
  ></div>

  {{-- borda gradiente + card --}}
  <div class="relative rounded-3xl p-[2px] bg-gradient-to-r to-amber-200 via-amber-500 from-yellow-300 shadow-md">
    <div class="rounded-[22px] bg-white dark:bg-zinc-900 p-5">
      {{-- conteúdo do card --}}
      <div class="flex items-start gap-3">
        <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-amber-400 to-rose-500 flex items-center justify-center shadow-sm">
          <svg viewBox="0 0 24 24" class="h-6 w-6 text-white" fill="currentColor">
            <path d="M3 7l4 3 5-6 5 6 4-3v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-lg font-extrabold tracking-tight text-zinc-900 dark:text-white">
            {{ $player['class_name'] }}
          </h3>
          <p class="text-xs text-zinc-500 dark:text-zinc-400">O grande campeão do mês</p>
        </div>
      </div>

      <div class="mt-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs uppercase text-zinc-500">Nick</span>
          <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
            {{ $player['char_name'] }}
          </span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-xs uppercase text-zinc-500">Clã</span>
          <span class="text-sm text-zinc-800 dark:text-zinc-200">
            {{ $player['clan_name'] ?: '—' }}
          </span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-xs uppercase text-zinc-500">Ally</span>
          <span class="text-sm text-zinc-800 dark:text-zinc-200">
            {{ $player['ally_name'] ?: '—' }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
        @empty
            <div class="col-span-full">
                <div class="rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-8 text-center">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Nenhum herói definido no momento.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- loading overlay opcional (se a página usa wire:poll) --}}
    <div wire:loading class="fixed inset-0 pointer-events-none grid place-items-center">
        <div
            class="rounded-xl bg-white/70 dark:bg-black/40 backdrop-blur px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200 shadow">
            Atualizando…
        </div>
    </div>
</div>
