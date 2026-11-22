@props(['menu'])

<a href="{{ route('buyer.menu', $menu['slug']) }}" class="group overflow-hidden rounded-2xl border border-line bg-white">
    <div class="relative grid h-28 place-items-center bg-mint-soft text-[10px] font-semibold text-leaf-dark/70">
        <x-icon name="chef" class="size-7 text-leaf-dark/40" />
        @if (! empty($menu['badge']))
            <span class="absolute left-2 top-2 rounded-full bg-mint px-2 py-1 text-[9px] font-bold text-forest">{{ $menu['badge'] }}</span>
        @endif
    </div>
    <div class="p-3">
        <p class="line-clamp-1 text-[13px] font-bold text-ink">{{ $menu['name'] }}</p>
        <div class="mt-1 flex items-center gap-1 text-[11px] text-muted">
            <span class="text-amber">★</span>
            <span class="font-semibold text-ink">{{ $menu['rating'] }}</span>
            <span>({{ $menu['reviews'] }})</span>
        </div>
        <p class="mt-1 text-[13px] font-extrabold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
        <p class="mt-0.5 line-clamp-1 text-[10px] text-muted">{{ $menu['seller'] }} · {{ $menu['distance'] }}</p>
    </div>
</a>
