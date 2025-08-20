@props([
    // Ex.: [
    //   ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
    //   ['label' => 'Olimpíadas', 'url' => route('olimpiadas')],
    //   ['label' => 'Classe 103', 'url' => null], // item atual sem link
    // ]
    'items' => [],
])

<nav aria-label="Breadcrumb"
     class="sticky top-0 z-20">
  <div class="mx-auto max-w-7xl px-3">
    <!-- Barra rolável no mobile -->
    <ol class="flex items-center gap-1 py-2 overflow-x-auto no-scrollbar"
        itemscope itemtype="https://schema.org/BreadcrumbList">
      @foreach ($items as $index => $item)
        @php
          $isLast = $index === count($items) - 1;
          $icon = $item['icon'] ?? null;
        @endphp

        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
            class="shrink-0 flex items-center">
          @if (!$isLast && !empty($item['url']))
            <a href="{{ $item['url'] }}"
               itemprop="item"
               class="inline-flex items-center gap-1.5 rounded-lg px-2 py-1.5 text-sm
                      text-zinc-700 hover:text-sky-600 dark:text-zinc-300 dark:hover:text-sky-400">
              @if ($icon === 'home')
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2A1 1 0 1 0 2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293A1 1 0 1 0 19.707 9.293Z"/>
                </svg>
              @endif
              <span itemprop="name">{{ $item['label'] }}</span>
              <meta itemprop="position" content="{{ $index + 1 }}">
            </a>
          @else
            <span itemprop="name"
                  class="inline-flex items-center gap-1.5 rounded-lg px-2 py-1.5 text-sm
                         text-zinc-500 dark:text-zinc-400">
              @if ($icon === 'home')
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2A1 1 0 1 0 2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293A1 1 0 1 0 19.707 9.293Z"/>
                </svg>
              @endif
              {{ $item['label'] }}
              <meta itemprop="position" content="{{ $index + 1 }}">
            </span>
          @endif
        </li>

        @if (!$isLast)
          <li class="shrink-0 text-zinc-400 dark:text-zinc-600" aria-hidden="true">
            <svg class="h-4 w-4" viewBox="0 0 6 10" fill="none">
              <path d="m1 9 4-4-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </li>
        @endif
      @endforeach
    </ol>
  </div>
</nav>

<style>
  /* oculta scrollbars no mobile */
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
