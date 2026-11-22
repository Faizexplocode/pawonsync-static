@extends('layouts.phone')

@section('title', 'Detail Pesanan Seller')

@php
    $steps = [
        ['Pesanan masuk', '12 Okt 08.14', true],
        ['Dikonfirmasi seller', '12 Okt 08.20', true],
        ['Produksi dimulai', '11 Okt 06.00', false],
        ['Siap kirim', '12 Okt 10.00', false],
        ['Diterima buyer', '12 Okt 10.30', false],
    ];
@endphp

@section('content')
    <x-app-bar title="Detail Pesanan" :subtitle="'PS-' . strtoupper($order['id'])" :back="route('seller.pesanan')">
        <x-slot:right>
            <x-badge :color="$order['tone']">{{ $order['status'] }}</x-badge>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl bg-forest p-4 text-white">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-mint/70">Nilai pesanan</p>
            <p class="mt-1 text-[26px] font-extrabold leading-none">Rp {{ number_format($order['total'], 0, ',', '.') }}</p>
            <div class="mt-3 grid grid-cols-3 gap-2 border-t border-white/15 pt-3 text-center">
                <div><p class="text-[13px] font-extrabold">150</p><p class="text-[9px] text-mint/70">Porsi</p></div>
                <div><p class="text-[13px] font-extrabold">11.00</p><p class="text-[9px] text-mint/70">Jam antar</p></div>
                <div><p class="text-[13px] font-extrabold">12 Okt</p><p class="text-[9px] text-mint/70">Tanggal</p></div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Informasi pemesan</p>
            <div class="mt-3 flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-full bg-mint text-[12px] font-extrabold text-forest">
                    {{ strtoupper(substr($order['customer'], 0, 2)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-ink">{{ $order['customer'] }}</p>
                    <p class="text-[10px] text-muted">0812 3456 7890 · Bu Diana</p>
                </div>
                <a href="#" class="grid size-9 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="phone" class="size-4" /></a>
            </div>
            <div class="mt-3 space-y-2 border-t border-line pt-3 text-[11px]">
                <div class="flex gap-2 text-muted"><x-icon name="location" class="mt-0.5 size-3.5 shrink-0" /><span>Graha ITS, Kampus ITS Sukolilo, Surabaya</span></div>
                <div class="flex gap-2 text-muted"><x-icon name="clock" class="mt-0.5 size-3.5 shrink-0" /><span>Antar 12 Okt 2026, 11.00 WIB</span></div>
                <div class="flex gap-2 text-muted"><x-icon name="edit" class="mt-0.5 size-3.5 shrink-0" /><span>Sambal dipisah, 20 box tanpa kerupuk</span></div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Rincian menu</p>
                <a href="{{ route('seller.katalog') }}" class="text-[10px] font-bold text-leaf">Ubah</a>
            </div>
            <div class="mt-3 space-y-3">
                <div class="flex gap-3">
                    <x-photo label="Foto" class="size-14 shrink-0" />
                    <div class="min-w-0 flex-1">
                        <p class="text-[12px] font-bold text-ink">Nasi Box Ayam Geprek</p>
                        <p class="text-[10px] text-muted">150 porsi · Paket + Es Teh</p>
                    </div>
                    <span class="text-[12px] font-extrabold text-forest">Rp 5.700.000</span>
                </div>
                <div class="flex gap-3">
                    <x-photo label="Foto" class="size-14 shrink-0" />
                    <div class="min-w-0 flex-1">
                        <p class="text-[12px] font-bold text-ink">Es teh manis</p>
                        <p class="text-[10px] text-muted">150 gelas</p>
                    </div>
                    <span class="text-[12px] font-extrabold text-forest">Rp 600.000</span>
                </div>
            </div>
            <div class="mt-3 space-y-1.5 border-t border-line pt-3 text-[11px]">
                <div class="flex justify-between text-muted"><span>Subtotal</span><span class="font-semibold text-ink">Rp 6.300.000</span></div>
                <div class="flex justify-between text-muted"><span>Ongkir</span><span class="font-semibold text-ink">Rp 85.000</span></div>
                <div class="flex justify-between text-muted"><span>Diskon platform</span><span class="font-semibold text-leaf">- Rp 500.000</span></div>
                <div class="flex justify-between border-t border-line pt-2 text-[12px] font-extrabold text-forest-deep"><span>Pendapatan bersih</span><span class="text-forest">Rp 5.337.500</span></div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Timeline pesanan</p>
            <div class="mt-3 space-y-3">
                @foreach ($steps as [$title, $time, $done])
                    <div class="flex gap-3">
                        <span @class(['mt-0.5 grid size-5 shrink-0 place-items-center rounded-full text-white', 'bg-leaf' => $done, 'bg-line text-muted' => ! $done])>
                            @if ($done)<x-icon name="check" class="size-3" />@else<span class="size-1.5 rounded-full bg-muted"></span>@endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold {{ $done ? 'text-forest-deep' : 'text-muted' }}">{{ $title }}</p>
                            <p class="text-[9px] text-muted">{{ $time }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            <span class="font-bold">Perhatian:</span> konfirmasi pesanan maksimal 1x24 jam. Setelah dikonfirmasi, jadwal produksi otomatis masuk kalender dapur.
        </section>

        <div class="grid grid-cols-2 gap-2">
            <a href="#" class="flex items-center justify-center rounded-2xl border border-line bg-white px-4 py-3.5 text-[13px] font-bold text-muted">Tolak</a>
            <a href="{{ route('seller.milestone') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
                Terima &amp; proses
                <x-icon name="arrow-right" class="size-4" />
            </a>
        </div>
    </main>
@endsection
