@extends('layouts.phone')

@section('title', 'Beranda Buyer')

@php
    $buyer = config('demo.buyer');
    $categories = config('demo.categories');
    $menus = config('demo.menus');
    $order = config('demo.orders')[0];
    $flash = $menus[5];
@endphp

@section('content')
    <x-app-bar title="Halo, {{ $buyer['name'] }}" subtitle="Kirim ke: {{ $buyer['address'] }}">
        <x-slot:right>
            <a href="{{ route('buyer.notifikasi') }}" class="relative grid size-9 place-items-center rounded-full bg-white text-forest-deep ring-1 ring-line">
                <x-icon name="bell" class="size-5" />
                <span class="absolute -right-0.5 -top-0.5 grid size-4 place-items-center rounded-full bg-amber text-[8px] font-bold text-white">{{ $buyer['notifications'] }}</span>
            </a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-5 px-4 py-4">
        <div class="flex gap-2">
            <a href="{{ route('buyer.katalog') }}" class="flex flex-1 items-center gap-2 rounded-2xl border border-line bg-white px-4 py-3 text-[12px] font-medium text-muted">
                <x-icon name="search" class="size-4 text-muted" />
                Cari nasi box, prasmanan, tumpeng...
            </a>
            <a href="{{ route('buyer.filter') }}" class="inline-flex items-center gap-1.5 rounded-2xl bg-forest px-3.5 py-3 text-[12px] font-bold text-white">
                <x-icon name="filter" class="size-4" />
                Filter
            </a>
        </div>

        <section class="relative overflow-hidden rounded-3xl bg-forest p-4 text-white">
            <div class="pointer-events-none absolute -right-8 -top-10 size-36 rounded-full bg-leaf/50 blur-xl"></div>
            <x-badge color="mint">PROMO WISUDA</x-badge>
            <p class="mt-3 text-[19px] font-extrabold leading-tight">Diskon 20% paket 100+ porsi</p>
            <p class="mt-1 text-[11px] font-medium text-mint/80">Berlaku sampai 31 Okt 2026 · Voucher WISUDA20</p>
            <a href="{{ route('buyer.katalog') }}" class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-mint px-3.5 py-2 text-[11px] font-bold text-forest">
                Lihat promo
                <x-icon name="arrow-right" class="size-3.5" />
            </a>
        </section>

        <section>
            <x-section-title title="Kategori" :href="route('buyer.katalog')" action="Semua" />
            <div class="grid grid-cols-5 gap-2">
                @foreach ($categories as $category)
                    <a href="{{ route('buyer.katalog') }}" class="flex flex-col items-center gap-1.5">
                        <span class="grid size-12 place-items-center rounded-2xl bg-mint-soft text-[12px] font-extrabold text-leaf-dark">{{ $category['code'] }}</span>
                        <span class="text-center text-[9px] font-semibold text-muted">{{ $category['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section>
            <x-section-title title="Pesanan Aktif" :href="route('buyer.pesanan')" action="Lihat Semua" />
            <a href="{{ route('buyer.pesanan.detail', $order['id']) }}" class="block rounded-2xl border border-line bg-white p-4">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[14px] font-extrabold text-forest-deep">{{ $order['title'] }}</p>
                    <x-badge color="green">{{ $order['status'] }}</x-badge>
                </div>
                <p class="mt-1 text-[10px] text-muted">{{ $order['meta'] }}</p>
                <div class="mt-3">
                    <x-progress-bar :value="$order['progress'] / $order['steps'] * 100" />
                </div>
                <div class="mt-2 flex items-center justify-between text-[10px] font-semibold text-muted">
                    <span>{{ $order['progress'] }} dari {{ $order['steps'] }} tahap selesai</span>
                    <span class="text-leaf">{{ $order['eta'] }}</span>
                </div>
            </a>
        </section>

        <section>
            <x-section-title title="Rekomendasi untuk Anda" :href="route('buyer.katalog')" action="Lihat Semua" />
            <div class="grid grid-cols-2 gap-3">
                @foreach (array_slice($menus, 0, 4) as $menu)
                    <x-menu-card :menu="$menu" />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-title title="Flash Sale Hari Ini" />
            <div class="mb-2 flex items-center justify-between text-[10px] font-semibold">
                <span class="text-amber">Berakhir 02:14:09</span>
                <span class="text-muted">Sisa 18 paket</span>
            </div>
            <a href="{{ route('buyer.menu', $flash['slug']) }}" class="flex items-center gap-3 rounded-2xl border border-line bg-white p-3">
                <x-photo label="Foto" class="size-16 shrink-0" />
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[12px] font-bold text-ink">{{ $flash['name'] }}</p>
                    <p class="mt-1 text-[11px] font-semibold text-forest">
                        Rp {{ number_format($flash['price'], 0, ',', '.') }}
                        <span class="ml-1 text-[10px] font-medium text-muted line-through">Rp {{ number_format($flash['old_price'], 0, ',', '.') }}</span>
                    </p>
                    <div class="mt-2"><x-progress-bar :value="72" tone="amber" /></div>
                    <p class="mt-1 text-[9px] font-semibold text-amber">72% terjual</p>
                </div>
            </a>
        </section>
    </main>

    <x-bottom-nav role="buyer" active="buyer.beranda" />
@endsection
