@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'pill' => false])

@php
    $classes = match (true) {
        $pill && $variant === 'secondary' => 'rounded-full bg-mint-soft text-forest',
        $pill && $variant === 'outline' => 'rounded-full bg-white text-ink shadow-soft',
        $pill && $variant === 'danger' => 'rounded-full bg-danger-soft text-danger',
        $pill => 'rounded-full bg-forest text-white',
        $variant === 'secondary' => 'rounded-[14px] bg-mint text-forest',
        $variant === 'outline' => 'rounded-[14px] border-[1.5px] border-forest bg-white text-forest',
        $variant === 'danger' => 'rounded-[14px] border-[1.5px] border-danger bg-white text-danger',
        default => 'rounded-[14px] bg-[linear-gradient(144deg,var(--color-forest-deep)_0%,var(--color-leaf-dark)_71.4%)] text-white',
    };
    $dasar = $pill ? 'text-[14px] transition active:scale-[0.98]' : 'text-[15px]';
    $classes = "flex w-full items-center justify-center gap-2.5 px-[18px] py-3.5 font-semibold disabled:cursor-not-allowed disabled:border-line disabled:bg-disabled disabled:bg-none disabled:text-disabled-text {$dasar} {$classes}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
