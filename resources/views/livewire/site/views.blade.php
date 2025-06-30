<div>
    <div class="container mx-auto p-4">
        <div class="justify-items-center">
            <h4 class="justify-center m-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-3xl dark:text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r to-green-400 from-sky-400">Estatísticas de Visitantes</span>
            </h4>
            <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                <a href="{{ route('home')}}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Home
                </a>
                </li>
                <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Views</span>
                </div>
                </li>
            </ol>
            </nav>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <!-- Card Total de Visitantes -->
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">Total de Visitantes</h2>
                <p class="text-4xl font-bold text-blue-600">{{ number_format($totalVisitors) }}</p>
                <p class="text-gray-500 mt-2">Desde o início</p>
            </div>
            
            <!-- Card Visitantes Ativos -->
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">Visitantes Ativos</h2>
                <p class="text-4xl font-bold text-green-600">{{ $activeVisitors }}</p>
                <p class="text-gray-500 mt-2">Nas últimas 24 horas</p>
            </div>
        </div>
    </div>
</div>
