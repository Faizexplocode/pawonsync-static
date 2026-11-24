@props(['label' => 'Foto', 'src' => null])

<div {{ $attributes->merge(['class' => 'relative grid place-items-center overflow-hidden rounded-xl bg-linear-[127deg] from-mint-soft from-0% to-mint to-70% text-[11px] font-medium text-leaf-dark']) }}>
    @if (! empty($src))
        <img src="{{ $src }}" alt="{{ $label }}" loading="lazy" class="absolute inset-0 z-10 size-full object-cover" onerror="this.remove()" />
    @endif
    <span class="px-1 text-center">{{ $label }}</span>
</div>
