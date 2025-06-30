<div>
    <div class="flex flex-col">
        <div class="justify-items-center">
            <h4 class="justify-center m-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-3xl dark:text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r to-green-400 from-sky-400">{{ $className }}</span>
            </h4>
            <p class="m-2 text-sm text-gray-500">Última atualização: {{ $lastUpdated }}</p>


            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center text-sm font-medium text-zinc-700 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center text-sm font-medium text-zinc-700 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Olimpíadas
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-zinc-500 md:ms-2 dark:text-zinc-400">{{ $className }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

        </div>
    </div>

    <div class="w-full max-w-4xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 gap-4 p-4 ">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg" wire:poll.30s="poll">
                <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                    <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Nick
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Clã
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Ally
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Pontos
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($players as $player)
                            <tr
                                class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                                <th scope="row"
                                    class="px-4 py-2 font-bold text-zinc-900 whitespace-nowrap dark:text-white">
                                    {{ $player['char_name'] }}
                                </th>
                                <td class="px-4 py-2">
                                    {{ $player['clan_name'] }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $player['ally_name']  ?? '-'}}
                                </td>
                                <td class="px-4 py-2 text-base font-semibold text-green-600 dark:text-green-400">
                                    {{ $player['olympiad_point'] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>