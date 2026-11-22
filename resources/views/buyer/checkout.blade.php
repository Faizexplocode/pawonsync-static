@extends('layouts.phone')

@section('title', 'Checkout')

@php
    $menu = config('demo.menus')[0];
    $subtotal = 5700000;
    $ongkir = 85000;
    $diskon = 500000;
    $total = $subtotal + $ongkir - $diskon;
@endphp

@section('content')
    <x-app-bar title="Checkout" subtitle="Lengkapi detail pengiriman" :back="route('buyer.keranjang')" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-start gap-3">
                <span class="grid size-9 shrink-0 place-items-center rounded-full bg-mint-soft text-leaf">
                    <x-icon name="location" class="size-4" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-forest-deep">Alamat pengiriman</p>
                    <p class="mt-0.5 text-[11px] font-semibold text-ink">Bu Diana · 0812 3456 7890</p>
                    <p class="text-[10px] leading-relaxed text-muted">Graha ITS, Kampus ITS Sukolilo, Surabaya</p>
                </div>
                <a href="#" class="text-[10px] font-bold text-leaf">Ubah</a>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Jadwal pengiriman</p>
            <div class="mt-3 grid grid-cols-2 gap-2">
                <div class="rounded-xl bg-mist p-3">
                    <p class="text-[9px] font-semibold text-muted">Tanggal</p>
                    <p class="mt-0.5 text-[12px] font-bold text-ink">12 Okt 2026</p>
                </div>
                <div class="rounded-xl bg-mist p-3">
                    <p class="text-[9px] font-semibold text-muted">Jam antar</p>
                    <p class="mt-0.5 text-[12px] font-bold text-ink">11.00 WIB</p>
                </div>
            </div>
            <p class="mt-2 text-[10px] text-muted">Pesanan harus dikonfirmasi seller minimal H-3.</p>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Rincian pesanan</p>
                <a href="{{ route('buyer.keranjang') }}" class="text-[10px] font-bold text-leaf">Ubah</a>
            </div>
            <div class="mt-3 flex gap-3">
                <x-photo label="Foto" class="size-16 shrink-0" />
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-ink">{{ $menu['name'] }}</p>
                    <p class="text-[10px] text-muted">150 porsi · Paket + Es Teh</p>
                    <p class="mt-1 text-[12px] font-extrabold text-forest">Rp 5.700.000</p>
                </div>
            </div>
            <div class="mt-3 flex gap-3 border-t border-line pt-3">
                <x-photo label="Foto" class="size-16 shrink-0" />
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-ink">Es teh manis</p>
                    <p class="text-[10px] text-muted">150 gelas</p>
                    <p class="mt-1 text-[12px] font-extrabold text-forest">Rp 600.000</p>
                </div>
            </div>
        </section>

        <x-field label="Catatan pengiriman" placeholder="Patokan lokasi, nama penerima, dll." icon="edit" />

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Metode pembayaran</p>
            <div class="mt-3 flex items-center gap-3 rounded-xl bg-mint-soft p-3">
                <span class="grid size-8 place-items-center rounded-lg bg-white text-leaf"><x-icon name="credit-card" class="size-4" /></span>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-bold text-forest-deep">QRIS / Virtual Account</p>
                    <p class="text-[9px] text-muted">Pilih di halaman berikutnya</p>
                </div>
                <x-icon name="chevron-right" class="size-4 text-muted" />
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="space-y-2 text-[11px]">
                <div class="flex justify-between text-muted"><span>Subtotal</span><span class="font-semibold text-ink">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-muted"><span>Ongkir</span><span class="font-semibold text-ink">Rp {{ number_format($ongkir, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-muted"><span>Diskon WISUDA20</span><span class="font-semibold text-leaf">- Rp {{ number_format($diskon, 0, ',', '.') }}</span></div>
                <div class="flex justify-between border-t border-line pt-2 text-[13px] font-extrabold text-forest-deep"><span>Total</span><span class="text-forest">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
            </div>
        </section>

        <a href="{{ route('buyer.pembayaran') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[14px] font-bold text-white shadow-lg shadow-forest/20">
            Lanjut ke pembayaran
            <x-icon name="arrow-right" class="size-4" />
        </a>
    </main>
@endsection
