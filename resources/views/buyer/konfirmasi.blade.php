@extends('layouts.phone')

@section('title', 'Pesanan Dikonfirmasi')

@php
    $order = config('demo.orders')[0];
@endphp

@section('content')
    <x-app-bar title="Konfirmasi Pesanan" :back="route('buyer.pembayaran')" />

    <main class="flex-1 space-y-4 px-4 py-5">
        <section class="flex flex-col items-center rounded-3xl bg-forest px-5 py-7 text-center text-white">
            <span class="grid size-16 place-items-center rounded-full bg-mint text-forest">
                <x-icon name="check" class="size-8" />
            </span>
            <h2 class="mt-4 text-[20px] font-extrabold">Pembayaran berhasil!</h2>
            <p class="mt-1 text-[11px] leading-relaxed text-mint/80">Pesananmu sudah diteruskan ke {{ $order['title'] }} dan akan segera diproses dapur.</p>
            <p class="mt-3 rounded-full bg-forest-deep/60 px-3 py-1.5 text-[10px] font-semibold text-mint">Order ID · PS-{{ strtoupper($order['id']) }}</p>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[13px] font-bold text-forest-deep">{{ $order['title'] }}</p>
                <x-badge color="green">{{ $order['status'] }}</x-badge>
            </div>
            <p class="mt-1 text-[10px] text-muted">{{ $order['meta'] }}</p>
            <div class="mt-3 space-y-2 text-[11px]">
                <div class="flex justify-between text-muted"><span>Total dibayar</span><span class="font-bold text-forest">Rp 5.455.000</span></div>
                <div class="flex justify-between text-muted"><span>Metode</span><span class="font-semibold text-ink">QRIS</span></div>
                <div class="flex justify-between text-muted"><span>Estimasi antar</span><span class="font-semibold text-ink">{{ $order['eta'] }}</span></div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Langkah selanjutnya</p>
            <div class="mt-3 space-y-3">
                @foreach ([
                    ['Dikonfirmasi seller', 'Seller menerima dan menyiapkan jadwal produksi', true],
                    ['Diproses dapur', 'Bahan dihitung, produksi dimulai H-1', false],
                    ['Dikirim kurir', 'Kurir membawa pesanan ke venue', false],
                    ['Diterima', 'Konfirmasi serah terima di lokasi', false],
                ] as [$title, $desc, $done])
                    <div class="flex gap-3">
                        <span @class(['mt-0.5 grid size-5 shrink-0 place-items-center rounded-full text-white', 'bg-leaf' => $done, 'bg-line text-muted' => ! $done])>
                            @if ($done)<x-icon name="check" class="size-3" />@else<span class="size-1.5 rounded-full bg-muted"></span>@endif
                        </span>
                        <div>
                            <p class="text-[11px] font-bold {{ $done ? 'text-forest-deep' : 'text-muted' }}">{{ $title }}</p>
                            <p class="text-[9px] leading-relaxed text-muted">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <a href="{{ route('buyer.pesanan.detail', $order['id']) }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[14px] font-bold text-white">
            Lihat detail pesanan
            <x-icon name="arrow-right" class="size-4" />
        </a>
        <a href="{{ route('buyer.beranda') }}" class="flex items-center justify-center rounded-2xl border border-line bg-white px-4 py-3.5 text-[13px] font-bold text-forest-deep">
            Kembali ke beranda
        </a>
    </main>
@endsection
