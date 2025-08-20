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
  <!-- Top bar / brand -->
  <header class="sticky top-0 z-30 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-zinc-900/60 border-b border-zinc-100 dark:border-zinc-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="/logo.png" alt="Logo" class="h-9 w-9 rounded-xl shadow-sm ring-1 ring-black/5">
        <span class="text-lg sm:text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">Kosglad 2008 • Base de Dados</span>
      </div>
      <nav class="hidden md:flex items-center gap-2 text-sm">
        @if (!request()->routeIs('home'))
            <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300">Home</a>
        @endif
        <a href="{{ route('olimpiadas') }}" class="px-3 py-1.5 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300">Olimpíadas</a>
        <a href="{{ route('heros') }}" class="px-3 py-1.5 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300">Heros</a>
        <a href="{{ route('bosses') }}" class="px-3 py-1.5 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300">Bosses</a>
        <a href="{{ route('sieges') }}" class="px-3 py-1.5 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300">Sieges</a>
      </nav>
      <div class="flex items-center gap-2" x-data>
          <button
              @click="$flux.appearance = ($flux.appearance === 'dark' ? 'light' : 'dark')"
              class="rounded-2xl border border-zinc-200 dark:border-zinc-800 p-2 bg-white/60 dark:bg-zinc-900/60 backdrop-blur"
          >
              <template x-if="$flux.appearance === 'dark'">
                  <flux:icon name="sun"></flux:icon>
              </template>
              <template x-if="$flux.appearance === 'light'">
                  <flux:icon name="moon"></flux:icon>
              </template>
          </button>
      </div>
    </div>
  </header>
<body>
    {{ $slot }}

    @fluxScripts

    

<!-- Footer -->
  <footer class="border-t border-zinc-200 dark:border-zinc-800 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-sm text-zinc-600 dark:text-zinc-400">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <p>Projeto comunitário • Sem afiliação oficial com Kosglad 2008.</p>
        <p class="opacity-80">Feito com ❤ pela comunidade.</p>
      </div>
    </div>
  </footer>


</body>
</html>
