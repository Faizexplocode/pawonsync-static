@extends('layouts.phone')

@section('title', 'Promosi')

@php
    $promos = [
        ['code' => 'WISUDA20', 'title' => 'Diskon 20% paket wisuda', 'period' => '1–31 Okt 2026', 'used' => 42, 'quota' => 100, 'status' => 'Aktif', 'tone' => 'green'],
        ['code' => 'ARISAN50', 'title' => 'Potongan Rp 50.000 arisan', 'period' => '5–20 Okt 2026', 'used' => 18, 'quota' => 50, 'status' => 'Aktif', 'tone' => 'green'],
        ['code' => 'SNACK10', 'title' => 'Diskon 10% snack box', 'period' => '1–7 Okt 2026', 'used' => 50, 'quota' => 50, 'status' => 'Habis', 'tone' => 'gray'],
        ['code' => 'NEWYEAR', 'title' => 'Promo tahun baru', 'period' => '1 Jan 2027', 'used' => 0, 'quota' => 200, 'status' => 'Draft', 'tone' => 'amber'],
    ];
@endphp

@section('content')
    <x-app-bar title="Promosi" subtitle="4 kampanye · 2 aktif" :back="route('seller.dashboard')">
        <x-slot:right>
            <a href="#" class="grid size-9 place-items-center rounded-full bg-forest text-white" aria-label="Buat promo">
                <x-icon name="plus" class="size-4" />
            </a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl bg-forest p-4 text-white">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-mint/70">Performa promosi</p>
            <p class="mt-1 text-[24px] font-extrabold leading-none">Rp 8,2 jt</p>
            <p class="mt-1 text-[10px] text-mint/80">Pendapatan dari pesanan berpromo bulan ini</p>
            <div class="mt-3 grid grid-cols-3 gap-2 border-t border-white/15 pt-3 text-center">
                <div><p class="text-[13px] font-extrabold">110</p><p class="text-[9px] text-mint/70">Pemakaian</p></div>
                <div><p class="text-[13px] font-extrabold">4,8x</p><p class="text-[9px] text-mint/70">ROAS</p></div>
                <div><p class="text-[13px] font-extrabold">Rp 1,7 jt</p><p class="text-[9px] text-mint/70">Diskon</p></div>
            </div>
        </section>

        <div class="grid grid-cols-3 gap-1 rounded-2xl bg-white p-1 ring-1 ring-line">
            <span class="rounded-xl bg-forest py-2 text-center text-[11px] font-bold text-white">Semua</span>
            <a href="#" class="rounded-xl py-2 text-center text-[11px] font-bold text-muted">Aktif</a>
            <a href="#" class="rounded-xl py-2 text-center text-[11px] font-bold text-muted">Selesai</a>
        </div>

        <section class="space-y-3">
            @foreach ($promos as $promo)
                <div class="rounded-2xl border border-line bg-white p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-[12px] font-extrabold text-forest-deep">{{ $promo['title'] }}</p>
                            <p class="mt-0.5 text-[9px] text-muted">{{ $promo['period'] }}</p>
                        </div>
                        <x-badge :color="$promo['tone']">{{ $promo['status'] }}</x-badge>
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                        <span class="rounded-lg border border-dashed border-leaf bg-mint-soft px-2.5 py-1 text-[10px] font-extrabold tracking-wide text-forest">{{ $promo['code'] }}</span>
                        <span class="text-[9px] text-muted">Kode voucher</span>
                    </div>
                    <div class="mt-3">
                        <div class="mb-1 flex items-center justify-between text-[9px] font-semibold">
                            <span class="text-muted">Terpakai {{ $promo['used'] }} / {{ $promo['quota'] }}</span>
                            <span class="text-forest">{{ $promo['quota'] > 0 ? round($promo['used'] / $promo['quota'] * 100) : 0 }}%</span>
                        </div>
                        <x-progress-bar :value="$promo['quota'] > 0 ? $promo['used'] / $promo['quota'] * 100 : 0" :tone="$promo['status'] === 'Habis' ? 'amber' : 'leaf'" />
                    </div>
                    <div class="mt-3 flex gap-2 border-t border-line pt-2.5">
                        <a href="#" class="flex-1 rounded-lg border border-line py-2 text-center text-[10px] font-bold text-muted">Salin kode</a>
                        <a href="#" class="flex-1 rounded-lg border border-line py-2 text-center text-[10px] font-bold text-muted">Edit</a>
                        <a href="#" class="flex-1 rounded-lg bg-forest py-2 text-center text-[10px] font-bold text-white">Lihat hasil</a>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Ide promosi berikutnya</p>
            <div class="mt-2 space-y-2">
                @foreach (['Bundling nasi box + dessert', 'Gratis ongkir min. 100 porsi', 'Diskon repeat order 15%'] as $idea)
                    <div class="flex items-center gap-2 rounded-xl bg-mist px-3 py-2.5">
                        <x-icon name="gift" class="size-4 shrink-0 text-leaf" />
                        <span class="flex-1 text-[10px] font-semibold text-forest-deep">{{ $idea }}</span>
                        <span class="text-[9px] font-bold text-leaf">Buat</span>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection
