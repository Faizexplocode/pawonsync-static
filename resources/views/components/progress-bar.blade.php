@props(['value' => 50, 'tone' => 'leaf', 'height' => 'h-2'])

@php
    $toneClass = match ($tone) {
        'amber' => 'bg-amber',
        'mint' => 'bg-mint-mid',
        'forest' => 'bg-forest',
        default => 'bg-linear-to-r from-leaf to-leaf-light',
    };
@endphp

<div class="{{ $height }} w-full overflow-hidden rounded-full bg-mint-soft">
    <div class="h-full rounded-full {{ $toneClass }}" style="width: {{ max(0, min(100, (int) $value)) }}%"></div>
</div>
