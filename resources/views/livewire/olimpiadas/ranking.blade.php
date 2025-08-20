{{-- resources/views/livewire/olimpiadas/ranking.blade.php --}}
<div
    x-data="olymFavs()"
    x-init="init()"
    class="min-h-screen bg-gradient-to-b from-white to-zinc-50 dark:from-zinc-900 dark:to-black"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                    Ranking das Olimpíadas
                </h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    Busque por classe e marque suas favoritas.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <input
                    wire:model.live.debounce.300ms="q"
                    type="search"
                    placeholder="Buscar classe…"
                    class="rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-2 text-sm
                           text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-sky-400"
                />

                <label class="inline-flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <input type="checkbox" wire:model.live="onlyFav" class="rounded border-zinc-300 dark:border-zinc-700">
                    Só favoritos
                </label>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($items as $i => $row)
                @php
                    $code = $row['class_code'];
                    $isFav = in_array($code, $favorites ?? [], true);
                @endphp

                <div
                    class="group rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 hover:shadow-md transition"
                    wire:key="card-{{ $code }}"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                {{ $row['class_name'] }}
                            </h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                Código: {{ $row['class_code'] }}
                            </p>
                        </div>

                        {{-- Botão Favoritar --}}
                        <button
                            type="button"
                            wire:click.stop="toggleFavorite('{{ $code }}')"
                            x-on:click="pulse($el)"
                            class="rounded-full p-2 ring-1 ring-inset ring-transparent hover:ring-zinc-300
                                   dark:hover:ring-zinc-700 transition"
                            aria-label="Favoritar"
                            title="{{ $isFav ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                        >
                            @if ($isFav)
                                {{-- Coração preenchido --}}
                                <svg class="h-5 w-5 text-rose-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 21s-6.716-4.455-9.428-7.167A5.5 5.5 0 1 1 11.95 5.05L12 5.1l.05-.05a5.5 5.5 0 1 1 8.378 8.782C18.716 16.545 12 21 12 21z"/>
                                </svg>
                            @else
                                {{-- Coração contorno --}}
                                <svg class="h-5 w-5 text-zinc-500 group-hover:text-rose-500 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 21s-6.716-4.455-9.428-7.167A5.5 5.5 0 1 1 11.95 5.05L12 5.1l.05-.05a5.5 5.5 0 1 1 8.378 8.782C18.716 16.545 12 21 12 21z"/>
                                </svg>
                            @endif
                        </button>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            Pontos
                        </div>
                        <div class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ $row['points'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (empty($items))
            <p class="mt-10 text-center text-sm text-zinc-500">Nenhum resultado encontrado.</p>
        @endif
    </div>

    {{-- Alpine: Fallback de favoritos para visitantes via localStorage --}}
    <script>
        function olympFavs() {
            return {
                key: 'olymp_favs',
                init() {
                    // Visitante: carrega favs e envia para Livewire
                    @if(!auth()->check())
                        const favs = JSON.parse(localStorage.getItem(this.key) || '[]');
                        $wire.dispatch('guest-favs-loaded', { codes: favs });
                        // Atualiza quando Livewire avisar para sync
                        $wire.on('guest-fav-sync', ({ code, op }) => {
                            let arr = new Set(JSON.parse(localStorage.getItem(this.key) || '[]'));
                            if (op === 'add') arr.add(code);
                            if (op === 'remove') arr.delete(code);
                            localStorage.setItem(this.key, JSON.stringify(Array.from(arr)));
                        });
                    @endif
                },
                pulse(el) {
                    el.classList.add('scale-95');
                    setTimeout(() => el.classList.remove('scale-95'), 120);
                }
            }
        }
    </script>
</div>
