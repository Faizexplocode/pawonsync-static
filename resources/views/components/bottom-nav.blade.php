@props(['role' => 'buyer', 'active' => null])

@php
    $pengirimanAktif = $role === 'kurir'
        ? \App\Models\Pengiriman::query()
            ->where('kurir_id', auth()->id())
            ->whereIn('status', ['ditugaskan', 'berangkat', 'tiba'])
            ->latest('id')
            ->value('id')
        : null;

    $items = match ($role) {
        'seller' => [
            ['label' => 'Dashboard', 'route' => 'seller.dashboard', 'icon' => 'home'],
            ['label' => 'Pesanan', 'route' => 'seller.pesanan', 'icon' => 'receipt'],
            ['label' => 'Katalog', 'route' => 'seller.katalog', 'icon' => 'boxes'],
            ['label' => 'Keuangan', 'route' => 'seller.keuangan', 'icon' => 'wallet'],
            ['label' => 'Toko', 'route' => 'seller.pengaturan', 'icon' => 'store'],
        ],
        'kurir' => array_values(array_filter([
            ['label' => 'Beranda', 'route' => 'kurir.beranda', 'icon' => 'home'],
            ['label' => 'Tugas', 'route' => 'kurir.tugas', 'icon' => 'package'],
            $pengirimanAktif ? ['label' => 'Live', 'route' => 'kurir.live', 'icon' => 'navigation', 'params' => [$pengirimanAktif]] : null,
            $pengirimanAktif ? ['label' => 'Bukti', 'route' => 'kurir.bukti', 'icon' => 'camera', 'params' => [$pengirimanAktif]] : null,
        ])),
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
            <a href="{{ route($item['route'], $item['params'] ?? []) }}" @class([
                'flex flex-col items-center gap-[3px] px-1 py-1 text-[10px] transition',
                'font-bold text-forest' => $isActive,
                'font-medium text-muted' => ! $isActive,
            ])>
                <span @class([
                    'grid h-7 w-[46px] place-items-center rounded-xl',
                    'bg-mint text-forest' => $isActive,
                ])>
                    <x-icon :name="$item['icon']" class="size-[18px]" />
                </span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
