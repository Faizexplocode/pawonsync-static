@extends('layouts.phone')

@section('title', 'Profil')

@php
    $buyer = config('demo.buyer');
    $items = [
        ['Pesanan saya', 'receipt', route('buyer.pesanan')],
        ['Alamat pengiriman', 'location', '#'],
        ['Metode pembayaran', 'credit-card', '#'],
        ['Voucher & promo', 'gift', '#'],
        ['Bantuan & pusat bantuan', 'help', '#'],
        ['Pengaturan akun', 'settings', '#'],
    ];
@endphp

@section('content')
    <x-app-bar title="Profil" :back="route('buyer.beranda')">
        <x-slot:right>
            <a href="#" class="grid size-9 place-items-center rounded-full bg-white text-forest-deep ring-1 ring-line" aria-label="Pengaturan">
                <x-icon name="settings" class="size-4" />
            </a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-3xl bg-forest p-4 text-white">
            <div class="flex items-center gap-3">
                <span class="grid size-14 place-items-center rounded-full bg-mint text-[16px] font-extrabold text-forest">BD</span>
                <div class="min-w-0 flex-1">
                    <p class="text-[16px] font-extrabold">{{ $buyer['name'] }}</p>
                    <p class="text-[10px] text-mint/80">diana@email.com · 0812 3456 7890</p>
                    <p class="mt-1 inline-flex items-center gap-1 rounded-full bg-forest-deep/60 px-2 py-0.5 text-[9px] font-semibold text-mint">
                        <x-icon name="shield" class="size-3" />
                        Buyer terverifikasi
                    </p>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-2 border-t border-white/15 pt-3 text-center">
                <div><p class="text-[15px] font-extrabold">12</p><p class="text-[9px] text-mint/70">Pesanan</p></div>
                <div><p class="text-[15px] font-extrabold">4.9</p><p class="text-[9px] text-mint/70">Rating</p></div>
                <div><p class="text-[15px] font-extrabold">3</p><p class="text-[9px] text-mint/70">Voucher</p></div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-line bg-white">
            @foreach ($items as [$label, $icon, $href])
                <a href="{{ $href }}" class="flex items-center gap-3 border-b border-line px-4 py-3 last:border-0">
                    <span class="grid size-8 place-items-center rounded-lg bg-mint-soft text-leaf">
                        <x-icon :name="$icon" class="size-4" />
                    </span>
                    <span class="flex-1 text-[12px] font-semibold text-forest-deep">{{ $label }}</span>
                    <x-icon name="chevron-right" class="size-4 text-muted" />
                </a>
            @endforeach
        </section>

        <a href="{{ route('splash') }}" class="flex items-center justify-center gap-2 rounded-2xl border border-line bg-white px-4 py-3.5 text-[13px] font-bold text-forest-deep">
            <x-icon name="logout" class="size-4" />
            Keluar
        </a>

        <p class="text-center text-[9px] text-muted">PawonSync v1.0 · Mode statis</p>
    </main>

    <x-bottom-nav role="buyer" active="buyer.profil" />
@endsection
