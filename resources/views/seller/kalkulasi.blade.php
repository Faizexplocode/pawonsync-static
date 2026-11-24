@extends('layouts.phone')

@section('title', 'Kalkulasi Bahan')

@section('content')
    <x-app-bar title="Kalkulasi Total Bahan" :subtitle="$menu ? $menu->nama.' · Panduan dapur' : 'Belum ada menu'" :back="route('seller.katalog')" />

    <main class="flex-1 space-y-3.5 p-4">
        @if (! $menu)
            <x-empty-state title="Belum ada menu">Tambahkan menu terlebih dahulu untuk menghitung estimasi bahan.</x-empty-state>
        @else
            <form action="{{ route('seller.kalkulasi') }}" method="get" class="space-y-3.5">
                <input type="hidden" name="menu" value="{{ $menu->id }}" />
                <section class="flex items-center justify-between gap-3 rounded-[18px] border border-line bg-white px-4 py-3.5">
                    <div class="space-y-0.5">
                        <p class="text-body font-bold text-ink">Jumlah porsi</p>
                        <p class="text-[11px] text-muted">Ubah jika ada perubahan pesanan mendadak</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-qty-stepper name="porsi" :value="$porsi" :min="1" :submit-on-change="true" />
                    </div>
                </section>
            </form>

            <section class="space-y-1 rounded-[20px] bg-[linear-gradient(161deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)] px-[18px] py-4">
                <p class="text-caption font-medium text-mint">Estimasi HPP {{ $porsi }} porsi</p>
                <p class="text-heading-l font-bold text-white">Rp {{ number_format($totalHpp, 0, ',', '.') }}</p>
                <p class="text-[11px] text-mint">Rp {{ number_format($hppPerPorsi, 0, ',', '.') }} per porsi · pendapatan Rp {{ number_format($revenue, 0, ',', '.') }}</p>
            </section>

            <div class="flex gap-2.5">
                <x-stat-card label="Food cost" :value="$foodCost.'%'" sub="dari harga jual" />
                <x-stat-card label="Porsi" :value="$porsi" sub="box" />
                <x-stat-card tone="dark" label="Margin" :value="$marginPersen.'%'" sub="kotor" />
            </div>

            <section class="space-y-3 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <div class="space-y-px">
                            <p class="text-body font-semibold text-ink">Harga jual / porsi</p>
                            <p class="text-[11px] text-muted">Rp {{ number_format($hppPerPorsi, 0, ',', '.') }} estimasi HPP / porsi</p>
                        </div>
                        <p class="text-title font-bold text-forest">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                    </div>
                    <x-progress-bar :value="$foodCost" height="h-1.5" />
                </div>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Margin / porsi</span>
                    <span class="font-semibold text-ink">Rp {{ number_format($menu->harga - $hppPerPorsi, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Margin kotor {{ $porsi }} porsi</span>
                    <span class="font-bold text-forest">Rp {{ number_format($margin, 0, ',', '.') }}</span>
                </div>
            </section>

            <x-alert tone="warning">Harga bahan dapat berubah. Perbarui kalkulasi H-3 sebelum produksi agar margin tetap akurat.</x-alert>
        @endif
    </main>

    @if ($menu)
        <div class="sticky bottom-0 z-20 mt-auto flex items-center gap-2.5 border-t border-line bg-white px-4 py-3">
            <x-button variant="outline" :href="route('seller.menu', $menu->id)" class="flex-1">Edit Menu</x-button>
            <x-button :href="route('seller.katalog')" class="flex-1">Mulai Produksi</x-button>
        </div>
    @endif
@endsection
