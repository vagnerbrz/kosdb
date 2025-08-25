<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>503 — Em Manutenção</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .rainbow-disc {
      position: fixed;
      inset: 0;
      display: grid;
      place-items: center;
      overflow: hidden;
      background: #000;
    }
    .rainbow-disc::before {
      content: "";
      position: absolute;
      width: 160vmax;
      height: 160vmax;
      border-radius: 50%;
      background: conic-gradient(
        red, orange, yellow, lime, cyan, blue, indigo, violet, red
      );
      /* 🔥 aqui está a rotação */
      animation: spin 5s linear infinite;
      will-change: transform;
    }
    .veil {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.25);
      backdrop-filter: blur(4px);
    }
    @keyframes spin {
      from { transform: rotate(0deg); }
      to   { transform: rotate(360deg); }
    }

  </style>
</head>
<body class="h-screen w-screen text-white">
  <div class="rainbow-disc"></div>
  <div class="veil"></div>

  <main class="relative z-10 h-full w-full flex items-center justify-center p-6">
  <div class="text-center p-8 bg-white/70 dark:bg-black/70 rounded-2xl shadow-2xl backdrop-blur">
    <img src="/junao2.png" alt="" class="mx-auto drop-shadow-lg w-40 h-40 object-contain">
    
    <p class="mt-4 text-2xl font-semibold text-gray-800 dark:text-gray-200">
      🌈Você foi banido!🌈
    </p>
    
    <p class="mt-2 text-gray-600 dark:text-gray-400">
      Vokc sabe qeu nao pdoe usar esse siet porq vc vai ter vantage ai vai etsta inflindo a rega do jogu po rets motivo vc esta baniudo.
    </p>
  </div>
</main>

</body>
</html>
