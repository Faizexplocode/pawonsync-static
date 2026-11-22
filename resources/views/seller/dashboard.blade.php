@extends('layouts.phone')

@section('title', 'Dashboard Seller')

@php
    $seller = config('demo.seller');
    $orders = config('demo.seller_orders');
    $menus = config('demo.menus');
    $bars = [42, 68, 51, 82, 61, 93, 72];
    $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
@endphp

@section('content')
    <x-app-bar title="Halo, {{ $seller['name'] }}" subtitle="{{ $seller['store'] }} · {{ $seller['status'] }}">
        <x-slot:right>
            <span class="inline-flex items-center gap-1 rounded-full bg-mint px-2.5 py-1.5 text-[10px] font-bold text-forest">
                <span class="size-1.5 rounded-full bg-leaf"></span>
                Online
            </span>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <div class="grid grid-cols-2 gap-2">
            <x-stat-card label="Pesanan baru" value="5" sub="2 perlu konfirmasi" />
            <x-stat-card label="Kirim hari ini" value="2" sub="1 sudah di-packing" />
            <x-stat-card label="Perlu update" value="3" sub="Milestone tertunda" tone="dark" />
            <x-stat-card label="Omzet hari ini" value="Rp 4,2 jt" sub="+12% vs kemarin" />
        </div>

        <div class="grid grid-cols-3 gap-2">
            <x-stat-card label="Porsi diproduksi" value="230" sub="77% kapasitas" />
            <x-stat-card label="Rating toko" value="4.9" sub="318 ulasan" />
            <x-stat-card label="Tepat waktu" value="96%" sub="30 hari" />
        </div>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Grafik penjualan</p>
                <span class="text-[10px] font-bold text-leaf">+18%</span>
            </div>
            <div class="mt-1 flex items-baseline gap-2">
                <p class="text-[20px] font-extrabold text-forest">Rp 28,4 jt</p>
                <span class="text-[9px] text-muted">minggu ini</span>
            </div>
            <div class="mt-4 flex h-28 items-end gap-2">
                @foreach ($bars as $index => $height)
                    <div class="flex flex-1 flex-col items-center gap-1">
                        <div class="w-full rounded-t-md {{ $index === 5 ? 'bg-forest' : 'bg-mint' }}" style="height: {{ $height }}%"></div>
                        <span class="text-[8px] font-semibold text-muted">{{ $days[$index] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-2 grid grid-cols-3 gap-1 rounded-xl bg-mist p-1 text-center text-[9px] font-bold">
                <span class="rounded-lg bg-white py-1 text-forest-deep">Harian</span>
                <span class="py-1 text-muted">Mingguan</span>
                <span class="py-1 text-muted">Bulanan</span>
            </div>
        </section>

        <section>
            <x-section-title title="Perlu Diproses" :href="route('seller.pesanan')" action="Lihat Semua" />
            <div class="space-y-2">
                @foreach ($orders as $order)
                    <a href="{{ route('seller.pesanan.detail', $order['id']) }}" class="block rounded-2xl border border-line bg-white p-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-[12px] font-bold text-forest-deep">{{ $order['title'] }}</p>
                                <p class="text-[9px] text-muted">{{ $order['customer'] }} · {{ $order['detail'] }}</p>
                            </div>
                            <x-badge :color="$order['tone']">{{ $order['status'] }}</x-badge>
                        </div>
                        <div class="mt-2 flex items-center justify-between border-t border-line pt-2">
                            <span class="text-[12px] font-extrabold text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                            <div class="flex gap-1.5">
                                <span class="rounded-full border border-line px-3 py-1 text-[9px] font-bold text-muted">Tolak</span>
                                <span class="rounded-full bg-forest px-3 py-1 text-[9px] font-bold text-white">Terima</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section>
            <x-section-title title="Produk Terlaris" :href="route('seller.katalog')" action="Lihat Katalog" />
            <div class="divide-y divide-line rounded-2xl border border-line bg-white">
                @foreach (array_slice($menus, 0, 3) as $index => $menu)
                    <div class="flex items-center gap-3 p-3">
                        <span class="grid size-6 place-items-center rounded-lg bg-mint-soft text-[10px] font-extrabold text-forest">{{ $index + 1 }}</span>
                        <x-photo label="Foto" class="size-10 shrink-0" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[11px] font-bold text-ink">{{ $menu['name'] }}</p>
                            <p class="text-[9px] text-muted">{{ $menu['sold'] }} terjual</p>
                        </div>
                        <span class="text-[10px] font-bold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <a href="{{ route('seller.kurir') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
            <x-icon name="truck" class="size-4" />
            Buka Departure Gate
        </a>
    </main>

    <x-bottom-nav role="seller" active="seller.dashboard" />
@endsection
