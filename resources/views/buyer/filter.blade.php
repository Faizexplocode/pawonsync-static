@extends('layouts.phone')

@section('title', 'Filter & Urutkan')

@php
    $qAktif = trim((string) request('q', ''));
    $urutAktif = (string) request('urut', 'populer');
    $chip = 'inline-flex items-center rounded-full bg-white px-4 py-2 text-[13px] text-muted shadow-soft transition peer-checked:bg-forest peer-checked:font-medium peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-leaf';
@endphp

@section('content')
    <x-app-bar title="Katalog Menu" subtitle="Atur kategori dan urutan menu" :back="route('buyer.katalog')" />

    <div class="flex flex-1 flex-col bg-[rgba(5,31,18,0.55)]">
        <form action="{{ route('buyer.katalog') }}" method="get" class="mt-auto animate-rise space-y-4 rounded-t-[30px] bg-cream px-5 pb-[max(1.25rem,env(safe-area-inset-bottom))] pt-3">
            <span class="mx-auto block h-1 w-10 rounded-full bg-line"></span>
            <h2 class="text-[20px] font-semibold text-ink">Filter &amp; urutkan</h2>

            <div class="space-y-2.5">
                <p class="text-[15px] font-semibold text-ink">Kategori</p>
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
            </div>

            <div class="space-y-2.5">
                <p class="text-[15px] font-semibold text-ink">Urutkan</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (['populer' => 'Terpopuler', 'murah' => 'Termurah', 'mahal' => 'Termahal'] as $nilai => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="urut" value="{{ $nilai }}" class="peer sr-only" @checked($urutAktif === $nilai) />
                            <span class="{{ $chip }}">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <p class="text-[12px] text-muted">Minimal pemesanan {{ config('katalog.min_porsi') }} porsi per transaksi.</p>

            <div class="flex items-center gap-2.5">
                <x-button pill variant="outline" :href="route('buyer.katalog')" class="flex-1">Reset</x-button>
                <x-button pill type="submit" class="flex-1">Terapkan</x-button>
            </div>
        </form>
    </div>
@endsection
