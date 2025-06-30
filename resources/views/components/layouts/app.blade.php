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
</body>
</html>
