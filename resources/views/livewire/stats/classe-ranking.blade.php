<div>
    {{-- Header / Hero compacto --}}
    <div class="flex flex-col items-center text-center px-4 pt-6">
        <h4 class="m-2 text-3xl md:text-5xl lg:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-emerald-400">
                {{ $className }}
            </span>
        </h4>

        <div class="mt-2 flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
            <span class="inline-flex items-center gap-1">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Última atualização: <span class="font-medium">{{ $lastUpdated }}</span>
            </span>
        </div>
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
            ['label' => 'Olimpíadas', 'url' => route('olimpiadas')],
            ['label' => $className, 'url' => null], // página atual
        ]" />
    </div>

    {{-- Conteúdo --}}
    <div class="w-full max-w-5xl mx-auto px-4 py-8">
        {{-- Barra de ações --}}
        <div class="flex items-center justify-between gap-4 mb-4">
            <div class="text-sm text-zinc-600 dark:text-zinc-400">
                Top jogadores da classe <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $className }}</span>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="poll"
                        class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-100 dark:text-zinc-200 dark:ring-zinc-700 dark:hover:bg-zinc-800 transition">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 4v6h-6"/><path d="M20.49 15A9 9 0 1 1 18 8.51"/>
                    </svg>
                    Atualizar
                </button>
            </div>
        </div>

        {{-- Tabela (desktop) --}}
        <div class="hidden md:block relative overflow-x-auto shadow-sm ring-1 ring-zinc-200/70 dark:ring-zinc-800/70 sm:rounded-2xl"
             wire:poll.30s="poll">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-300">
                <thead class="text-xs uppercase bg-zinc-50/80 dark:bg-zinc-800/70 text-zinc-700 dark:text-zinc-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nick</th>
                        <th scope="col" class="px-6 py-3">Clã</th>
                        <th scope="col" class="px-6 py-3">Ally</th>
                        <th scope="col" class="px-6 py-3 text-right">Pontos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800/70 bg-white dark:bg-zinc-900">
                    @forelse ($players as $player)
                        <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/60 transition">
                            <th scope="row" class="px-6 py-3 font-semibold text-zinc-900 dark:text-white">
                                {{ $player['char_name'] }}
                            </th>
                            <td class="px-6 py-3">
                                {{ $player['clan_name'] }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $player['ally_name'] ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ $player['olympiad_point'] ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-zinc-500">
                                Nenhum jogador encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- estado de loading --}}
            <div wire:loading class="absolute inset-0 grid place-items-center bg-white/50 dark:bg-black/30 backdrop-blur-sm rounded-2xl">
                <div class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2" class="opacity-25"/><path d="M4 12a8 8 0 0 1 8-8" stroke-width="2" class="opacity-75"/></svg>
                    Atualizando…
                </div>
            </div>
        </div>

        {{-- Cards (mobile) --}}
        <div class="md:hidden space-y-3" wire:poll.30s="poll">
            @forelse ($players as $player)
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-base font-semibold text-zinc-900 dark:text-white">
                                {{ $player['char_name'] }}
                            </div>
                            <div class="mt-1 text-xs text-zinc-500">
                                Clã: <span class="text-zinc-700 dark:text-zinc-300">{{ $player['clan_name'] ?? '—' }}</span>
                                <span class="px-1.5">•</span>
                                Ally: <span class="text-zinc-700 dark:text-zinc-300">{{ $player['ally_name'] ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs uppercase tracking-wide text-zinc-500">Pontos</div>
                            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $player['olympiad_point'] ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-sm text-zinc-500">Nenhum jogador encontrado.</p>
            @endforelse

            <div wire:loading class="flex justify-center">
                <div class="inline-flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2" class="opacity-25"/><path d="M4 12a8 8 0 0 1 8-8" stroke-width="2" class="opacity-75"/></svg>
                    Atualizando…
                </div>
            </div>
        </div>
    </div>
</div>
