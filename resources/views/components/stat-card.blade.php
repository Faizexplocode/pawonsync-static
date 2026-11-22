@props(['label', 'value', 'sub' => null, 'tone' => 'light'])

@php
    $toneClasses = $tone === 'dark'
        ? 'bg-forest text-mint'
        : 'bg-white text-forest-deep ring-1 ring-line';
@endphp

<div class="rounded-2xl p-3 {{ $toneClasses }}">
    <p class="text-[9px] font-bold uppercase tracking-wide opacity-70">{{ $label }}</p>
    <p class="mt-1 text-[19px] font-extrabold leading-none">{{ $value }}</p>
    @if ($sub)
        <p class="mt-1 text-[10px] font-medium opacity-70">{{ $sub }}</p>
    @endif
</div>
