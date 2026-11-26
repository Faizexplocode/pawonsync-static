@props(['menu'])

<a href="{{ route('buyer.menu', $menu['id']) }}" class="surface block self-start p-2 transition active:scale-[0.98]">
    <div class="relative grid h-[112px] place-items-center overflow-hidden rounded-[18px] bg-linear-[141deg] from-mint-soft from-0% to-mint to-70% text-[11px] font-medium text-leaf-dark">
        @if (! empty($menu['photo']))
            <img src="{{ $menu['photo'] }}" alt="{{ $menu['name'] }}" loading="lazy" class="absolute inset-0 size-full object-cover" />
        @else
            Foto menu
        @endif
        <span class="absolute left-2 top-2 inline-flex items-center gap-1 rounded-full bg-white/95 px-2 py-1 text-[11px] font-semibold text-ink">
            <span class="text-amber">★</span>
            {{ $menu['rating'] > 0 ? number_format($menu['rating'], 1) : '-' }}
            <span class="font-medium text-muted">({{ $menu['reviews'] }})</span>
        </span>
    </div>
    <div class="space-y-1 px-1.5 pb-1.5 pt-2.5">
        <p class="line-clamp-2 text-[13px] font-semibold leading-snug text-ink">{{ $menu['name'] }}</p>
        <p class="line-clamp-1 text-[11px] text-muted">{{ $menu['seller'] }}</p>
        <p class="tabular text-body font-semibold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
    </div>
</a>
