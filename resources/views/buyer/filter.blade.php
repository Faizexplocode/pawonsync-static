@extends('layouts.phone')

@section('title', 'Filter & Urutkan')

@php
    $qAktif = trim((string) request('q', ''));
    $urutAktif = (string) request('urut', 'populer');
    $chip = 'inline-flex items-center rounded-full border border-line bg-white px-3.5 py-2.5 text-caption font-medium text-ink peer-checked:border-forest peer-checked:bg-forest peer-checked:font-semibold peer-checked:text-white';
@endphp

@section('content')
    <x-app-bar title="Katalog Menu" subtitle="Atur kategori dan urutan menu" :back="route('buyer.katalog')" />

    <div class="flex flex-1 flex-col bg-[rgba(5,31,18,0.55)]">
        <form action="{{ route('buyer.katalog') }}" method="get" class="mt-auto space-y-3.5 rounded-t-[26px] bg-white px-4 py-3">
            <div class="flex justify-center"><span class="h-1 w-10 rounded-sm bg-line"></span></div>
            <h2 class="text-[18px] font-bold text-ink">Filter &amp; Urutkan</h2>

            <p class="text-[13px] font-bold text-ink">Kategori</p>
            <div class="flex flex-wrap gap-2">
                <label class="cursor-pointer">
                    <input type="radio" name="q" value="" class="peer sr-only" @checked($qAktif === '') />
                    <span class="{{ $chip }}">Semua</span>
                </label>
                @foreach ($categories as $category)
                    <label class="cursor-pointer">
                        <input type="radio" name="q" value="{{ $category['name'] }}" class="peer sr-only" @checked(strcasecmp($qAktif, $category['name']) === 0) />
                        <span class="{{ $chip }}">{{ $category['name'] }}</span>
                    </label>
                @endforeach
            </div>

            <p class="text-[13px] font-bold text-ink">Urutkan</p>
            <div class="flex flex-wrap gap-2">
                @foreach (['populer' => 'Terpopuler', 'murah' => 'Termurah', 'mahal' => 'Termahal'] as $nilai => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="urut" value="{{ $nilai }}" class="peer sr-only" @checked($urutAktif === $nilai) />
                        <span class="{{ $chip }}">{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <p class="text-caption text-muted">Minimal pemesanan {{ config('katalog.min_porsi') }} porsi per transaksi.</p>

            <div class="flex items-center gap-2.5">
                <x-button variant="outline" :href="route('buyer.katalog')" class="flex-1">Reset</x-button>
                <x-button type="submit" class="flex-1">Terapkan</x-button>
            </div>
        </form>
    </div>
@endsection
