@props(['value' => 50, 'tone' => 'leaf'])

@php
    $toneClass = match ($tone) {
        'amber' => 'bg-amber',
        'mint' => 'bg-mint-mid',
        'forest' => 'bg-forest',
        default => 'bg-leaf',
    };
@endphp

<div class="h-2 w-full overflow-hidden rounded-full bg-line">
    <div class="h-full rounded-full {{ $toneClass }}" style="width: {{ max(0, min(100, (int) $value)) }}%"></div>
</div>
