<div>
    <div class="flex flex-col">
        <div class="justify-items-center">
            <h4
                class="justify-center m-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-3xl dark:text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r to-green-400 from-sky-400">Sieges</span>
            </h4>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span
                                class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Sieges</span>
                        </div>
                    </li>
                </ol>
            </nav>

        </div>
    </div>

    <div class="m-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-2">
            @forelse ($castelos as $castle)
                <div class="w-full max-w-md bg-white border border-gray-200 rounded-lg shadow-sm p-6 dark:bg-zinc-800 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <h5 class="text-xl font-bold leading-none text-zinc-900 dark:text-white">
                            {{ $castle['display_name'] }}
                        </h5>
                    </div>
                    <div class="flow-root">
                        <ul role="list" class="">
                                <li class="">
                                    <div class="flex items-center">
                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-zinc-900 truncate dark:text-white">
                                                Clã 
                                            </p>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-base text-yellow-600 dark:text-yellow-400">
                                            {{ $castle['char_name'] ?? '---' }}
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="flex items-center">
                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-zinc-900 truncate dark:text-white">
                                                Líder do Clã 
                                            </p>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-base text-yellow-600 dark:text-yellow-400">
                                            {{ $castle['clan_name'] ?? '---' }}
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="flex items-center">
                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-zinc-900 truncate dark:text-white">
                                                Aliança 
                                            </p>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-base text-yellow-600 dark:text-yellow-400">
                                            {{ $castle['ally_name'] ?? '---' }}
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="flex items-center">
                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-zinc-900 truncate dark:text-white">
                                                Próxima Siege 
                                            </p>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-base text-yellow-600 dark:text-yellow-400">
                                            {{ $castle['sdate_formatted'] }}
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="flex items-center">
                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-zinc-900 truncate dark:text-white">
                                                Taxa 
                                            </p>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-base text-yellow-600 dark:text-yellow-400">
                                            {{ $castle['stax'] }}%
                                        </div>
                                    </div>
                                </li>
                        </ul>
                        <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">
                        <div class="relative sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                                <tbody>
                                    <tr>
                                        <td class="py-1 sm:py-2"> ⚔️ Ataque</td>
                                        <td>
                                            @if (count($castle['attackers']))
                                                <ul class="list-disc list-inside">
                                                    @foreach ($castle['attackers'] as $clan)
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-red-400 border border-red-400">
                                                            {{ $clan }}
                                                        </span>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-gray-500">Nenhum atacante registrado.</p>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 sm:py-2"> 🛡️ Defesa</td>
                                        <td class="">
                                            @if (count($castle['defenders']))
                                                <ul class="list-disc list-inside">
                                                    @foreach ($castle['defenders'] as $clan)
                                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                                            {{ $clan }}
                                                        </span>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-gray-500">Nenhum defensor registrado.</p>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Nenhum castelo encontrado.</p>
            @endforelse
        </div>
    </div>
