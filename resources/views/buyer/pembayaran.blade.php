@extends('layouts.phone')

@section('title', 'Pembayaran')

@php
    $methods = [
        ['name' => 'QRIS', 'note' => 'Scan dengan semua e-wallet', 'icon' => 'sliders', 'active' => true],
        ['name' => 'Virtual Account BCA', 'note' => 'Transfer otomatis terverifikasi', 'icon' => 'banknote', 'active' => false],
        ['name' => 'GoPay / OVO / Dana', 'note' => 'Bayar lewat aplikasi e-wallet', 'icon' => 'wallet', 'active' => false],
        ['name' => 'Bayar di Tempat (COD)', 'note' => 'Bayar saat pesanan tiba', 'icon' => 'banknote', 'active' => false],
    ];
@endphp

@section('content')
    <x-app-bar title="Pembayaran" subtitle="Selesaikan dalam 14:59" :back="route('buyer.checkout')" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl bg-forest p-4 text-white">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-mint/70">Total tagihan</p>
            <p class="mt-1 text-[28px] font-extrabold leading-none">Rp 5.455.000</p>
            <div class="mt-3 flex items-center justify-between text-[10px] font-semibold">
                <span class="text-mint/80">Batas pembayaran</span>
                <span class="rounded-full bg-mint px-2.5 py-1 text-forest">14:59</span>
            </div>
        </section>

        <section>
            <p class="mb-2 text-[12px] font-bold text-forest-deep">Pilih metode pembayaran</p>
            <div class="space-y-2">
                @foreach ($methods as $method)
                    <label @class(['flex cursor-pointer items-center gap-3 rounded-2xl border p-3', 'border-leaf bg-mint-soft' => $method['active'], 'border-line bg-white' => ! $method['active']])>
                        <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-white text-leaf ring-1 ring-line">
                            <x-icon :name="$method['icon']" class="size-4" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[12px] font-bold text-ink">{{ $method['name'] }}</span>
                            <span class="block text-[10px] text-muted">{{ $method['note'] }}</span>
                        </span>
                        <span @class(['grid size-5 place-items-center rounded-full border-2', 'border-leaf bg-leaf text-white' => $method['active'], 'border-line' => ! $method['active']])>
                            @if ($method['active'])<x-icon name="check" class="size-3" />@endif
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Ringkasan</p>
            <div class="mt-2 space-y-1.5 text-[11px]">
                <div class="flex justify-between text-muted"><span>Subtotal</span><span class="font-semibold text-ink">Rp 5.700.000</span></div>
                <div class="flex justify-between text-muted"><span>Ongkir</span><span class="font-semibold text-ink">Rp 85.000</span></div>
                <div class="flex justify-between text-muted"><span>Diskon</span><span class="font-semibold text-leaf">- Rp 500.000</span></div>
                <div class="flex justify-between text-muted"><span>Biaya layanan</span><span class="font-semibold text-ink">Rp 170.000</span></div>
                <div class="flex justify-between border-t border-line pt-2 text-[13px] font-extrabold text-forest-deep"><span>Total</span><span class="text-forest">Rp 5.455.000</span></div>
            </div>
        </section>

        <div class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            <span class="font-bold">Penting:</span> pesanan diproses setelah pembayaran terverifikasi. Simpan bukti pembayaran sampai pesanan selesai.
        </div>

        <a href="{{ route('buyer.konfirmasi') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[14px] font-bold text-white shadow-lg shadow-forest/20">
            Bayar sekarang
            <x-icon name="shield" class="size-4" />
        </a>
    </main>
@endsection
