@extends('layouts.phone')

@section('title', $menu['name'])

@php
    $packages = $menu['packages'] ?? [];
    $extras = $menu['extras'] ?? [];
    $plate = $menu['plate'] ?? [];
    $chip = 'inline-flex items-center rounded-full border border-line bg-white px-3.5 py-2.5 text-caption font-medium text-ink peer-checked:border-forest peer-checked:bg-forest peer-checked:font-semibold peer-checked:text-white';
@endphp

@section('content')
    <x-app-bar title="Detail Menu" :back="route('buyer.katalog')" />

    <form action="{{ route('buyer.keranjang.tambah') }}" method="post" class="flex flex-1 flex-col">
        @csrf
        <input type="hidden" name="menu_id" value="{{ $menu['id'] }}" />

        <main class="flex-1 space-y-3.5 p-4">
            <x-photo :src="$menu['photo']" label="Foto menu" class="h-[230px] w-full rounded-[20px]" />

            <div class="flex flex-wrap gap-1.5">
                <x-badge color="dark">Sisa kapasitas {{ $menu['kapasitas'] }}</x-badge>
                <x-badge color="green">Halal</x-badge>
                <x-badge color="mint">Min {{ $menu['min'] }} porsi</x-badge>
            </div>

            <h2 class="text-[22px] font-bold text-ink">{{ $menu['name'] }}</h2>
            <div class="flex items-center gap-1 text-caption">
                <span class="text-[13px] font-bold text-amber">★</span>
                <span class="font-semibold text-ink">{{ $menu['rating'] > 0 ? number_format($menu['rating'], 1) : '-' }}</span>
                <span class="text-muted">({{ $menu['reviews'] }} ulasan)</span>
            </div>
            <div class="flex items-end gap-2">
                <span class="text-heading-xl font-bold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                <span class="text-caption font-medium text-muted">/ porsi</span>
            </div>
            <p class="text-[13px] leading-[19px] text-muted">{{ $menu['description'] ?: 'Menu katering dari '.$menu['seller'].'.' }}</p>

            <div class="h-px bg-line"></div>

            <p class="text-body font-bold text-ink">Pilih paket</p>
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

            @if (count($extras) > 0)
                <p class="text-body font-bold text-ink">Opsi tambahan</p>
                <div class="space-y-0.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                    @foreach ($extras as $extra)
                        <label class="flex cursor-pointer items-center gap-3 py-1">
                            <input type="checkbox" name="opsi_ids[]" value="{{ $extra['id'] }}" class="peer sr-only" />
                            <span class="grid size-[22px] shrink-0 place-items-center rounded-[7px] border-2 border-line bg-white text-[12px] font-bold text-transparent peer-checked:border-leaf-dark peer-checked:bg-leaf-dark peer-checked:text-white">✓</span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-body font-medium text-ink">{{ $extra['name'] }}{{ $extra['price'] > 0 ? ' + Rp '.number_format($extra['price'], 0, ',', '.') : '' }}</span>
                                <span class="block text-[11px] text-muted">{{ $extra['unit'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <p class="text-body font-bold text-ink">Jumlah porsi</p>
                    <p class="text-[11px] text-muted">Minimal {{ $menu['min'] }} porsi · Sisa kapasitas {{ $menu['kapasitas'] }}</p>
                </div>
                <div class="flex items-center gap-3.5 rounded-full border border-line bg-white px-3 py-1.5">
                    <button type="button" class="text-[16px] font-bold text-forest" onclick="this.nextElementSibling.stepDown()" aria-label="Kurangi">−</button>
                    <input type="number" name="qty" min="{{ $menu['min'] }}" max="{{ max($menu['min'], $menu['kapasitas']) }}" value="{{ $menu['min'] }}" class="w-12 bg-transparent text-center text-body font-semibold text-ink outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none" required />
                    <button type="button" class="text-[16px] font-bold text-forest" onclick="this.previousElementSibling.stepUp()" aria-label="Tambah">+</button>
                </div>
            </div>

            @if (count($plate) > 0)
                <section class="space-y-2.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                    <div class="flex items-center justify-between">
                        <p class="text-body font-bold text-ink">Standar Porsi (Dummy Plate)</p>
                    </div>
                    <x-photo :src="$menu['plate_photo']" :label="$menu['plate_photo'] ? 'Foto piring acuan seller' : 'Belum ada foto piring'" class="h-[100px] w-full rounded-[14px]" />
                    @foreach ($plate as $row)
                        <div class="flex items-start justify-between gap-3 text-[13px]">
                            <span class="shrink-0 text-muted">{{ $row['name'] }}</span>
                            <span class="text-right font-semibold text-ink">{{ $row['value'] }}</span>
                        </div>
                    @endforeach
                </section>
            @endif

            <section class="space-y-2.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <div class="flex items-center gap-3">
                    <span class="grid size-12 shrink-0 place-items-center rounded-full bg-forest text-[17px] font-bold text-white">{{ strtoupper(substr($menu['seller'], 0, 2)) }}</span>
                    <div class="min-w-0 flex-1 space-y-0.5">
                        <p class="text-[15px] font-bold text-ink">{{ $menu['seller'] }}</p>
                        <div class="flex items-center gap-1 text-caption">
                            <span class="text-[13px] font-bold text-amber">★</span>
                            <span class="font-semibold text-ink">{{ $menu['rating'] > 0 ? number_format($menu['rating'], 1) : '-' }}</span>
                            <span class="text-muted">({{ $menu['reviews'] }})</span>
                        </div>
                    </div>
                    <x-badge color="green">Verified</x-badge>
                </div>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between text-center">
                    <div class="flex-1 space-y-0.5">
                        <p class="text-body font-bold text-forest">{{ $menu['kapasitas'] }} porsi</p>
                        <p class="text-[10px] font-medium text-muted">Sisa kapasitas</p>
                    </div>
                    <div class="flex-1 space-y-0.5">
                        <p class="text-body font-bold text-forest">{{ $menu['reviews'] }}</p>
                        <p class="text-[10px] font-medium text-muted">Ulasan</p>
                    </div>
                </div>
            </section>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto flex items-center gap-2.5 border-t border-line bg-white px-4 py-3">
            <x-button type="submit" variant="outline" class="flex-1">Tambah ke Keranjang</x-button>
            <x-button type="submit" name="beli" value="1" class="flex-1">Beli Sekarang</x-button>
        </div>
    </form>
@endsection
