@props(['title', 'subtitle' => null, 'back' => null])

<header class="sticky top-0 z-20 border-b border-line/70 bg-mist/90 backdrop-blur-md">
    <x-status-bar />
    <div class="flex items-center gap-2 px-4 pb-3 pt-1">
        @if ($back)
            <a href="{{ $back }}" class="grid size-9 shrink-0 place-items-center rounded-full bg-white text-forest-deep shadow-sm ring-1 ring-line" aria-label="Kembali">
                <x-icon name="chevron-left" class="size-5" />
            </a>
        @endif
        <div class="min-w-0 flex-1">
            <h1 class="truncate text-[17px] font-extrabold leading-tight tracking-tight text-forest-deep">{{ $title }}</h1>
            @if ($subtitle)
                <p class="truncate text-[11px] font-medium text-muted">{{ $subtitle }}</p>
            @endif
        </div>
        @isset($right)
            <div class="flex shrink-0 items-center gap-1">{{ $right }}</div>
        @endisset
    </div>
</header>
