<div
    x-data="{
        key: 'boss_favs',
        init() {
            const favs = JSON.parse(localStorage.getItem(this.key) || '[]');
            $wire.setBossFavorites({ favorites: favs });
            $wire.on('save-boss-favorites', ({ favorites }) => {
                localStorage.setItem(this.key, JSON.stringify(favorites || []));
            });
        }
    }"
    x-init="init()"
>
    <div class="flex flex-col">
        <div class="justify-items-center">
            <h4 class="justify-center m-4 text-4xl font-extrabold leading-none tracking-tight text-zinc-900 md:text-5xl lg:text-3xl dark:text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r to-green-400 from-sky-400">Bosses</span>
            </h4>
            <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
            ['label' => 'Bosses', 'url' => route('bosses')],
        ]" />
        </div>
    </div>

    <div class="w-full max-w-5xl mx-auto px-4 py-8" wire:poll.30s="poll">
        {{-- barra de ações: busca + só favoritos --}}
        <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input
                    type="search"
                    wire:model.live.debounce.300ms="q"
                    placeholder="Buscar boss…"
                    class="w-full sm:w-80 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-sky-400"
                />
                <label class="inline-flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <input type="checkbox" wire:model.live="onlyFav" class="rounded border-zinc-300 dark:border-zinc-700">
                    Só favoritos
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div class="relative overflow-x-auto shadow-sm ring-1 ring-zinc-200/70 dark:ring-zinc-800/70 sm:rounded-2xl">
                <table class="w-full text-sm text-left rtl:text-right text-zinc-600 dark:text-zinc-300">
                    <thead class="text-xs uppercase bg-zinc-50 dark:bg-zinc-800/70 text-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Fav</th>
                            <th scope="col" class="px-6 py-3">Nome</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Hora da morte</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800/70 bg-white dark:bg-zinc-900">
                        @foreach ($filteredBosses as $boss)
                            @php
                                // ajuste a chave se necessário: $boss['id'] ou $boss['boss_id'] etc.
                                $id = (int)($boss['id'] ?? $boss['boss_id'] ?? crc32($boss['display_name']));
                                $isFav = in_array($id, $favoriteBosses ?? [], true);
                            @endphp
                            <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/60 transition" wire:key="boss-{{ $id }}">
                                <td class="px-4 py-2">
                                    <button
                                        type="button"
                                        wire:click="toggleBossFavorite({{ $id }})"
                                        class="rounded-full p-2 ring-1 ring-inset ring-transparent hover:ring-zinc-300 dark:hover:ring-zinc-700 transition"
                                        title="{{ $isFav ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                                    >
                                        @if ($isFav)
                                            <svg class="h-5 w-5 text-emerald-300" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z"/>
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-zinc-500" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </td>
                                <th scope="row" class="px-4 py-2 font-semibold text-zinc-900 whitespace-nowrap dark:text-yellow-500">
                                    {{ $boss['display_name'] }}
                                </th>
                                <td class="px-4 py-2 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                    @if ($boss['alive'])
                                        <span class="text-green-600 font-bold">Vivo</span>
                                    @else
                                        <span class="text-red-600 font-bold">Morto</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    {{ $boss['respawn_time'] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>    
                </table>

                {{-- overlay de loading --}}
                <div wire:loading class="absolute inset-0 grid place-items-center bg-white/50 dark:bg-black/30 backdrop-blur-sm rounded-2xl">
                    <div class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" stroke-width="2" class="opacity-25"/>
                            <path d="M4 12a8 8 0 0 1 8-8" stroke-width="2" class="opacity-75"/>
                        </svg>
                        Atualizando…
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
