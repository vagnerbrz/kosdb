{{-- <x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar> --}}

<!DOCTYPE html>
<html lang="pt-BR">
<head>
        @include('partials.head')

    <title>KosDB</title>
</head>
<body>
    {{ $slot }}

    @fluxScripts

    

<footer class="m-4">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <span class="block text-sm text-zinc-500 sm:text-center dark:text-zinc-400"><a href="$" class="hover:underline"></a>By Infernal WarriorS // VsAll 😎 </span>
    </div>
</footer>


</body>
</html>
