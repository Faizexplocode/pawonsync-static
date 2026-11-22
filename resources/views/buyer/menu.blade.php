@extends('layouts.phone')

@section('title', $menu['name'])

@php
    $packages = $menu['packages'] ?? [
        ['name' => 'Paket Standar', 'note' => 'Porsi reguler', 'active' => true],
        ['name' => 'Paket Plus', 'note' => 'Tambah minuman', 'active' => false],
        ['name' => 'Paket Premium', 'note' => 'Tambah dessert', 'active' => false],
    ];
    $extras = $menu['extras'] ?? [
        ['name' => 'Es teh manis', 'price' => 4000, 'unit' => 'Per gelas', 'checked' => true],
        ['name' => 'Kemasan eco-friendly', 'price' => 1500, 'unit' => 'Per box', 'checked' => false],
        ['name' => 'Sambal terpisah', 'price' => 0, 'unit' => 'Gratis', 'checked' => true],
    ];
    $plate = $menu['plate'] ?? [
        ['name' => 'Nasi', 'value' => '150 g'],
        ['name' => 'Lauk utama', 'value' => '180 g'],
        ['name' => 'Sayur', 'value' => '80 g'],
        ['name' => 'Sambal', 'value' => '30 g'],
        ['name' => 'Total gramasi', 'value' => '440 g'],
    ];
@endphp

