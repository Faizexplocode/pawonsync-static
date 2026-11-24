@extends('layouts.phone')

@section('title', 'Katalog Menu')

@php
    $urutLabel = ['populer' => 'Terpopuler', 'murah' => 'Termurah', 'mahal' => 'Termahal'][$urut] ?? 'Terpopuler';
@endphp

@section('content')
    <x-app-bar title="Katalog Menu" :subtitle="count($menus).' menu tersedia'" />

    <main class="flex-1 space-y-3.5 p-4">
        <div class="flex items-center gap-2.5">
            <form action="{{ route('buyer.katalog') }}" method="get" class="flex h-11 flex-1 items-center gap-2.5 rounded-[14px] border border-line bg-white px-3.5">
                <x-icon name="search" class="size-3.5 shrink-0 text-muted" />
                <input type="search" name="q" value="{{ $cari }}" placeholder="Cari menu atau dapur" class="w-full bg-transparent text-[13px] text-ink outline-none placeholder:text-muted" />
                @if ($urut !== 'populer')
                    <input type="hidden" name="urut" value="{{ $urut }}" />
                @endif
            </form>
            <a href="{{ route('buyer.filter', array_filter(['q' => $cari, 'urut' => $urut !== 'populer' ? $urut : null])) }}" class="rounded-[14px] bg-forest px-3.5 py-3 text-caption font-bold text-white">Filter</a>
        </div>

        <div class="flex flex-wrap gap-2">
            <x-chip :active="$cari === ''" :href="route('buyer.katalog', ['urut' => $urut])">Semua</x-chip>
            @foreach ($categories as $category)
                <x-chip :active="strcasecmp($cari, $category['name']) === 0" :href="route('buyer.katalog', ['q' => $category['name'], 'urut' => $urut])">{{ $category['name'] }}</x-chip>
            @endforeach
        </div>

        <div class="flex items-center justify-between text-caption">
            <span class="font-semibold text-leaf-dark">Urutkan: {{ $urutLabel }}</span>
            <span class="font-medium text-muted">
                {{ count($menus) }} hasil
                @if ($cari !== '' || $urut !== 'populer')
                    · <a href="{{ route('buyer.katalog') }}">Reset filter</a>
                @endif
            </span>
        </div>

        @forelse ($menus as $menu)
            <a href="{{ route('buyer.menu', $menu['id']) }}" class="flex gap-3 rounded-[18px] border border-line bg-white p-3">
                <x-photo :src="$menu['photo']" label="Foto" class="size-[92px] shrink-0" />
                <div class="min-w-0 flex-1 space-y-1">
                    <p class="line-clamp-2 text-body font-semibold text-ink">{{ $menu['name'] }}</p>
                    <p class="text-caption text-muted">{{ $menu['seller'] }} · min {{ config('katalog.min_porsi') }} porsi</p>
                    <div class="flex items-center gap-1 text-caption">
                        <span class="text-[13px] font-bold text-amber">★</span>
                        <span class="font-semibold text-ink">{{ $menu['rating'] > 0 ? number_format($menu['rating'], 1) : '-' }}</span>
                        <span class="text-muted">({{ $menu['reviews'] }})</span>
                    </div>
                    <p class="text-body font-bold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
                </div>
            </a>
        @empty
            <x-empty-state title="Menu tidak ditemukan">Tidak ada menu yang cocok dengan pencarian "{{ $cari }}".</x-empty-state>
        @endforelse
    </main>

    <x-bottom-nav role="buyer" active="buyer.katalog" />
@endsection
