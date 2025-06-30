<div>
    <div class="flex flex-col">
        <div class="justify-items-center">
            <h4
                class="justify-center m-4 text-4xl font-extrabold leading-none tracking-tight text-zinc-900 md:text-5xl lg:text-3xl dark:text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r to-green-400 from-sky-400">Bosses</span>
            </h4>


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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span
                                class="ms-1 text-sm font-medium text-zinc-500 md:ms-2 dark:text-zinc-400">Bosses</span>
                        </div>
                    </li>
                </ol>
            </nav>

        </div>
    </div>

    <div class="w-full max-w-4xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 gap-4">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nome</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Hora da morte</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bosses as $boss)
                        <tr
                            class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                            <th scope="row"
                                class="px-4 py-2 font-normal text-zinc-900 whitespace-nowrap dark:text-yellow-500">
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
                    @empty
                        <tr>
                            <td colspan="3" class="p-2 text-center text-gray-500">Nenhum boss encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
