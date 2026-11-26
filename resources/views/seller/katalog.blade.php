@extends('layouts.phone')

@section('title', 'Kelola Katalog')

@section('content')
    <x-app-bar title="Kelola Katalog" :subtitle="$total.' menu tersedia'" :back="route('seller.dashboard')" />

    <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
        <div class="flex items-center gap-2.5">
            <div class="flex h-11 flex-1 items-center gap-2.5 rounded-[14px] border border-line bg-white px-3.5 text-[13px] text-muted">
                <x-icon name="search" class="size-3.5 shrink-0" />
                <span>{{ $total }} menu · {{ $terjual }} porsi terjual</span>
            </div>
            <a href="{{ route('seller.menu') }}" class="rounded-[14px] bg-forest px-3.5 py-3 text-caption font-bold text-white">+ Tambah</a>
        </div>

        <div class="flex gap-2.5">
            <x-stat-card label="Total menu" :value="$total" sub="aktif" />
            <x-stat-card label="Terjual" :value="$terjual" sub="porsi" />
            <x-stat-card tone="dark" label="Rata-rata" :value="$rating > 0 ? number_format($rating, 1) : '-'" sub="rating" />
        </div>

        @forelse ($menus as $menu)
            <section class="space-y-2.5 surface px-4 py-3.5">
                <div class="flex items-start gap-3">
                    <x-photo :src="$menu['photo']" label="Foto" class="size-[84px] shrink-0 rounded-[14px]" />
                    <div class="min-w-0 flex-1 space-y-1">
                        <p class="line-clamp-2 text-body font-semibold text-ink">{{ $menu['name'] }}</p>
                        <p class="text-body font-semibold text-forest">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
                        <p class="text-[11px] text-muted">min {{ config('katalog.min_porsi') }} porsi · {{ $menu['sold'] }} terjual · {{ $menu['reviews'] }} ulasan</p>
                    </div>
                </div>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between gap-2">
                    <x-badge color="green">Aktif</x-badge>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('buyer.menu', $menu['id']) }}" class="rounded-full border border-line bg-white px-3.5 py-2.5 text-caption font-medium text-ink">Pratinjau</a>
                        <a href="{{ route('seller.kalkulasi', ['menu' => $menu['id']]) }}" class="rounded-full border border-line bg-white px-3.5 py-2.5 text-caption font-medium text-ink">Bahan</a>
                        <a href="{{ route('seller.menu', $menu['id']) }}" class="rounded-full bg-forest px-3.5 py-2.5 text-caption font-semibold text-white">Edit</a>
                    </div>
                </div>
                <form action="{{ route('seller.menu.hapus', $menu['id']) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Hapus menu ini?')" class="w-full text-center text-[11px] font-semibold text-danger">Hapus menu</button>
                </form>
            </section>
        @empty
            <x-empty-state title="Belum ada menu">Tambahkan menu pertamamu.</x-empty-state>
        @endforelse

        <x-button variant="secondary" :href="route('seller.menu')">+ Tambah menu baru</x-button>
    </main>

    <x-bottom-nav role="seller" active="seller.katalog" />
@endsection
