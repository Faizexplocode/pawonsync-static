@extends('layouts.phone')

@section('title', 'Kalkulasi Bahan')

@php
    $ingredients = [
        ['name' => 'Beras premium', 'qty' => '22,5 kg', 'cost' => 315000, 'unit' => 'Rp 14.000/kg'],
        ['name' => 'Ayam paha atas', 'qty' => '27 kg', 'cost' => 1026000, 'unit' => 'Rp 38.000/kg'],
        ['name' => 'Bumbu geprek', 'qty' => '1 set', 'cost' => 185000, 'unit' => 'Rp 1.233/porsi'],
        ['name' => 'Sayur & lalapan', 'qty' => '12 kg', 'cost' => 216000, 'unit' => 'Rp 18.000/kg'],
        ['name' => 'Sambal bawang', 'qty' => '4,5 kg', 'cost' => 135000, 'unit' => 'Rp 30.000/kg'],
        ['name' => 'Box & kemasan', 'qty' => '150 pcs', 'cost' => 375000, 'unit' => 'Rp 2.500/pcs'],
        ['name' => 'Es teh manis', 'qty' => '150 gelas', 'cost' => 180000, 'unit' => 'Rp 1.200/gelas'],
    ];
    $total = collect($ingredients)->sum('cost');
    $revenue = 5700000;
    $margin = $revenue - $total;
@endphp

@section('content')
    <x-app-bar title="Kalkulasi Bahan" subtitle="Nasi Box Ayam Geprek · 150 porsi" :back="route('seller.katalog')">
        <x-slot:right>
            <a href="#" class="rounded-full bg-mint-soft px-3 py-1.5 text-[10px] font-bold text-forest">Ekspor</a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl bg-forest p-4 text-white">
            <p class="text-[10px] font-semibold uppercase tracking-wide text-mint/70">Estimasi HPP</p>
            <p class="mt-1 text-[26px] font-extrabold leading-none">Rp {{ number_format($total, 0, ',', '.') }}</p>
            <p class="mt-1 text-[10px] text-mint/80">Rp {{ number_format($total / 150, 0, ',', '.') }} per porsi</p>
            <div class="mt-3 grid grid-cols-2 gap-2 border-t border-white/15 pt-3 text-[10px]">
                <div>
                    <p class="text-mint/70">Pendapatan</p>
                    <p class="mt-0.5 text-[12px] font-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-mint/70">Margin kotor</p>
                    <p class="mt-0.5 text-[12px] font-bold">Rp {{ number_format($margin, 0, ',', '.') }}</p>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-3 gap-2">
            <x-stat-card label="Food cost" value="{{ round($total / $revenue * 100) }}%" sub="dari harga jual" />
            <x-stat-card label="Porsi" value="150" sub="box" />
            <x-stat-card label="Margin" value="{{ round($margin / $revenue * 100) }}%" sub="kotor" tone="dark" />
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Rincian bahan</p>
                <span class="text-[9px] text-muted">7 item</span>
            </div>
            <div class="mt-2 divide-y divide-line">
                @foreach ($ingredients as $ingredient)
                    <div class="flex items-center gap-3 py-2.5">
                        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-mint-soft text-leaf">
                            <x-icon name="boxes" class="size-4" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-semibold text-ink">{{ $ingredient['name'] }}</p>
                            <p class="text-[9px] text-muted">{{ $ingredient['qty'] }} · {{ $ingredient['unit'] }}</p>
                        </div>
                        <span class="text-[11px] font-bold text-forest-deep">Rp {{ number_format($ingredient['cost'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex items-center justify-between pt-3 text-[12px] font-extrabold text-forest-deep">
                    <span>Total HPP</span>
                    <span class="text-forest">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Simulasi harga jual</p>
            <div class="mt-3 space-y-2 text-[11px]">
                <div class="flex items-center justify-between rounded-xl bg-mist px-3 py-2">
                    <span class="text-muted">Harga jual saat ini</span>
                    <span class="font-bold text-forest-deep">Rp 38.000</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-mist px-3 py-2">
                    <span class="text-muted">Margin per porsi</span>
                    <span class="font-bold text-forest-deep">Rp {{ number_format($margin / 150, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-mint-soft px-3 py-2">
                    <span class="font-semibold text-forest">Rekomendasi harga</span>
                    <span class="font-bold text-forest">Rp 41.000</span>
                </div>
            </div>
            <p class="mt-2 text-[9px] leading-relaxed text-muted">Rekomendasi dihitung dari food cost maksimal 32% dan biaya operasional dapur.</p>
        </section>

        <div class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            Harga bahan dapat berubah. Perbarui kalkulasi H-3 sebelum produksi agar margin tetap akurat.
        </div>

        <a href="{{ route('seller.menu') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
            <x-icon name="refresh" class="size-4" />
            Perbarui harga menu
        </a>
    </main>
@endsection
