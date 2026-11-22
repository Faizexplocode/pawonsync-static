@extends('layouts.phone')

@section('title', 'Keuangan')

@php
    $transactions = [
        ['title' => 'Pencairan Wisuda ITS', 'date' => '12 Okt 2026', 'amount' => 5337500, 'type' => 'in', 'status' => 'Masuk'],
        ['title' => 'Pencairan Seminar FEB', 'date' => '11 Okt 2026', 'amount' => 3120000, 'type' => 'in', 'status' => 'Masuk'],
        ['title' => 'Komisi platform', 'date' => '11 Okt 2026', 'amount' => -156000, 'type' => 'out', 'status' => 'Potongan'],
        ['title' => 'Belanja bahan', 'date' => '10 Okt 2026', 'amount' => -2432000, 'type' => 'out', 'status' => 'Keluar'],
        ['title' => 'Pencairan Arisan RT 05', 'date' => '09 Okt 2026', 'amount' => 1940000, 'type' => 'in', 'status' => 'Masuk'],
    ];
@endphp

@section('content')
    <x-app-bar title="Keuangan" subtitle="Pawon Lestari" :back="route('seller.dashboard')">
        <x-slot:right>
            <a href="#" class="rounded-full bg-mint-soft px-3 py-1.5 text-[10px] font-bold text-forest">Riwayat</a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="relative overflow-hidden rounded-3xl bg-forest p-4 text-white">
            <div class="pointer-events-none absolute -right-8 -top-10 size-32 rounded-full bg-leaf/40 blur-xl"></div>
            <p class="text-[10px] font-semibold uppercase tracking-wide text-mint/70">Saldo tersedia</p>
            <p class="mt-1 text-[28px] font-extrabold leading-none">Rp 12.480.000</p>
            <div class="mt-3 flex items-center gap-2">
                <a href="#" class="inline-flex items-center gap-1.5 rounded-full bg-mint px-3.5 py-2 text-[11px] font-bold text-forest">
                    <x-icon name="banknote" class="size-3.5" />
                    Cairkan
                </a>
                <a href="#" class="inline-flex items-center gap-1.5 rounded-full bg-forest-deep/60 px-3.5 py-2 text-[11px] font-bold text-mint">
                    Lihat rekening
                </a>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2 border-t border-white/15 pt-3 text-[10px]">
                <div>
                    <p class="text-mint/70">Pendapatan bulan ini</p>
                    <p class="mt-0.5 text-[13px] font-bold">Rp 28,4 jt</p>
                </div>
                <div>
                    <p class="text-mint/70">Menunggu cair</p>
                    <p class="mt-0.5 text-[13px] font-bold">Rp 3,1 jt</p>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-3 gap-2">
            <x-stat-card label="Masuk" value="Rp 10,4 jt" sub="bulan ini" />
            <x-stat-card label="Keluar" value="Rp 2,6 jt" sub="bahan & komisi" />
            <x-stat-card label="Laba" value="Rp 7,8 jt" sub="+14%" tone="dark" />
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Arus kas 7 hari</p>
                <span class="text-[10px] font-bold text-leaf">+18%</span>
            </div>
            <div class="mt-3 flex h-24 items-end gap-1.5">
                @foreach ([38, 52, 44, 68, 58, 84, 72] as $index => $height)
                    <div class="flex flex-1 flex-col items-center gap-1">
                        <div class="w-full rounded-t-md {{ $index === 5 ? 'bg-forest' : 'bg-mint' }}" style="height: {{ $height }}%"></div>
                        <span class="text-[8px] text-muted">{{ ['S', 'S', 'R', 'K', 'J', 'S', 'M'][$index] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section>
            <x-section-title title="Transaksi terakhir" action="Semua" href="#" />
            <div class="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
                @foreach ($transactions as $transaction)
                    <div class="flex items-center gap-3 p-3">
                        <span @class([
                            'grid size-9 shrink-0 place-items-center rounded-full',
                            'bg-mint text-forest' => $transaction['type'] === 'in',
                            'bg-amber-soft text-amber' => $transaction['type'] === 'out',
                        ])>
                            <x-icon :name="$transaction['type'] === 'in' ? 'arrow-up-right' : 'banknote'" class="size-4" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[11px] font-bold text-ink">{{ $transaction['title'] }}</p>
                            <p class="text-[9px] text-muted">{{ $transaction['date'] }} · {{ $transaction['status'] }}</p>
                        </div>
                        <span @class([
                            'text-[11px] font-bold',
                            'text-forest' => $transaction['type'] === 'in',
                            'text-muted' => $transaction['type'] !== 'in',
                        ])>
                            {{ $transaction['type'] === 'in' ? '+' : '-' }} Rp {{ number_format(abs($transaction['amount']), 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Rekening pencairan</p>
            <div class="mt-2 flex items-center gap-3 rounded-xl bg-mist p-3">
                <span class="grid size-9 place-items-center rounded-lg bg-white text-[9px] font-extrabold text-forest ring-1 ring-line">BCA</span>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-bold text-ink">Bank BCA · 1234 5678 90</p>
                    <p class="text-[9px] text-muted">a.n. Naomy Lestari</p>
                </div>
                <a href="{{ route('seller.pengaturan') }}" class="text-[10px] font-bold text-leaf">Ubah</a>
            </div>
            <p class="mt-2 text-[9px] text-muted">Pencairan otomatis setiap Senin &amp; Kamis, maksimal 2x24 jam.</p>
        </section>
    </main>

    <x-bottom-nav role="seller" active="seller.keuangan" />
@endsection
