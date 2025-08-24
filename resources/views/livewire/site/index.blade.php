<div class="min-h-screen bg-gradient-to-b from-white via-white to-zinc-50 dark:from-zinc-900 dark:via-zinc-900 dark:to-black">


  <!-- Hero -->
  <section class="relative overflow-hidden">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
      <div class="absolute -top-20 left-1/2 -translate-x-1/2 h-[36rem] w-[36rem] rounded-full blur-3xl opacity-30 bg-gradient-to-br from-sky-400 to-emerald-400 dark:opacity-20"></div>
      <div class="absolute bottom-0 right-0 h-64 w-64 bg-[radial-gradient(ellipse_at_bottom_right,rgba(16,185,129,0.15),transparent_60%)]"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
      <div class="grid lg:grid-cols-12 gap-10 lg:gap-14 items-center">
        <div class="lg:col-span-7">
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-emerald-500">Banco de dados Kosglad 2008</span>
          </h1>
          <p class="mt-4 text-base sm:text-lg text-zinc-700 dark:text-zinc-300 max-w-prose">
            Informações oficiais do servidor, atualizadas e organizadas para a comunidade. Acompanhe rankings, heróis, bosses e sieges com uma experiência leve e moderna.

            Se desbanir eu tiro do ar, foda-se.
          </p>

          <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('olimpiadas') }}" class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-white shadow hover:shadow-md hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 transition">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M3 3v18h18"/><path d="m19 9-6 6-4-4-3 3"/></svg>
              Ver Ranking das Olimpíadas
            </a>
            <a href="{{ route('bosses') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-zinc-300 text-zinc-800 hover:bg-zinc-100 dark:text-zinc-200 dark:ring-zinc-700 dark:hover:bg-zinc-800 transition">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M3 12h18"/><path d="M12 3v18"/></svg>
              Bosses & Spawns
            </a>
          </div>

          <!-- Quick stats -->
          <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-xl">
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 bg-white/60 dark:bg-zinc-900/60 backdrop-blur">
              <p class="text-xs uppercase tracking-wide text-zinc-500">Atualização</p>
              <p class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Tempo real*</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 bg-white/60 dark:bg-zinc-900/60 backdrop-blur">
              <p class="text-xs uppercase tracking-wide text-zinc-500">Cobertura</p>
              <p class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Olimpíadas, Heros, Bosses, Sieges</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 bg-white/60 dark:bg-zinc-900/60 backdrop-blur">
              <p class="text-xs uppercase tracking-wide text-zinc-500">Comunidade</p>
              <p class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100">100% gratuito</p>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5">
          <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white/70 dark:bg-zinc-900/70 backdrop-blur p-5 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-sky-500 to-emerald-500"></div>
              <div>
                <p class="text-sm text-zinc-500">Busca rápida</p>
                <p class="text-sm text-zinc-400">(em breve)</p>
              </div>
            </div>
            <div class="mt-4">
              <input disabled placeholder="Pesquisar jogadores, clãs, bosses…" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-3 text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-sky-400 disabled:opacity-60" />
            </div>
            <p class="mt-3 text-xs text-zinc-500">* Quando disponível pelos endpoints oficiais do servidor.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Feature / Links grid -->
  <section class="py-8 sm:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <!-- Card: Olimpíadas -->
        <a href="{{ route('olimpiadas') }}" class="group relative rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 hover:shadow-md transition">
          <div class="flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:scale-105 transition">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-zinc-700 dark:text-zinc-300"><circle cx="6" cy="12" r="3"/><circle cx="12" cy="12" r="3"/><circle cx="18" cy="12" r="3"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Ranking das Olimpíadas</h3>
              <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Pontuação em tempo real por classe e participante.</p>
            </div>
          </div>
        </a>

        <!-- Card: Heros -->
        <a href="{{ route('heros') }}" class="group relative rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 hover:shadow-md transition">
          <div class="flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:scale-105 transition">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-zinc-700 dark:text-zinc-300"><path d="M12 2 7 7l5 5 5-5-5-5Z"/><path d="M7 7v10l5 5 5-5V7"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Heros</h3>
              <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Classes, clãs, status e habilidades dos campeões.</p>
            </div>
          </div>
        </a>

        <!-- Card: Bosses -->
        <a href="{{ route('bosses') }}" class="group relative rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 hover:shadow-md transition">
          <div class="absolute top-4 right-4 text-[10px] font-medium px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Novos filtros</div>
          <div class="flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:scale-105 transition">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-zinc-700 dark:text-zinc-300"><path d="M12 3l2.5 6.5L21 12l-6.5 2.5L12 21l-2.5-6.5L3 12l6.5-2.5L12 3Z"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Bosses</h3>
              <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Status, janelas de spawn e alertas.</p>
              <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">Em breve recursos extras</p>
            </div>
          </div>
        </a>

        <!-- Card: Sieges -->
        <a href="{{ route('sieges') }}" class="group relative rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 hover:shadow-md transition">
          <div class="flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:scale-105 transition">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-zinc-700 dark:text-zinc-300"><path d="M4 21V3h16v18"/><path d="M4 7h16"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Sieges</h3>
              <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Próximos eventos e estratégias.</p>
            </div>
          </div>
        </a>

        <!-- Card: Top Enchant -->
        <div class="group relative rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
          <div class="absolute top-4 right-4 text-[10px] font-medium px-2 py-1 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Em construção</div>
          <div class="flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-zinc-700 dark:text-zinc-300"><path d="M12 2v20"/><path d="M4 12h16"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Top Enchant</h3>
              <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Ranking de armas mais encantadas.</p>
            </div>
          </div>
        </div>

        <!-- Card: Outros Rankings -->
        <div class="group relative rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
          <div class="absolute top-4 right-4 text-[10px] font-medium px-2 py-1 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Em construção</div>
          <div class="flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-zinc-700 dark:text-zinc-300"><path d="M3 3h18v4H3z"/><path d="M7 7v14"/><path d="M17 7v14"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Demais Rankings</h3>
              <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">PvP, PK, clãs, tempo online e mais.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  
</div>
