@extends('layouts.phone')

@section('title', 'Keranjang')

@php
    $menu = config('demo.menus')[0];
    $subtotal = $menu['price'] * 150 + 4000 * 150;
    $ongkir = 85000;
    $diskon = 500000;
    $total = $subtotal + $ongkir - $diskon;
@endphp

@section('content')
    <x-app-bar title="Keranjang" subtitle="2 item · 150 porsi" :back="route('buyer.menu', $menu['slug'])" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-3">
            <div class="flex gap-3">
                <x-photo label="Foto" class="size-20 shrink-0" />
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[13px] font-extrabold text-ink">{{ $menu['name'] }}</p>
                        <button class="text-muted"><x-icon name="trash" class="size-4" /></button>
                    </div>
                    <p class="mt-0.5 text-[10px] text-muted">{{ $menu['seller'] }} · Paket + Es Teh</p>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[13px] font-extrabold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                        <x-qty :value="150" />
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-3">
            <div class="flex gap-3">
                <x-photo label="Foto" class="size-20 shrink-0" />
                <div class="min-w-0 flex-1">
                    <p class="text-[13px] font-extrabold text-ink">Es teh manis</p>
                    <p class="mt-0.5 text-[10px] text-muted">Tambahan · Per gelas</p>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[13px] font-extrabold text-forest">Rp 4.000</span>
                        <x-qty :value="150" />
                    </div>
                </div>
            </div>
        </section>

        <x-field label="Catatan untuk dapur" placeholder="Contoh: sambal dipisah, tanpa kerupuk..." icon="edit" />

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[13px] font-bold text-forest-deep">Ringkasan pembayaran</p>
            <div class="mt-3 space-y-2 text-[11px]">
                <div class="flex justify-between text-muted"><span>Subtotal (150 porsi)</span><span class="font-semibold text-ink">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-muted"><span>Ongkir</span><span class="font-semibold text-ink">Rp {{ number_format($ongkir, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-muted"><span>Diskon WISUDA20</span><span class="font-semibold text-leaf">- Rp {{ number_format($diskon, 0, ',', '.') }}</span></div>
                <div class="mt-2 flex justify-between border-t border-line pt-2 text-[13px] font-extrabold text-forest-deep"><span>Total</span><span class="text-forest">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
            </div>
        </section>

        <a href="{{ route('buyer.checkout') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[14px] font-bold text-white shadow-lg shadow-forest/20">
            Lanjut ke checkout
            <x-icon name="arrow-right" class="size-4" />
        </a>
    </main>
@endsection
