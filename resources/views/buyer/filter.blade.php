@extends('layouts.phone')

@section('title', 'Filter & Urutkan')

@section('content')
    <x-app-bar title="Filter & Urutkan" subtitle="124 menu ditemukan" :back="route('buyer.katalog')" />

    <main class="flex-1 space-y-5 px-4 py-4">
        <section>
            <p class="mb-2 text-[12px] font-bold text-forest-deep">Kategori</p>
            <div class="flex flex-wrap gap-2">
                <x-chip href="#">Semua</x-chip>
                <x-chip :active="true" href="#">Nasi Box</x-chip>
                <x-chip :active="true" href="#">Prasmanan</x-chip>
                <x-chip href="#">Snack</x-chip>
                <x-chip href="#">Tumpeng</x-chip>
                <x-chip href="#">Dessert</x-chip>
            </div>
        </section>

        <section>
            <div class="mb-3 flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Rentang harga per porsi</p>
                <span class="text-[10px] font-semibold text-leaf">Rp 20rb – Rp 120rb</span>
            </div>
            <div class="relative h-6">
                <div class="absolute inset-x-0 top-2.5 h-1.5 rounded-full bg-line"></div>
                <div class="absolute left-[17%] right-[25%] top-2.5 h-1.5 rounded-full bg-leaf"></div>
                <span class="absolute left-[17%] top-0 size-5 -translate-x-1/2 rounded-full border-4 border-leaf bg-white shadow"></span>
                <span class="absolute right-[25%] top-0 size-5 translate-x-1/2 rounded-full border-4 border-leaf bg-white shadow"></span>
            </div>
            <div class="mt-1 flex justify-between text-[10px] font-semibold text-muted">
                <span>Rp 20.000</span>
                <span>Rp 120.000</span>
            </div>
        </section>

        <section>
            <p class="mb-2 text-[12px] font-bold text-forest-deep">Rating minimum</p>
            <div class="flex flex-wrap gap-2">
                <x-chip href="#">Semua</x-chip>
                <x-chip href="#">4.0+</x-chip>
                <x-chip :active="true" href="#">4.5+</x-chip>
                <x-chip href="#">4.8+</x-chip>
            </div>
        </section>

        <section>
            <p class="mb-2 text-[12px] font-bold text-forest-deep">Lokasi dapur</p>
            <div class="flex flex-wrap gap-2">
                <x-chip href="#">Semua</x-chip>
                <x-chip :active="true" href="#">Surabaya Timur</x-chip>
                <x-chip href="#">Surabaya Pusat</x-chip>
                <x-chip href="#">Rungkut &amp; sekitarnya</x-chip>
            </div>
        </section>

        <section>
            <p class="mb-2 text-[12px] font-bold text-forest-deep">Minimal porsi</p>
            <div class="flex flex-wrap gap-2">
                <x-chip :active="true" href="#">&lt; 30 porsi</x-chip>
                <x-chip href="#">30–50</x-chip>
                <x-chip href="#">50–100</x-chip>
                <x-chip href="#">100+</x-chip>
            </div>
        </section>

        <section>
            <x-field label="Catatan tambahan" placeholder="Contoh: tanpa sambal, kemasan dipisah..." icon="edit" />
        </section>

        <div class="grid grid-cols-2 gap-2 pt-1">
            <a href="{{ route('buyer.katalog') }}" class="flex items-center justify-center rounded-2xl border border-line bg-white px-4 py-3.5 text-[13px] font-bold text-forest-deep">Reset</a>
            <a href="{{ route('buyer.katalog') }}" class="flex items-center justify-center gap-1.5 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
                Terapkan
                <x-icon name="arrow-right" class="size-4" />
            </a>
        </div>
    </main>
@endsection
