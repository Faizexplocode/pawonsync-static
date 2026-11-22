@extends('layouts.phone')

@section('title', 'Kelola Katalog')

@php
    $menus = config('demo.menus');
    $statuses = ['Aktif', 'Aktif', 'Aktif', 'Aktif', 'Draft', 'Habis'];
@endphp

@section('content')
    <x-app-bar title="Kelola Katalog" subtitle="6 menu · 4 aktif" :back="route('seller.dashboard')">
        <x-slot:right>
            <a href="{{ route('seller.menu') }}" class="grid size-9 place-items-center rounded-full bg-forest text-white" aria-label="Tambah menu">
                <x-icon name="plus" class="size-4" />
            </a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 px-4 py-4">
        <div class="grid grid-cols-3 gap-2">
            <x-stat-card label="Total menu" value="6" sub="2 draft" />
            <x-stat-card label="Terjual" value="1,2 rb" sub="bulan ini" />
            <x-stat-card label="Rata-rata" value="4.8" sub="rating" tone="dark" />
        </div>

        <div class="mt-4 flex gap-1 overflow-x-auto rounded-2xl bg-white p-1 ring-1 ring-line no-scrollbar">
            <span class="shrink-0 rounded-xl bg-forest px-4 py-2 text-[11px] font-bold text-white">Semua</span>
            <a href="#" class="shrink-0 rounded-xl px-4 py-2 text-[11px] font-bold text-muted">Aktif</a>
            <a href="#" class="shrink-0 rounded-xl px-4 py-2 text-[11px] font-bold text-muted">Draft</a>
            <a href="#" class="shrink-0 rounded-xl px-4 py-2 text-[11px] font-bold text-muted">Habis</a>
        </div>

        <div class="mt-4 space-y-3">
            @foreach ($menus as $index => $menu)
                @php($status = $statuses[$index] ?? 'Aktif')
                <div class="rounded-2xl border border-line bg-white p-3">
                    <div class="flex gap-3">
                        <x-photo label="Foto" class="size-[72px] shrink-0" />
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <p class="line-clamp-1 text-[12px] font-extrabold text-ink">{{ $menu['name'] }}</p>
                                <x-badge :color="$status === 'Aktif' ? 'green' : ($status === 'Draft' ? 'amber' : 'gray')">{{ $status }}</x-badge>
                            </div>
                            <p class="mt-0.5 text-[9px] text-muted">{{ $menu['category'] }} · min {{ $menu['min'] }} porsi</p>
                            <div class="mt-1.5 flex items-center gap-2 text-[10px]">
                                <span class="font-extrabold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                                <span class="text-muted">· {{ $menu['sold'] }} terjual</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-1.5 border-t border-line pt-2.5">
                        <a href="{{ route('buyer.menu', $menu['slug']) }}" class="flex-1 rounded-lg border border-line py-2 text-center text-[10px] font-bold text-muted">Pratinjau</a>
                        <a href="{{ route('seller.menu') }}" class="flex-1 rounded-lg border border-line py-2 text-center text-[10px] font-bold text-muted">Edit</a>
                        <a href="{{ route('seller.kalkulasi') }}" class="flex-1 rounded-lg bg-forest py-2 text-center text-[10px] font-bold text-white">Hitung bahan</a>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="{{ route('seller.menu') }}" class="mt-4 flex items-center justify-center gap-2 rounded-2xl border border-dashed border-leaf bg-mint-soft px-4 py-3.5 text-[12px] font-bold text-forest">
            <x-icon name="plus" class="size-4" />
            Tambah menu baru
        </a>
    </main>

    <x-bottom-nav role="seller" active="seller.katalog" />
@endsection
