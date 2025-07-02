{{-- <x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar> --}}

<!DOCTYPE html>
<html lang="pt-BR">
<head>
        @include('partials.head')
</head>
<body>
    {{ $slot }}

    @fluxScripts

    

<footer class="m-4 ">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <span class="block text-sm text-zinc-500 sm:text-center dark:text-zinc-400">
            By Infernal WarriorS // VsALL 
            <a href="{{ route('views')}}"  class="hover:underline text-red-600"> ♡
            </a> 
        </span>
        <div class="w-full text-sm/6 text-center text-zinc-700 font-light mt-4">Feito por <br> <span class="italic font-bold text-zinc-600">VAGNAO</span> </div>
    </div>
</footer>


</body>
</html>
