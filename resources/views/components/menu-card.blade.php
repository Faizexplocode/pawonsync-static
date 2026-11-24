@props(['menu'])

<a href="{{ route('buyer.menu', $menu['id']) }}" class="group self-start overflow-hidden rounded-card border border-line bg-white">
    <div class="grid h-[104px] place-items-center bg-linear-[141deg] from-mint-soft from-0% to-mint to-70% text-[11px] font-medium text-leaf-dark">
        @if (! empty($menu['photo']))
            <img src="{{ $menu['photo'] }}" alt="{{ $menu['name'] }}" class="size-full object-cover" />
        @else
            Foto menu
        @endif
    </div>
    <div class="space-y-1 px-3 py-2.5">
        <p class="line-clamp-2 text-[13px] font-semibold text-ink">{{ $menu['name'] }}</p>
        <div class="flex items-center gap-1 text-caption">
            <span class="text-[13px] font-bold text-amber">★</span>
            <span class="font-semibold text-ink">{{ $menu['rating'] > 0 ? number_format($menu['rating'], 1) : '-' }}</span>
            <span class="text-muted">({{ $menu['reviews'] }})</span>
        </div>
        <p class="text-body font-bold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
        <p class="line-clamp-1 text-[11px] text-muted">{{ $menu['seller'] }}</p>
    </div>
</a>
