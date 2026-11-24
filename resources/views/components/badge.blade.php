@props(['color' => 'green'])

@php
    $classes = match ($color) {
        'mint' => 'bg-mint text-forest-deep',
        'amber' => 'bg-amber-soft text-amber',
        'red' => 'bg-danger-soft text-danger',
        'dark' => 'bg-forest text-white',
        'info' => 'bg-info-soft text-info',
        'gray' => 'bg-line text-muted',
        default => 'bg-mint-soft text-forest',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold leading-none {$classes}"]) }}>{{ $slot }}</span>
