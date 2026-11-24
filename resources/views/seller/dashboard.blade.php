@extends('layouts.phone')

@section('title', 'Dashboard Seller')

@php
    $terlaris = collect($menus)->max('sold') ?: 1;
@endphp

@section('content')
    <header class="bg-[linear-gradient(148deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)]">
        <x-status-bar tone="light" />
        <div class="flex items-center gap-3 px-4 pb-[18px] pt-2.5">
            <div class="min-w-0 flex-1 space-y-0.5">
                <h1 class="truncate text-[18px] font-bold text-white">Halo, {{ $seller->nama }}</h1>
                <p class="truncate text-caption text-mint">{{ $seller->email }}</p>
            </div>
            <span class="text-caption font-semibold text-mint">Online</span>
        </div>
        <div class="flex gap-2.5 px-4 pb-[18px]">
            @foreach ([['baru', 'Pesanan baru'], ['kirim_hari_ini', 'Kirim hari ini'], ['perlu_update', 'Perlu update']] as [$kunci, $label])
                <div class="flex-1 rounded-[14px] bg-white/15 px-3 py-2.5">
                    <p class="text-[20px] font-bold text-white">{{ $stats[$kunci] }}</p>
                    <p class="text-[10px] font-medium text-mint">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </header>

    <main class="flex-1 space-y-4 p-4">
        <div class="flex gap-2.5">
            <x-stat-card tone="dark" label="Omzet hari ini" :value="'Rp '.number_format($stats['omzet_hari_ini'], 0, ',', '.')" sub="Pesanan selesai" />
            <x-stat-card label="Porsi bulan ini" :value="$stats['porsi_bulan_ini']" sub="porsi" />
        </div>
        <div class="flex gap-2.5">
            <x-stat-card label="Rating toko" :value="$stats['rating'] > 0 ? number_format($stats['rating'], 1) : '-'" :sub="$stats['reviews'].' ulasan'" />
            <x-stat-card label="Perlu konfirmasi" :value="$stats['perlu_konfirmasi']" sub="pesanan" />
        </div>

        <section class="space-y-3 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <div class="flex items-center justify-between">
                <p class="text-[15px] font-bold text-ink">Grafik Penjualan</p>
                <x-badge color="green">7 hari</x-badge>
            </div>
            <div class="flex h-[130px] items-end gap-2">
                @foreach ($bars as $index => $height)
                    <div class="flex h-full flex-1 flex-col items-center justify-end gap-1">
                        <div class="min-h-1 w-full rounded-t-md {{ $index === count($bars) - 1 ? 'bg-forest' : 'bg-mint' }}" style="height: {{ max(3, (int) $height) }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex items-center justify-between text-[10px] font-medium text-muted">
                @foreach ($days as $hari)
                    <span>{{ $hari }}</span>
                @endforeach
            </div>
        </section>

        <div>
            <x-section-title title="Perlu Diproses" :href="route('seller.pesanan')" action="Lihat Semua" />
            <div class="space-y-3">
                @forelse ($orders as $order)
                    <a href="{{ route('seller.pesanan.detail', $order['id']) }}" class="block space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
                        <div class="flex items-center justify-between gap-2">
                            <p class="truncate text-[15px] font-bold text-ink">{{ $order['title'] }}</p>
                            <x-badge :color="$order['tone']" class="shrink-0">{{ $order['status'] }}</x-badge>
                        </div>
                        <p class="text-caption text-muted">{{ $order['customer'] }} · {{ $order['detail'] }}</p>
                        <div class="flex items-center justify-between">
                            <p class="text-body font-bold text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</p>
                            <span class="text-caption font-semibold text-leaf-dark">Kelola ›</span>
                        </div>
                    </a>
                @empty
                    <x-empty-state title="Tidak ada pesanan aktif">Pesanan baru akan muncul di sini.</x-empty-state>
                @endforelse
            </div>
        </div>

        <div>
            <x-section-title title="Produk Terlaris" :href="route('seller.katalog')" action="Lihat Katalog" />
            <div class="space-y-3 rounded-[18px] border border-line bg-white px-4 py-3.5">
                @forelse ($menus as $index => $menu)
                    <div class="flex items-center gap-3">
                        <span class="grid size-[30px] shrink-0 place-items-center rounded-full bg-mint-soft text-[10.8px] font-bold text-forest">{{ $index + 1 }}</span>
                        <div class="min-w-0 flex-1 space-y-[5px]">
                            <div class="flex items-center justify-between gap-2">
                                <p class="truncate text-[13px] font-semibold text-ink">{{ $menu['name'] }}</p>
                                <p class="shrink-0 text-[11px] font-medium text-muted">{{ $menu['sold'] }} terjual</p>
                            </div>
                            <x-progress-bar :value="$menu['sold'] / $terlaris * 100" height="h-1.5" />
                        </div>
                    </div>
                @empty
                    <p class="text-center text-caption text-muted">Belum ada menu.</p>
                @endforelse
            </div>
        </div>
    </main>

    <x-bottom-nav role="seller" active="seller.dashboard" />
@endsection
