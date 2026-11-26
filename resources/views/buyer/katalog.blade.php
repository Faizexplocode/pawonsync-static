@extends('layouts.phone')

@section('title', 'Katalog Menu')

@php
    $urutLabel = ['populer' => 'Terpopuler', 'murah' => 'Termurah', 'mahal' => 'Termahal'][$urut] ?? 'Terpopuler';
@endphp

@section('content')
    <div class="flex-1 bg-cream">
        <header class="flex items-center gap-3 px-5 pb-2 pt-5">
            <div class="min-w-0 flex-1">
                <p class="text-[12px] text-muted">{{ count($menus) }} menu tersedia</p>
                <h1 class="text-[20px] font-semibold text-ink">Katalog menu</h1>
            </div>
            <a href="{{ route('buyer.keranjang') }}" class="grid size-10 shrink-0 place-items-center rounded-full bg-white text-forest shadow-soft" aria-label="Keranjang">
                <x-icon name="cart" class="size-[18px]" />
            </a>
        </header>

        <main class="stagger space-y-4 px-5 pb-6 pt-3">
            <div class="flex items-center gap-2.5">
                <form action="{{ route('buyer.katalog') }}" method="get" class="flex h-12 min-w-0 flex-1 items-center gap-2.5 rounded-full bg-white px-4 shadow-soft focus-within:ring-2 focus-within:ring-leaf">
                    <x-icon name="search" class="size-4 shrink-0 text-muted" />
                    <input type="search" name="q" value="{{ $cari }}" placeholder="Cari menu atau dapur" class="w-full bg-transparent text-[13px] text-ink outline-none placeholder:text-muted" />
                    @if ($urut !== 'populer')
                        <input type="hidden" name="urut" value="{{ $urut }}" />
                    @endif
                </form>
                <a href="{{ route('buyer.filter', array_filter(['q' => $cari, 'urut' => $urut !== 'populer' ? $urut : null])) }}" class="grid size-12 shrink-0 place-items-center rounded-full bg-forest text-white" aria-label="Filter">
                    <x-icon name="sliders" class="size-5" />
                </a>
            </div>

            <div class="no-scrollbar -mx-5 flex gap-2 overflow-x-auto px-5 py-0.5">
                <x-chip :active="$cari === ''" :href="route('buyer.katalog', ['urut' => $urut])">Semua</x-chip>
                @foreach ($categories as $category)
                    <x-chip :active="strcasecmp($cari, $category['name']) === 0" :href="route('buyer.katalog', ['q' => $category['name'], 'urut' => $urut])">{{ $category['name'] }}</x-chip>
                @endforeach
            </div>

            <div class="flex items-center justify-between text-[12px]">
                <span class="font-medium text-leaf-dark">Urutkan: {{ $urutLabel }}</span>
                <span class="text-muted">
                    {{ count($menus) }} hasil
                    @if ($cari !== '' || $urut !== 'populer')
                        · <a href="{{ route('buyer.katalog') }}" class="font-medium text-leaf-dark">Reset filter</a>
                    @endif
                </span>
            </div>

            <div class="space-y-3">
                @forelse ($menus as $menu)
                    <a href="{{ route('buyer.menu', $menu['id']) }}" class="surface flex gap-3.5 p-3 transition active:scale-[0.99]">
                        <x-photo :src="$menu['photo']" label="Foto" class="size-[96px] shrink-0 rounded-[18px]" />
                        <div class="flex min-w-0 flex-1 flex-col justify-between gap-1 py-0.5">
                            <div class="space-y-0.5">
                                <p class="line-clamp-2 text-body font-semibold leading-snug text-ink">{{ $menu['name'] }}</p>
                                <p class="truncate text-[12px] text-muted">{{ $menu['seller'] }} · min {{ config('katalog.min_porsi') }} porsi</p>
                            </div>
                            <div class="flex items-center gap-1 text-[12px]">
                                <span class="text-amber">★</span>
                                <span class="font-semibold text-ink">{{ $menu['rating'] > 0 ? number_format($menu['rating'], 1) : '-' }}</span>
                                <span class="text-muted">({{ $menu['reviews'] }})</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <p class="tabular text-[15px] font-semibold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
                                <span class="grid size-7 shrink-0 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="plus" class="size-3.5" /></span>
                            </div>
                        </div>
                    </a>
                @empty
                    <x-empty-state title="Menu tidak ditemukan">Tidak ada menu yang cocok dengan pencarian "{{ $cari }}".</x-empty-state>
                @endforelse
            </div>
        </main>
    </div>

    <x-bottom-nav role="buyer" active="buyer.katalog" />
@endsection
