@extends('layouts.phone')

@section('title', 'Promosi')

@section('content')
    <x-app-bar title="Promosi & Diskon" subtitle="Marketing Center" :back="route('seller.dashboard')" />

    <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
        <div class="no-scrollbar -mx-4 flex gap-2 overflow-x-auto px-4">
            @foreach (['Voucher', 'Diskon Produk', 'Flash Sale'] as $index => $tab)
                <span @class([
                    'shrink-0 rounded-full px-4 py-2 text-[13px] transition',
                    'bg-forest font-medium text-white' => $index === 0,
                    'bg-white text-muted shadow-soft' => ! ($index === 0),
                ])>{{ $tab }}</span>
            @endforeach
        </div>

        <x-empty-state title="Promo belum tersedia">Voucher, diskon produk, dan flash sale belum memiliki tabel pada skema saat ini, jadi halaman ini belum bisa menyimpan promo.</x-empty-state>

        <section class="space-y-2 surface px-4 py-3.5">
            <p class="text-[15px] font-semibold text-ink">Yang bisa dilakukan sekarang</p>
            <p class="text-[13px] text-muted">Atur harga menu langsung dari katalog.</p>
            <p class="text-[13px] text-muted">Gunakan kalkulasi bahan untuk menjaga margin.</p>
            <p class="text-[13px] text-muted">Pantau performa penjualan dari dashboard.</p>
        </section>

        <x-button :href="route('seller.katalog')">Kelola Menu</x-button>
    </main>

    <x-bottom-nav role="seller" active="seller.promosi" />
@endsection
