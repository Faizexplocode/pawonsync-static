@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])

@php
    $classes = match ($variant) {
        'secondary' => 'bg-mint text-forest',
        'outline' => 'border-[1.5px] border-forest bg-white text-forest',
        'danger' => 'border-[1.5px] border-danger bg-white text-danger',
        default => 'bg-[linear-gradient(144deg,var(--color-forest-deep)_0%,var(--color-leaf-dark)_71.4%)] text-white',
    };
    $classes = "flex w-full items-center justify-center gap-2.5 rounded-[14px] px-[18px] py-3.5 text-[15px] font-semibold disabled:cursor-not-allowed disabled:border-line disabled:bg-disabled disabled:bg-none disabled:text-disabled-text {$classes}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
