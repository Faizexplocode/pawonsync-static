@extends('layouts.phone')

@section('title', $menu['name'])

@php
    $packages = $menu['packages'] ?? [];
    $extras = $menu['extras'] ?? [];
    $plate = $menu['plate'] ?? [];
    $chip = 'inline-flex items-center rounded-full bg-white px-4 py-2.5 text-[13px] text-muted shadow-soft transition peer-checked:bg-forest peer-checked:font-medium peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-leaf';
@endphp

@section('content')
    <x-app-bar title="Detail Menu" :back="route('buyer.katalog')" />

    <form action="{{ route('buyer.keranjang.tambah') }}" method="post" class="flex flex-1 flex-col bg-cream">
        @csrf
        <input type="hidden" name="menu_id" value="{{ $menu['id'] }}" />

        <main class="stagger flex-1 space-y-5 px-5 pb-6 pt-4">
            <div class="relative">
                <x-photo :src="$menu['photo']" label="Foto menu" class="h-[240px] w-full rounded-[28px] shadow-float" />
                <span class="absolute bottom-3 left-3 z-20 inline-flex items-center gap-1 rounded-full bg-white/95 px-3 py-1.5 text-[12px] font-semibold text-ink shadow-float">
                    <span class="text-amber">★</span>
                    {{ $menu['rating'] > 0 ? number_format($menu['rating'], 1) : '-' }}
                    <span class="font-medium text-muted">({{ $menu['reviews'] }} ulasan)</span>
                </span>
            </div>

            <section class="space-y-2.5">
                <h2 class="text-[22px] font-semibold leading-snug text-ink">{{ $menu['name'] }}</h2>
                <div class="flex items-end gap-1.5">
                    <span class="tabular text-heading-xl font-semibold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                    <span class="text-[12px] text-muted">/ porsi</span>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    <x-badge color="dark">Sisa kapasitas {{ $menu['kapasitas'] }}</x-badge>
                    <x-badge color="green">Halal</x-badge>
                    <x-badge color="mint">Min {{ $menu['min'] }} porsi</x-badge>
                </div>
                <p class="text-[13px] leading-relaxed text-muted">{{ $menu['description'] ?: 'Menu katering dari '.$menu['seller'].'.' }}</p>
            </section>

            <section class="space-y-2.5">
                <p class="text-[15px] font-semibold text-ink">Pilih paket</p>
                <div class="flex flex-wrap gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="paket_id" value="" checked class="peer sr-only" />
                        <span class="{{ $chip }}">Reguler Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                    </label>
                    @foreach ($packages as $package)
                        <label class="cursor-pointer">
                            <input type="radio" name="paket_id" value="{{ $package['id'] }}" class="peer sr-only" />
                            <span class="{{ $chip }}">{{ $package['name'] }} Rp {{ number_format($package['price'], 0, ',', '.') }}</span>
                        </label>
                    @endforeach
                </div>
            </section>

            @if (count($extras) > 0)
                <section class="space-y-2.5">
                    <p class="text-[15px] font-semibold text-ink">Opsi tambahan</p>
                    <div class="surface divide-y divide-line/70 px-4">
                        @foreach ($extras as $extra)
                            <label class="flex cursor-pointer items-center gap-3 py-3">
                                <input type="checkbox" name="opsi_ids[]" value="{{ $extra['id'] }}" class="peer sr-only" />
                                <span class="grid size-6 shrink-0 place-items-center rounded-full border-2 border-line bg-white text-[12px] font-bold text-transparent transition peer-checked:border-forest peer-checked:bg-forest peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-leaf">✓</span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-body font-medium text-ink">{{ $extra['name'] }}</span>
                                    <span class="block text-[11px] text-muted">{{ $extra['unit'] }}</span>
                                </span>
                                @if ($extra['price'] > 0)
                                    <span class="tabular shrink-0 text-[13px] font-medium text-forest">+ Rp {{ number_format($extra['price'], 0, ',', '.') }}</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="surface flex items-center justify-between gap-3 p-4">
                <div class="space-y-0.5">
                    <p class="text-body font-semibold text-ink">Jumlah porsi</p>
                    <p class="text-[11px] text-muted">Minimal {{ $menu['min'] }} porsi · Sisa kapasitas {{ $menu['kapasitas'] }}</p>
                </div>
                <div class="flex shrink-0 items-center gap-1 rounded-full bg-mint-faint p-1">
                    <button type="button" class="grid size-9 place-items-center rounded-full bg-white text-forest shadow-soft transition active:scale-95" onclick="this.nextElementSibling.stepDown()" aria-label="Kurangi"><x-icon name="minus" class="size-4" /></button>
                    <input type="number" name="qty" min="{{ $menu['min'] }}" max="{{ max($menu['min'], $menu['kapasitas']) }}" value="{{ $menu['min'] }}" class="tabular w-12 bg-transparent text-center text-body font-semibold text-ink outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none" required />
                    <button type="button" class="grid size-9 place-items-center rounded-full bg-forest text-white transition active:scale-95" onclick="this.previousElementSibling.stepUp()" aria-label="Tambah"><x-icon name="plus" class="size-4" /></button>
                </div>
            </section>

            @if (count($plate) > 0)
                <section class="surface space-y-3 p-4">
                    <p class="text-[15px] font-semibold text-ink">Standar Porsi (Dummy Plate)</p>
                    <x-photo :src="$menu['plate_photo']" :label="$menu['plate_photo'] ? 'Foto piring acuan seller' : 'Belum ada foto piring'" class="h-[110px] w-full rounded-[18px]" />
                    @foreach ($plate as $row)
                        <div class="flex items-start justify-between gap-3 text-[13px]">
                            <span class="shrink-0 text-muted">{{ $row['name'] }}</span>
                            <span class="text-right font-semibold text-ink">{{ $row['value'] }}</span>
                        </div>
                    @endforeach
                </section>
            @endif

            <section class="surface flex items-center gap-3 p-4">
                <span class="grid size-12 shrink-0 place-items-center rounded-full bg-mint-soft text-[15px] font-semibold text-forest">{{ strtoupper(substr($menu['seller'], 0, 2)) }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[15px] font-semibold text-ink">{{ $menu['seller'] }}</p>
                    <p class="text-[12px] text-muted">Sisa kapasitas {{ $menu['kapasitas'] }} porsi</p>
                </div>
                <x-badge color="green" class="shrink-0">Verified</x-badge>
            </section>
        </main>

        <x-action-bar>
            <x-button pill type="submit" variant="outline" class="flex-1 px-3! text-[13px]!">+ Keranjang</x-button>
            <x-button pill type="submit" name="beli" value="1" class="flex-1">Beli sekarang</x-button>
        </x-action-bar>
    </form>
@endsection
