@extends('layouts.phone')

@section('title', 'Katalog Menu')

@php
    $menus = config('demo.menus');
    $categories = config('demo.categories');
@endphp

@section('content')
    <x-app-bar title="Katalog Menu" subtitle="1.248 menu dari 86 dapur">
        <x-slot:right>
            <a href="#" class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-2 text-[11px] font-semibold text-leaf ring-1 ring-line">
                <x-icon name="map" class="size-3.5" />
                Peta
            </a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 px-4 py-4">
        <div class="flex gap-2">
            <div class="flex flex-1 items-center gap-2 rounded-2xl border border-line bg-white px-4 py-3 text-[12px] font-medium text-muted">
                <x-icon name="search" class="size-4" />
                Cari menu atau dapur...
            </div>
            <a href="{{ route('buyer.filter') }}" class="inline-flex items-center gap-1.5 rounded-2xl bg-forest px-3.5 py-3 text-[12px] font-bold text-white">
                <x-icon name="filter" class="size-4" />
                Filter 2
            </a>
        </div>

        <div class="mt-4 flex gap-2 overflow-x-auto pb-1 no-scrollbar">
            <x-chip :active="true">Semua</x-chip>
            @foreach ($categories as $category)
                <x-chip href="{{ route('buyer.filter') }}">{{ $category['name'] }}</x-chip>
            @endforeach
            <x-chip href="{{ route('buyer.filter') }}">Min 50 porsi</x-chip>
        </div>

        <div class="mt-4 flex items-center justify-between text-[10px] font-semibold text-muted">
            <span class="inline-flex items-center gap-1 text-forest">
                Urutkan: Terpopuler
                <x-icon name="chevron-right" class="size-3 rotate-90" />
            </span>
            <a href="{{ route('buyer.filter') }}" class="text-leaf">124 hasil · Reset filter</a>
        </div>

        <div class="mt-3 space-y-3">
            @foreach ($menus as $menu)
                <a href="{{ route('buyer.menu', $menu['slug']) }}" class="flex gap-3 rounded-2xl border border-line bg-white p-3">
                    <x-photo label="Foto" class="size-[92px] shrink-0" />
                    <div class="min-w-0 flex-1">
                        @if (! empty($menu['badge']))
                            <x-badge color="mint">{{ $menu['badge'] }}</x-badge>
                        @endif
                        <p class="mt-1 line-clamp-1 text-[13px] font-extrabold text-ink">{{ $menu['name'] }}</p>
                        <p class="mt-1 line-clamp-1 text-[10px] text-muted">{{ $menu['seller'] }} · {{ $menu['distance'] }} · min {{ $menu['min'] }} porsi</p>
                        <div class="mt-1.5 flex items-center gap-1 text-[10px] text-muted">
                            <span class="text-amber">★</span>
                            <span class="font-semibold text-ink">{{ $menu['rating'] }}</span>
                            <span>({{ $menu['reviews'] }})</span>
                        </div>
                        <div class="mt-1.5 flex items-baseline gap-1.5">
                            <span class="text-[14px] font-extrabold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                            @if (! empty($menu['old_price']))
                                <span class="text-[10px] text-muted line-through">Rp {{ number_format($menu['old_price'], 0, ',', '.') }}</span>
                            @endif
                            <span class="text-[9px] text-muted">/ porsi</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-4 rounded-2xl border border-dashed border-line p-4 text-center text-[11px] text-muted">
            Menampilkan 6 dari 124 menu · <a href="#" class="font-semibold text-leaf">Muat lebih banyak</a>
        </div>
    </main>

    <x-bottom-nav role="buyer" active="buyer.katalog" />
@endsection
