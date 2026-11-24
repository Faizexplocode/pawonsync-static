@props(['label', 'value', 'sub' => null, 'tone' => 'light'])

@php
    $dark = $tone === 'dark';
@endphp

<div {{ $attributes->merge(['class' => 'flex-1 space-y-0.5 rounded-2xl px-3.5 py-3 '.($dark ? 'bg-[linear-gradient(149deg,var(--color-forest-deep)_0%,var(--color-leaf-dark)_71.4%)]' : 'border border-line bg-white')]) }}>
    <p @class(['text-[11px] font-medium', 'text-mint' => $dark, 'text-muted' => ! $dark])>{{ $label }}</p>
    <p @class(['text-[18px] font-bold', 'text-white' => $dark, 'text-ink' => ! $dark])>{{ $value }}</p>
    @if ($sub)
        <p @class(['text-[11px] font-semibold', 'text-mint-mid' => $dark, 'text-leaf' => ! $dark])>{{ $sub }}</p>
    @endif
</div>
