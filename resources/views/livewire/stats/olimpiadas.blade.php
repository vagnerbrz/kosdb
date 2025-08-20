<div
x-data="{

        key: 'olymp_fav_classes',
        init() {
            // 1) carrega do localStorage e envia pro PHP
            const favs = JSON.parse(localStorage.getItem(this.key) || '[]');
            $wire.setFavorites({ favorites: favs });

            // 2) escuta o evento emitido pelo PHP e salva no localStorage
            $wire.on('save-favorites', ({ favorites }) => {
                localStorage.setItem(this.key, JSON.stringify(favorites || []));
            });
        }
            
    }"
    x-init="init()">
    <div x-data="{
        classes: @js($groupedPlayers ?? []),

        // monta o texto e copia
        async copyAll() {
            const lines = [];

            (this.classes || []).forEach(c => {
                if (!c || !c.name) return;

                lines.push(c.name);

                const top3 = (c.players || []).slice(0, 3);
                top3.forEach((p, idx) => {
                    const rank = idx + 1;
                    const nick = p?.char_name ?? '—';
                    const pts  = p?.olympiad_point ?? 0;
                    lines.push(`${rank} - ${nick} (${pts} pontos)`);
                });

                lines.push(''); // linha em branco entre classes
            });

            const text = lines.join('\n');

            try {
                await navigator.clipboard.writeText(text);
                this.toast('Top 3 de todas as classes copiado!');
            } catch (e) {
                // fallback: cria um textarea temporário
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                ta.remove();
                this.toast('Top 3 de todas as classes copiado!');
            }
        },

        // feedback simples
        toast(msg) {
            const el = document.createElement('div');
            el.textContent = msg;
            el.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 z-50 rounded-xl bg-zinc-900 text-white px-4 py-2 text-sm shadow';
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 1800);
        }
    }"
    
    class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-emerald-500">
                    Ranking das Olimpíadas
                </h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    Top 3 jogadores por classe • Atualizado às {{ $lastUpdated }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button
                    @click="copyAll()"
                    class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-100
                        dark:text-zinc-200 dark:ring-zinc-700 dark:hover:bg-zinc-800 transition"
                    title="Copiar Top 3 de todas as classes"
                >
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    Copiar
                </button>
                <button
                    wire:click="poll"
                    class="rounded-xl px-3 py-2 text-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-100
                           dark:text-zinc-200 dark:ring-zinc-700 dark:hover:bg-zinc-800 transition"
                >
                    Atualizar
                </button>
            </div>
        </div>

        {{-- Grid de classes --}}
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" wire:poll.30s="poll">
            @foreach ($groupedPlayers as $c)
                <div
                    class="group rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 hover:shadow-md transition"
                    wire:key="class-{{ $c['id'] }}"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                {{ $c['name'] }}
                            </h3>
                            <a href="{{ route('classe.ranking', $c['id'])}}" class="text-xs text-blue-700">Ver Todos </a>
                        </div>

                        {{-- Botão Favoritar Classe --}}
                        @php $isFav = in_array($c['id'], $favoriteClasses ?? [], true); @endphp
                        <button
                            type="button"
                            wire:click.stop="toggleFavorite({{ $c['id'] }})"
                            x-on:click="pulse($el)"
                            class="rounded-full p-2 ring-1 ring-inset ring-transparent hover:ring-zinc-300
                                   dark:hover:ring-zinc-700 transition m-0"
                            aria-label="Favoritar classe"
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
                    </div>

                    {{-- Top 3 jogadores da classe --}}
                    <div class="mt-4 space-y-2">
                        @forelse ($c['players'] as $idx => $p)
                            <div class="flex items-center justify-between rounded-xl border border-zinc-200 dark:border-zinc-800 px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <span @class([
                                            'text-sm',
                                            'text-yellow-500' => $idx === 0,  // Ouro para 1º
                                            'text-zinc-500 dark:text-zinc-300' => $idx > 2, // Cor padrão para outros
                                        ])>
                                        #{{ $idx + 1 }}
                                    </span>
                                    <span class="text-sm text-zinc-800 dark:text-zinc-100">
                                        {{ $p['char_name'] ?? '—' }}
                                    </span>
                                </div>
                                <div class="text-sm text-zinc-600 dark:text-zinc-300">
                                    {{ $p['olympiad_point'] ?? 0 }} pts
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500">Sem jogadores nesta classe.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        @if (($groupedPlayers ?? collect())->isEmpty())
            <p class="mt-10 text-center text-sm text-zinc-500">Nenhum dado disponível agora.</p>
        @endif
    </div>

    {{-- Alpine bridge: favoritos do visitante via localStorage --}}
    <script>
        function olympFavs() {
            return {
                key: 'olymp_fav_classes',
                init() {
                    // Carrega favoritos do visitante e envia ao Livewire
                    const favs = JSON.parse(localStorage.getItem(this.key) || '[]');
                    $wire.setFavorites({ favorites: favs });

                    // Ouve o evento de salvar emitido pelo Livewire
                    $wire.on('save-favorites', ({ favorites }) => {
                        localStorage.setItem(this.key, JSON.stringify(favorites || []));
                    });
                },
                pulse(el) {
                    el.classList.add('scale-95');
                    setTimeout(() => el.classList.remove('scale-95'), 120);
                }
            }
        }
    </script>
</div>
