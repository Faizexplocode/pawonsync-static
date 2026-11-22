@props(['color' => 'green'])

@php
    $classes = match ($color) {
        'mint' => 'bg-mint-soft text-leaf-dark',
        'amber' => 'bg-amber-soft text-amber',
        'dark' => 'bg-forest-deep text-mint',
        'gray' => 'bg-line text-muted',
        default => 'bg-mint text-forest',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2 py-1 text-[10px] font-bold leading-none {$classes}"]) }}>{{ $slot }}</span>
