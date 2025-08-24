<!doctype html>
<html lang="pt-BR" class="h-full">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>503 — Serviço indisponível</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: [
              'Inter',
              'ui-sans-serif',
              'system-ui',
              '-apple-system',
              'Segoe UI',
              'Roboto',
              'Ubuntu',
              'Cantarell',
              'Noto Sans',
              'sans-serif'
            ]
          }
        }
      },
      darkMode: 'media'
    };
  </script>
</head>
<body class="h-full min-h-screen bg-gradient-to-b from-sky-50 to-indigo-50 dark:from-zinc-900 dark:to-black text-zinc-800 dark:text-zinc-100 selection:bg-indigo-200/60 selection:text-black">
  <main class="relative mx-auto max-w-3xl px-6 py-16 sm:py-24 flex min-h-screen flex-col items-center justify-center text-center">
    <!-- Fundo com blobs suaves -->
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
      <div class="absolute -left-16 -top-16 h-64 w-64 rounded-full bg-indigo-400/20 blur-3xl"></div>
      <div class="absolute -right-16 -bottom-16 h-72 w-72 rounded-full bg-sky-400/20 blur-3xl"></div>
    </div>

    <div class="relative">
      <div class="mx-auto mb-6 inline-flex items-center gap-2 rounded-full border border-zinc-200/70 bg-white/70 px-3 py-1 text-xs font-medium text-zinc-700 backdrop-blur dark:border-zinc-700/60 dark:bg-zinc-800/60 dark:text-zinc-200">
        <span class="inline-flex h-2 w-2 animate-pulse rounded-full bg-amber-500"></span>
        <span>Serviço temporariamente indisponível</span>
      </div>

      <h1 class="font-display font-extrabold tracking-tight">
        {{-- <span class="block text-8xl sm:text-9xl leading-none">503</span> --}}
      </h1>
      <div class="mt-4 flex items-center justify-center gap-3">

          <img class="" src="/junao.png" alt=""> <br>
      </div>
      <div class="mt-4 flex items-center justify-center gap-3 text-lg sm:text-xl">
        {{-- <span class="inline-block animate-bounce text-3xl" role="img" aria-label="café">☕</span> --}}
        <p class="max-w-xl">Já anotei aqui o teu ban, o VAGNAO ja tomou o próximo é você!</p>
      </div>

      {{-- <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400">Também pode ser só uma manutenção flash. Nada grave — prometo. ✨</p> --}}

      {{-- <div class="mt-8 rounded-2xl border border-zinc-200/70 bg-white/80 p-6 text-left shadow-sm backdrop-blur dark:border-zinc-700/60 dark:bg-zinc-900/70">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Tentaremos automaticamente em <span id="retry-seconds" class="font-semibold text-zinc-900 dark:text-white">15</span>s…</p>
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Erro: <span class="font-mono">503</span> • ID: <span id="req-id" class="font-mono"></span></p>
          </div>
          <div class="flex flex-wrap gap-3">
            <a href="/" class="inline-flex items-center gap-2 rounded-xl border border-zinc-300/70 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:shadow dark:border-zinc-700 dark:bg-zinc-800">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                <path d="M3 12l9-9 9 9" />
                <path d="M9 21V9h6v12" />
              </svg>
              Voltar pra Home
            </a>
            <button id="retry-btn" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/70">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                <path d="M3 4v6h6" />
                <path d="M3 12a9 9 0 1 0 3-6" />
              </svg>
              Tentar agora
            </button>
          </div>
        </div>
      </div>

      <details class="mt-6 mx-auto max-w-xl text-left">
        <summary class="cursor-pointer text-sm text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200">O que fazer enquanto isso?</summary>
        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-zinc-600 dark:text-zinc-400">
          <li>Respire fundo. Aproveite um gole d'água (ou café, em solidariedade ☕).</li>
          <li>Clique em “Tentar agora” ou espere a tentativa automática.</li>
          <li>Se o problema persistir, chame o suporte com o ID acima.</li>
        </ul>
      </details> --}}

      <footer class="mt-10 text-xs text-zinc-500 dark:text-zinc-400">
        © <span id="year"></span> — Tudo sob controle. (Quase sempre.)
      </footer>
    </div>
  </main>

  <script>
    (function () {
      // ID hex aleatório para facilitar suporte
      const id = Array.from({ length: 8 }, () => Math.floor(Math.random() * 16).toString(16)).join('');
      document.getElementById('req-id').textContent = id;

      // Contagem regressiva e recarregar
      const retrySpan = document.getElementById('retry-seconds');
      const btn = document.getElementById('retry-btn');
      let seconds = parseInt(retrySpan.textContent, 10) || 15;
      const timer = setInterval(() => {
        seconds -= 1;
        retrySpan.textContent = seconds;
        if (seconds <= 0) {
          clearInterval(timer);
          location.reload();
        }
      }, 1000);
      btn.addEventListener('click', () => location.reload());

      // Ano no rodapé
      document.getElementById('year').textContent = new Date().getFullYear();
    })();
  </script>
</body>
</html>
