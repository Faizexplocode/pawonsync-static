@props(['role' => 'buyer', 'active' => null])

@php
    $items = match ($role) {
        'seller' => [
            ['label' => 'Dashboard', 'route' => 'seller.dashboard', 'icon' => 'home'],
            ['label' => 'Pesanan', 'route' => 'seller.pesanan', 'icon' => 'receipt'],
            ['label' => 'Katalog', 'route' => 'seller.katalog', 'icon' => 'boxes'],
            ['label' => 'Keuangan', 'route' => 'seller.keuangan', 'icon' => 'wallet'],
            ['label' => 'Toko', 'route' => 'seller.pengaturan', 'icon' => 'store'],
        ],
        'kurir' => [
            ['label' => 'Beranda', 'route' => 'kurir.beranda', 'icon' => 'home'],
            ['label' => 'Tugas', 'route' => 'kurir.tugas', 'icon' => 'package'],
            ['label' => 'Live', 'route' => 'kurir.live', 'icon' => 'navigation'],
            ['label' => 'Bukti', 'route' => 'kurir.bukti', 'icon' => 'camera'],
        ],
        default => [
            ['label' => 'Beranda', 'route' => 'buyer.beranda', 'icon' => 'home'],
            ['label' => 'Katalog', 'route' => 'buyer.katalog', 'icon' => 'search'],
            ['label' => 'Pesanan', 'route' => 'buyer.pesanan', 'icon' => 'receipt'],
            ['label' => 'Notifikasi', 'route' => 'buyer.notifikasi', 'icon' => 'bell'],
            ['label' => 'Profil', 'route' => 'buyer.profil', 'icon' => 'user'],
        ],
    };
@endphp

<nav class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white/95 px-2 pb-[max(0.5rem,env(safe-area-inset-bottom))] pt-2 backdrop-blur-md">
    <div class="grid" style="grid-template-columns: repeat({{ count($items) }}, minmax(0, 1fr))">
        @foreach ($items as $item)
            @php($isActive = $active === $item['route'] || request()->routeIs($item['route']))
            <a href="{{ route($item['route']) }}" @class([
                'flex flex-col items-center gap-1 rounded-xl px-1 py-1 text-[10px] font-semibold transition',
                'text-leaf' => $isActive,
                'text-muted' => ! $isActive,
            ])>
                <span @class([
                    'grid size-9 place-items-center rounded-xl',
                    'bg-mint-soft text-forest' => $isActive,
                ])>
                    <x-icon :name="$item['icon']" class="size-[19px]" />
                </span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
