@extends('layouts.phone')

@section('title', 'Beranda Buyer')

@php
    $ikonKategori = ['NB' => 'package', 'PR' => 'chef', 'SB' => 'gift', 'TP' => 'tumpeng', 'DS' => 'star'];
    $fotoUtama = collect($menus)->pluck('photo')->filter()->first() ?? '/images/menu/tumpeng.jpg';
@endphp

@section('content')
    <div class="flex-1 bg-cream">
        <header class="flex items-center gap-3 px-5 pb-2 pt-5">
            <a href="{{ route('buyer.profil') }}" class="grid size-11 shrink-0 place-items-center rounded-full bg-mint-soft text-[15px] font-semibold text-forest" aria-label="Profil saya">{{ strtoupper(substr($buyer->nama, 0, 2)) }}</a>
            <div class="min-w-0 flex-1">
                <h1 class="truncate text-[17px] font-semibold text-ink">Halo, {{ $buyer->nama }}</h1>
                <p class="truncate text-[12px] text-muted">{{ $alamat ? 'Kirim ke: '.$alamat : 'Pesan katering untuk acara Anda' }}</p>
            </div>
            <a href="{{ route('buyer.keranjang') }}" class="grid size-10 shrink-0 place-items-center rounded-full bg-white text-forest shadow-soft" aria-label="Keranjang">
                <x-icon name="cart" class="size-[18px]" />
            </a>
            <a href="{{ route('buyer.notifikasi') }}" class="relative grid size-10 shrink-0 place-items-center rounded-full bg-white text-forest shadow-soft" aria-label="{{ $notif }} notif">
                <x-icon name="bell" class="size-[18px]" />
                @if ($notif > 0)
                    <span class="absolute right-2 top-2 size-2 rounded-full bg-amber ring-2 ring-white"></span>
                @endif
            </a>
        </header>

        <main class="stagger space-y-6 px-5 pb-6 pt-3">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('buyer.katalog') }}" class="flex h-12 min-w-0 flex-1 items-center gap-2.5 rounded-full bg-white px-4 text-[13px] text-muted shadow-soft">
                    <x-icon name="search" class="size-4 shrink-0" />
                    <span class="truncate">Cari nasi box, prasmanan, tumpeng...</span>
                </a>
                <a href="{{ route('buyer.filter') }}" class="grid size-12 shrink-0 place-items-center rounded-full bg-forest text-white" aria-label="Filter">
                    <x-icon name="sliders" class="size-5" />
                </a>
            </div>

            <section class="relative overflow-hidden rounded-[28px] bg-[linear-gradient(140deg,var(--color-forest-night)_0%,var(--color-forest)_55%,var(--color-leaf)_100%)] p-5 text-white">
                <div class="pointer-events-none absolute -right-10 -top-10 size-44 rounded-full bg-white/5"></div>
                <div class="relative flex items-center gap-3">
                    <div class="min-w-0 flex-1 space-y-1.5">
                        <x-badge color="mint">{{ $promo['badge'] }}</x-badge>
                        <p class="text-[19px] font-semibold leading-snug">{{ $promo['title'] }}</p>
                        <p class="text-[12px] text-mint/80">{{ $promo['note'] }}</p>
                    </div>
                    <div class="relative size-[104px] shrink-0">
                        <span class="absolute inset-2 rounded-full bg-mint/30 animate-ripple"></span>
                        <img src="{{ $fotoUtama }}" alt="" aria-hidden="true" class="relative size-full rounded-full object-cover ring-4 ring-white/15 animate-float" />
                    </div>
                </div>
                <a href="{{ route('buyer.katalog') }}" class="relative mt-4 flex items-center justify-between rounded-full bg-mint py-1.5 pl-5 pr-1.5 text-[14px] font-semibold text-forest-deep transition active:scale-[0.98]">
                    Lihat promo
                    <span class="grid size-10 place-items-center rounded-full bg-forest text-mint"><x-icon name="arrow-right" class="size-4" /></span>
                </a>
            </section>

            <section>
                <x-section-title title="Kategori" />
                <nav class="grid grid-cols-5 gap-2">
                    @foreach ($categories as $category)
                        <a href="{{ route('buyer.katalog', ['q' => $category['name']]) }}" class="flex flex-col items-center gap-1.5">
                            <span class="grid size-14 place-items-center rounded-[20px] bg-white text-forest shadow-soft transition active:scale-95">
                                <x-icon :name="$ikonKategori[$category['code']] ?? 'tag'" class="size-[22px]" />
                            </span>
                            <span class="whitespace-nowrap text-[11px] text-muted">{{ $category['name'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </section>

            <section>
                <x-section-title title="Pesanan Aktif" :href="route('buyer.pesanan')" action="Lihat semua" />
                @if ($order)
                    <a href="{{ route('buyer.pesanan.detail', $order['id']) }}" class="surface block space-y-3 p-4">
                        <div class="flex items-center gap-3">
                            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-mint-faint text-forest"><x-icon name="truck" class="size-[18px]" /></span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-[15px] font-semibold text-ink">{{ $order['title'] }}</p>
                                <p class="truncate text-[12px] text-muted">{{ $order['meta'] }}</p>
                            </div>
                        </div>
                        <x-progress-bar :value="$order['progress'] / $order['steps'] * 100" />
                        <div class="flex items-center justify-between gap-2">
                            <x-badge :color="$order['tone']">{{ $order['status'] }}</x-badge>
                            <span class="text-[11px] text-muted">{{ $order['progress'] }}/{{ $order['steps'] }} tahap · {{ $order['eta'] }}</span>
                        </div>
                    </a>
                @else
                    <a href="{{ route('buyer.katalog') }}" class="block rounded-[24px] bg-white/60 px-4 py-6 text-center text-[13px] text-muted">
                        Belum ada pesanan aktif. Yuk pilih menu untuk acara berikutnya.
                    </a>
                @endif
            </section>

            <section>
                <x-section-title title="Rekomendasi untuk Anda" :href="route('buyer.katalog')" action="Lihat semua" />
                <div class="grid grid-cols-2 items-start gap-3">
                    @foreach (array_slice($menus, 0, 4) as $menu)
                        <x-menu-card :menu="$menu" />
                    @endforeach
                </div>
            </section>

            <section>
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h2 class="text-[15px] font-semibold text-ink">Flash Sale Hari Ini</h2>
                    <span class="tabular inline-flex items-center gap-1.5 rounded-full bg-amber-soft px-3 py-1.5 text-[11px] font-semibold text-amber">
                        <x-icon name="clock" class="size-3.5" />
                        Berakhir {{ $flashSaleBerakhir }}
                    </span>
                </div>
                <a href="{{ route('buyer.katalog') }}" class="surface flex items-center gap-3.5 p-3">
                    <x-photo :src="$flashSale['foto'] ?? null" label="Foto" class="size-[84px] shrink-0 rounded-[18px]" />
                    <div class="min-w-0 flex-1 space-y-1.5">
                        <p class="truncate text-body font-semibold text-ink">{{ $flashSale['title'] }}</p>
                        <p class="tabular text-body font-semibold text-forest">
                            Rp {{ number_format($flashSale['harga'], 0, ',', '.') }}
                            <span class="ml-1 text-[11px] font-medium text-muted line-through">Rp {{ number_format($flashSale['harga_normal'], 0, ',', '.') }}</span>
                        </p>
                        <x-progress-bar :value="$flashSale['terjual']" tone="amber" height="h-1.5" />
                        <p class="text-[11px] text-muted">{{ $flashSale['terjual'] }}% terjual</p>
                    </div>
                </a>
            </section>
        </main>
    </div>

    <x-bottom-nav role="buyer" active="buyer.beranda" />
@endsection
