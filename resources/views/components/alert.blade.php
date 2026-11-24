@props(['tone' => 'success', 'title' => null])

@php
    [$box, $dot, $heading, $glyph] = match ($tone) {
        'error' => ['bg-danger-soft', 'bg-danger', 'text-danger', '✕'],
        'warning' => ['bg-amber-soft', 'bg-amber', 'text-amber', '!'],
        'info' => ['bg-info-soft', 'bg-info', 'text-info', 'i'],
        default => ['bg-mint-soft', 'bg-leaf', 'text-forest', '✓'],
    };
@endphp

<div {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-[14px] px-3.5 py-3 {$box}"]) }}>
    <span class="grid size-6 shrink-0 place-items-center rounded-xl text-[12px] font-bold text-white {{ $dot }}">{{ $glyph }}</span>
    <div class="min-w-0 flex-1 space-y-0.5">
        @if ($title)
            <p class="text-[13px] font-bold {{ $heading }}">{{ $title }}</p>
        @endif
        <p class="text-caption text-ink">{{ $slot }}</p>
    </div>
</div>
