@extends('layouts.phone')

@section('title', 'Keuangan')

@section('content')
    <header class="bg-[linear-gradient(140deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)]">
        <x-status-bar tone="light" />
        <div class="flex items-center gap-3 px-4 pb-1.5 pt-2.5">
            <div class="min-w-0 flex-1 space-y-0.5">
                <h1 class="text-[18px] font-bold text-white">Keuangan &amp; Saldo</h1>
                <p class="truncate text-caption text-mint">{{ $seller->nama }}</p>
            </div>
            <span class="text-caption font-semibold text-mint">{{ $jumlahSelesai }} selesai</span>
        </div>
        <div class="space-y-1 px-4 pb-5">
            <p class="text-caption font-medium text-mint">Pendapatan bersih</p>
            <p class="text-[32px] font-bold text-white">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
            <p class="text-[11px] text-mint">Setelah komisi platform 5% dari pesanan selesai</p>
        </div>
    </header>

    <main class="flex-1 space-y-4 p-4">
        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Rincian komisi &amp; biaya</p>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Penjualan kotor</span>
                <span class="font-semibold text-ink">Rp {{ number_format($masuk, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Komisi PawonSync (5%)</span>
                <span class="font-semibold text-danger">- Rp {{ number_format($komisi, 0, ',', '.') }}</span>
            </div>
            <div class="h-px bg-line"></div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Pendapatan bersih</span>
                <span class="font-bold text-forest">Rp {{ number_format($laba, 0, ',', '.') }}</span>
            </div>
        </section>

        <div class="flex gap-2.5">
            @php
                $ringkas = fn (float $nominal): string => $nominal >= 1000000
                    ? 'Rp '.rtrim(rtrim(number_format($nominal / 1000000, 2, ',', '.'), '0'), ',').' jt'
                    : 'Rp '.number_format($nominal, 0, ',', '.');
            @endphp
            <x-stat-card label="Kotor" :value="$ringkas($masuk)" sub="pesanan selesai" />
            <x-stat-card label="Komisi" :value="$ringkas($komisi)" sub="5%" />
            <x-stat-card tone="dark" label="Bersih" :value="$ringkas($laba)" sub="masuk" />
        </div>

        <x-section-title title="Riwayat Transaksi" />
        <section class="-mt-3 divide-y divide-line rounded-[18px] border border-line bg-white px-4 py-1.5">
            @forelse ($transactions as $transaction)
                @php($masukDana = $transaction['type'] === 'in')
                <div class="flex items-center gap-3 py-2.5">
                    <span @class([
                        'grid size-9 shrink-0 place-items-center rounded-xl text-caption font-bold',
                        'bg-mint-soft text-forest' => $masukDana,
                        'bg-danger-soft text-danger' => ! $masukDana,
                    ])>{{ $masukDana ? 'In' : 'Out' }}</span>
                    <div class="min-w-0 flex-1 space-y-0.5">
                        <p class="truncate text-[13px] font-semibold text-ink">{{ $transaction['title'] }}</p>
                        <p class="text-[11px] text-muted">{{ $transaction['date'] }} · {{ $transaction['status'] }}</p>
                    </div>
                    <span @class(['shrink-0 text-[13px] font-bold', 'text-leaf' => $masukDana, 'text-danger' => ! $masukDana])>
                        {{ $masukDana ? '+' : '-' }} Rp {{ number_format(abs($transaction['amount']), 0, ',', '.') }}
                    </span>
                </div>
            @empty
                <p class="py-4 text-center text-caption text-muted">Belum ada transaksi dari pesanan selesai.</p>
            @endforelse
        </section>

        <x-alert tone="warning">Data keuangan dihitung otomatis dari pesanan berstatus selesai. Pencairan mengikuti kebijakan platform.</x-alert>
    </main>

    <x-bottom-nav role="seller" active="seller.keuangan" />
@endsection
