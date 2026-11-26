@extends('layouts.phone')

@section('title', 'Dashboard Seller')

@php
    $terlaris = collect($menus)->max('sold') ?: 1;
    $jam = (int) now()->format('H');
    $sapaan = match (true) {
        $jam < 11 => 'Selamat pagi',
        $jam < 15 => 'Selamat siang',
        $jam < 18 => 'Selamat sore',
        default => 'Selamat malam',
    };
    $pintasan = [
        ['Katalog', 'boxes', route('seller.katalog')],
        ['Kalender', 'calendar', route('seller.kalender')],
        ['Bahan', 'chef', route('seller.kalkulasi')],
        ['Promosi', 'tag', route('seller.promosi')],
        ['Retur', 'refresh', route('seller.retur')],
    ];
@endphp

@section('content')
    <div class="flex-1 bg-cream">
        <header class="flex items-center gap-3 px-5 pb-2 pt-5">
            <span class="grid size-11 shrink-0 place-items-center rounded-full bg-mint-soft text-[15px] font-semibold text-forest">{{ strtoupper(substr($seller->nama, 0, 2)) }}</span>
            <div class="min-w-0 flex-1">
                <p class="text-[12px] text-muted">{{ $sapaan }},</p>
                <h1 class="truncate text-[17px] font-semibold text-ink">{{ $seller->nama }}</h1>
            </div>
            <a href="{{ route('seller.pesanan') }}" class="relative grid size-10 place-items-center rounded-full bg-white text-forest shadow-soft" aria-label="Pesanan masuk">
                <x-icon name="bell" class="size-[18px]" />
                @if ($stats['baru'] > 0)
                    <span class="absolute right-2 top-2 size-2 rounded-full bg-amber ring-2 ring-white"></span>
                @endif
            </a>
        </header>

        <main class="stagger space-y-6 px-5 pb-6 pt-3">
            <section class="relative overflow-hidden rounded-[28px] bg-[linear-gradient(140deg,var(--color-forest-night)_0%,var(--color-forest)_55%,var(--color-leaf)_100%)] p-5 text-white">
                <div class="pointer-events-none absolute -right-10 -top-10 size-44 rounded-full bg-white/5"></div>
                <div class="relative flex items-center gap-3">
                    <div class="min-w-0 flex-1 space-y-1">
                        <p class="text-[12px] text-mint/80">Omzet hari ini</p>
                        <p class="tabular text-[26px] font-semibold leading-tight" data-count-to="{{ (int) $stats['omzet_hari_ini'] }}" data-count-format="rupiah">Rp {{ number_format($stats['omzet_hari_ini'], 0, ',', '.') }}</p>
                        <p class="text-[12px] text-mint/80">{{ number_format($stats['porsi_bulan_ini'], 0, ',', '.') }} porsi bulan ini</p>
                    </div>
                    <div class="relative size-[104px] shrink-0">
                        <span class="absolute inset-2 rounded-full bg-mint/30 animate-ripple"></span>
                        <img src="{{ $fotoUtama }}" alt="" aria-hidden="true" class="relative size-full rounded-full object-cover ring-4 ring-white/15 animate-float" />
                    </div>
                </div>
                <div class="relative mt-4 flex items-center gap-2 text-[12px]">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5">
                        <x-icon name="star" class="size-3.5 text-amber-soft" />
                        {{ $stats['rating'] > 0 ? number_format($stats['rating'], 1) : 'Belum ada' }} rating
                    </span>
                    <span class="rounded-full bg-white/10 px-3 py-1.5">{{ $stats['reviews'] }} ulasan</span>
                </div>
            </section>

            <section class="surface grid grid-cols-3 divide-x divide-line py-4">
                @foreach ([['baru', 'Pesanan baru', 'bg-amber'], ['kirim_hari_ini', 'Kirim hari ini', 'bg-leaf'], ['perlu_update', 'Perlu update', 'bg-info']] as [$kunci, $label, $titik])
                    <a href="{{ route('seller.pesanan') }}" class="flex flex-col items-center gap-1 px-2">
                        <span class="tabular text-[22px] font-semibold text-ink" data-count-to="{{ $stats[$kunci] }}">{{ $stats[$kunci] }}</span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-muted"><span class="size-1.5 rounded-full {{ $titik }}"></span>{{ $label }}</span>
                    </a>
                @endforeach
            </section>

            <nav class="no-scrollbar -mx-5 flex gap-4 overflow-x-auto px-5">
                @foreach ($pintasan as [$label, $ikon, $href])
                    <a href="{{ $href }}" class="flex w-14 shrink-0 flex-col items-center gap-1.5">
                        <span class="grid size-14 place-items-center rounded-[20px] bg-white text-forest shadow-soft transition active:scale-95">
                            <x-icon :name="$ikon" class="size-[22px]" />
                        </span>
                        <span class="text-[11px] text-muted">{{ $label }}</span>
                    </a>
                @endforeach
            </nav>

            <section class="space-y-4">
                <div class="flex items-end justify-between">
                    <div>
                        <h2 class="text-[15px] font-semibold text-ink">Penjualan 7 hari</h2>
                        <p class="tabular text-[12px] text-muted">Rp {{ number_format($omzetMingguIni, 0, ',', '.') }} dari pesanan selesai</p>
                    </div>
                    <a href="{{ route('seller.keuangan') }}" class="text-[12px] font-medium text-leaf-dark">Keuangan</a>
                </div>
                <div class="relative">
                    @if ($omzetMingguIni <= 0)
                        <p class="absolute inset-x-0 top-6 z-10 text-center text-[12px] text-muted">Belum ada pesanan selesai minggu ini.<br>Grafik terisi otomatis saat pesanan selesai.</p>
                    @endif
                    <div @class(['flex h-[110px] items-end gap-3', 'opacity-60' => $omzetMingguIni <= 0])>
                        @foreach ($bars as $index => $height)
                            @php($hariIni = $index === count($bars) - 1)
                            <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                                <div class="w-full max-w-[22px] origin-bottom animate-grow-y rounded-full {{ $hariIni ? 'bg-forest' : 'bg-mint-mid/70' }}" style="height: {{ max(6, (int) $height) }}%; animation-delay: {{ 200 + $index * 70 }}ms"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="-mt-2 flex gap-3">
                    @foreach ($days as $index => $hari)
                        <span @class(['flex-1 text-center text-[10px]', 'font-semibold text-forest' => $index === count($days) - 1, 'text-muted' => $index !== count($days) - 1])>{{ $hari }}</span>
                    @endforeach
                </div>
            </section>

            <section class="space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-[15px] font-semibold text-ink">Perlu diproses</h2>
                    <a href="{{ route('seller.pesanan') }}" class="text-[12px] font-medium text-leaf-dark">Lihat semua</a>
                </div>
                @if (count($orders) > 0)
                    <div class="surface divide-y divide-line/70 px-4">
                        @foreach ($orders as $order)
                            <a href="{{ route('seller.pesanan.detail', $order['id']) }}" class="flex items-center gap-3 py-3.5">
                                <span class="flex w-11 shrink-0 flex-col items-center rounded-2xl bg-mint-faint py-1.5 text-forest">
                                    <span class="text-[15px] font-semibold leading-tight">{{ \Illuminate\Support\Str::before($order['tanggal'], ' ') }}</span>
                                    <span class="text-[10px] uppercase">{{ \Illuminate\Support\Str::of($order['tanggal'])->after(' ')->before(' ') }}</span>
                                </span>
                                <span class="min-w-0 flex-1 space-y-1">
                                    <span class="flex items-baseline justify-between gap-2">
                                        <span class="truncate text-[14px] font-medium text-ink">{{ $order['title'] }}</span>
                                        <span class="tabular shrink-0 text-[13px] font-medium text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                                    </span>
                                    <span class="block truncate text-[12px] text-muted">{{ $order['customer'] }} · {{ $order['detail'] }}</span>
                                    <x-badge :color="$order['tone']">{{ $order['status'] }}</x-badge>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="rounded-[24px] bg-white/60 px-4 py-6 text-center text-[13px] text-muted">Tidak ada pesanan aktif. Pesanan baru muncul di sini.</p>
                @endif
            </section>

            <section class="space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-[15px] font-semibold text-ink">Menu terlaris</h2>
                    <a href="{{ route('seller.katalog') }}" class="text-[12px] font-medium text-leaf-dark">Katalog</a>
                </div>
                <div class="space-y-3">
                    @forelse ($menus as $index => $menu)
                        <div class="flex items-center gap-3">
                            <x-photo :src="$menu['photo']" label="" class="size-12 shrink-0 rounded-2xl" />
                            <div class="min-w-0 flex-1 space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="truncate text-[13px] font-medium text-ink">{{ $menu['name'] }}</p>
                                    <p class="tabular shrink-0 text-[12px] text-muted">{{ $menu['sold'] }} porsi</p>
                                </div>
                                <div class="h-1.5 overflow-hidden rounded-full bg-mint-soft">
                                    <div class="h-full origin-left animate-grow-x rounded-full bg-leaf" style="width: {{ max(4, (int) ($menu['sold'] / $terlaris * 100)) }}%; animation-delay: {{ 300 + $index * 100 }}ms"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-[13px] text-muted">Belum ada menu.</p>
                    @endforelse
                </div>
            </section>
        </main>
    </div>

    <x-bottom-nav role="seller" active="seller.dashboard" />
@endsection
