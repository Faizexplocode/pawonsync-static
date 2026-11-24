@props(['title', 'subtitle' => null, 'back' => null])

<header class="sticky top-0 z-20 bg-white">
    <x-status-bar />
    <div class="flex items-center gap-3 px-4 py-2.5">
        @if ($back)
            <a href="{{ $back }}" class="shrink-0 px-0.5 text-[28px] font-medium leading-none text-ink" aria-label="Kembali">‹</a>
        @endif
        <div class="min-w-0 flex-1 space-y-0.5">
            <h1 class="truncate text-[18px] font-bold text-ink">{{ $title }}</h1>
            @if ($subtitle)
                <p class="truncate text-caption text-muted">{{ $subtitle }}</p>
            @endif
        </div>
        @isset($right)
            <div class="flex shrink-0 items-center gap-1">{{ $right }}</div>
        @endisset
    </div>
</header>
