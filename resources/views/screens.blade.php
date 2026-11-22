@extends('layouts.phone')

@section('title', 'Daftar Layar')

@php
    $groups = [
        'AUTH' => [
            ['A1 Splash', route('splash')],
            ['A2 Login Buyer', route('login', 'buyer')],
            ['A2 Login Seller', route('login', 'seller')],
            ['A2 Login Kurir', route('login', 'kurir')],
        ],
        'BUYER · 15 layar' => [
            ['B1 Beranda', route('buyer.beranda')],
            ['B2 Katalog', route('buyer.katalog')],
            ['B3 Filter', route('buyer.filter')],
            ['B4 Detail Menu', route('buyer.menu', 'nasi-box-ayam-geprek')],
            ['B5 Keranjang', route('buyer.keranjang')],
            ['B6 Checkout', route('buyer.checkout')],
            ['B7 Pembayaran', route('buyer.pembayaran')],
            ['B8 Konfirmasi Pesanan', route('buyer.konfirmasi')],
            ['B9 Pesanan Saya', route('buyer.pesanan')],
            ['B10 Detail Pesanan', route('buyer.pesanan.detail', 'wisuda-its')],
            ['B11 Live Tracking', route('buyer.tracking')],
            ['B12 Konfirmasi Tiba', route('buyer.tiba')],
            ['B13 Ulasan', route('buyer.ulasan')],
            ['B14 Notifikasi', route('buyer.notifikasi')],
            ['B15 Profil', route('buyer.profil')],
        ],
        'SELLER · 14 layar' => [
            ['S1 Dashboard', route('seller.dashboard')],
            ['S2 Pesanan Masuk', route('seller.pesanan')],
            ['S3 Detail Pesanan', route('seller.pesanan.detail', 'wisuda-its')],
            ['S4 Update Milestone', route('seller.milestone')],
            ['S5 Packaging', route('seller.packaging')],
            ['S6 Penugasan Kurir', route('seller.kurir')],
            ['S7 Kelola Katalog', route('seller.katalog')],
            ['S8 Form Menu', route('seller.menu')],
            ['S9 Kalkulasi Bahan', route('seller.kalkulasi')],
            ['S10 Kalender', route('seller.kalender')],
            ['S11 Keuangan', route('seller.keuangan')],
            ['S12 Promosi', route('seller.promosi')],
            ['S13 Pengaturan Toko', route('seller.pengaturan')],
            ['S14 Retur', route('seller.retur')],
        ],
        'KURIR · 6 layar' => [
            ['K1 Beranda Kurir', route('kurir.beranda')],
            ['K2 Daftar Tugas', route('kurir.tugas')],
            ['K3 Detail Tugas', route('kurir.tugas.detail', 'wisuda-its')],
            ['K4 Departure Gate', route('kurir.departure')],
            ['K5 Live Location', route('kurir.live')],
            ['K6 Bukti Serah Terima', route('kurir.bukti')],
        ],
    ];
@endphp

@section('content')
    <x-app-bar title="PawonSync UI" subtitle="39 layar statis · Figma Katering v2" :back="route('splash')" />

    <main class="flex-1 space-y-5 px-4 py-5">
        <div class="rounded-2xl bg-forest p-4 text-mint">
            <p class="text-[11px] font-bold uppercase tracking-wide text-mint/70">Prototype statis</p>
            <p class="mt-1 text-[15px] font-extrabold text-white">Semua layar Buyer, Seller, dan Kurir</p>
            <p class="mt-1 text-[11px] leading-relaxed text-mint/80">Belum ada database. Gunakan daftar ini untuk berpindah cepat antar layar.</p>
        </div>

        @foreach ($groups as $group => $screens)
            <section>
                <x-section-title :title="$group" />
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($screens as [$label, $href])
                        <a href="{{ $href }}" class="flex items-center justify-between gap-2 rounded-xl border border-line bg-white px-3 py-2.5 text-[11px] font-semibold text-forest-deep transition hover:border-leaf hover:text-leaf">
                            <span class="truncate">{{ $label }}</span>
                            <x-icon name="chevron-right" class="size-3.5 shrink-0 text-muted" />
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </main>
@endsection