@section('content')
    <x-app-bar title="Detail Menu" :back="route('buyer.katalog')">
        <x-slot:right>
            <a href="#" class="grid size-9 place-items-center rounded-full bg-white text-forest-deep ring-1 ring-line" aria-label="Bagikan">
                <x-icon name="share" class="size-4" />
            </a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 pb-28 pt-4">
        <section>
            <x-photo label="Galeri foto menu (1/5)" class="h-[230px] w-full rounded-3xl" />
            <div class="mt-2 grid grid-cols-4 gap-2">
                @foreach ([1, 2, 3] as $i)
                    <x-photo :label="'Foto '" class="h-16" />
                @endforeach
                <x-photo label="+3" class="h-16" />
            </div>
        </section>

        <div class="flex flex-wrap gap-1.5">
            <x-badge color="dark">Best Seller</x-badge>
            <x-badge color="green">Halal</x-badge>
            <x-badge color="mint">Min {{ $menu['min'] }} porsi</x-badge>
        </div>

        <section>
            <h2 class="text-[22px] font-extrabold leading-tight tracking-tight text-forest-deep">{{ $menu['name'] }}</h2>
            <div class="mt-1 flex items-center gap-1.5 text-[11px] text-muted">
                <span class="text-amber">★</span>
                <span class="font-bold text-ink">{{ $menu['rating'] }}</span>
                <span>({{ $menu['reviews'] }} ulasan)</span>
                <span>· Terjual {{ $menu['sold'] }}</span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-[26px] font-extrabold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                @if (! empty($menu['old_price']))
                    <span class="text-[13px] text-muted line-through">Rp {{ number_format($menu['old_price'], 0, ',', '.') }}</span>
                @endif
                <span class="text-[11px] text-muted">/ porsi</span>
            </div>
            <p class="mt-2 text-[12px] leading-relaxed text-muted">{{ $menu['description'] }}</p>
        </section>

        <div class="h-px bg-line"></div>

        <section>
            <p class="mb-2 text-[13px] font-bold text-forest-deep">Pilih paket</p>
            <div class="space-y-2">
                @foreach ($packages as $package)
                    <label @class(['flex cursor-pointer items-center justify-between gap-3 rounded-2xl border px-3 py-3', 'border-leaf bg-mint-soft' => $package['active'], 'border-line bg-white' => ! $package['active']])>
                        <span>
                            <span class="block text-[12px] font-bold text-ink">{{ $package['name'] }}</span>
                            <span class="block text-[10px] text-muted">{{ $package['note'] }}</span>
                        </span>
                        <span @class(['grid size-5 place-items-center rounded-full border-2', 'border-leaf bg-leaf text-white' => $package['active'], 'border-line' => ! $package['active']])>
                            @if ($package['active'])<x-icon name="check" class="size-3" />@endif
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <section>
            <p class="mb-2 text-[13px] font-bold text-forest-deep">Opsi tambahan</p>
            <div class="divide-y divide-line rounded-2xl border border-line bg-white">
                @foreach ($extras as $extra)
                    <label class="flex cursor-pointer items-center gap-3 px-3 py-3">
                        <span @class(['grid size-5 shrink-0 place-items-center rounded-md border-2', 'border-leaf bg-leaf text-white' => $extra['checked'], 'border-line' => ! $extra['checked']])>
                            @if ($extra['checked'])<x-icon name="check" class="size-3" />@endif
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[12px] font-semibold text-ink">{{ $extra['name'] }}{{ $extra['price'] > 0 ? ' + Rp ' . number_format($extra['price'], 0, ',', '.') : '' }}</span>
                            <span class="block text-[10px] text-muted">{{ $extra['unit'] }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="flex items-center justify-between gap-3 rounded-2xl border border-line bg-white p-3">
            <div>
                <p class="text-[13px] font-bold text-forest-deep">Jumlah porsi</p>
                <p class="text-[10px] text-muted">Minimal {{ $menu['min'] }} porsi · Sisa kapasitas 180</p>
            </div>
            <x-qty :value="$menu['min']" />
        </section>

        <section class="rounded-2xl border border-line bg-white p-3">
            <div class="flex items-center justify-between">
                <p class="text-[13px] font-bold text-forest-deep">Standar Porsi (Dummy Plate)</p>
                <x-badge color="green">Acuan</x-badge>
            </div>
            <x-photo label="Foto piring acuan seller" class="mt-2 h-28 w-full" />
            <div class="mt-3 divide-y divide-line">
                @foreach ($plate as $row)
                    <div class="flex items-center justify-between py-2 text-[11px]">
                        <span class="{{ $row['name'] === 'Total gramasi' ? 'font-bold text-forest-deep' : 'text-muted' }}">{{ $row['name'] }}</span>
                        <span class="{{ $row['name'] === 'Total gramasi' ? 'font-bold text-forest' : 'font-semibold text-ink' }}">{{ $row['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-3">
            <div class="flex items-center gap-3">
                <span class="grid size-11 place-items-center rounded-full bg-mint text-[13px] font-extrabold text-forest">PL</span>
                <div class="min-w-0 flex-1">
                    <p class="text-[13px] font-bold text-forest-deep">{{ $menu['seller'] }}</p>
                    <p class="text-[10px] text-muted">{{ $menu['distance'] }} · Aktif 5 mnt lalu</p>
                    <p class="mt-0.5 text-[10px] font-semibold text-amber">★ {{ $menu['rating'] }} · (1,8 rb)</p>
                </div>
                <x-badge color="green">Buka</x-badge>
            </div>
            <div class="mt-3 grid grid-cols-3 gap-2 border-t border-line pt-3 text-center">
                <div><p class="text-[13px] font-extrabold text-forest-deep">180</p><p class="text-[9px] text-muted">Kapasitas</p></div>
                <div><p class="text-[13px] font-extrabold text-forest-deep">&lt; 10 mnt</p><p class="text-[9px] text-muted">Respon</p></div>
                <div><p class="text-[13px] font-extrabold text-forest-deep">96%</p><p class="text-[9px] text-muted">Tepat waktu</p></div>
            </div>
        </section>
    </main>

    <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white/95 px-4 py-3 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <div class="min-w-0 flex-1">
                <p class="text-[9px] font-semibold text-muted">Total estimasi</p>
                <p class="text-[16px] font-extrabold text-forest">Rp {{ number_format($menu['price'] * $menu['min'], 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('buyer.keranjang') }}" class="inline-flex items-center gap-2 rounded-2xl bg-forest px-5 py-3.5 text-[13px] font-bold text-white shadow-lg shadow-forest/20">
                <x-icon name="cart" class="size-4" />
                Tambah ke keranjang
            </a>
        </div>
    </div>
@endsection
