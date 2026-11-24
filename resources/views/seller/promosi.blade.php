@extends('layouts.phone')

@section('title', 'Promosi')

@section('content')
    <x-app-bar title="Promosi & Diskon" subtitle="Marketing Center" :back="route('seller.dashboard')" />

    <main class="flex-1 space-y-3.5 p-4">
        <div class="flex">
            @foreach (['Voucher', 'Diskon Produk', 'Flash Sale'] as $index => $tab)
                <span class="flex flex-1 flex-col items-center gap-2 py-0.5">
                    <span @class(['text-[13px]', 'font-bold text-forest' => $index === 0, 'font-medium text-muted' => $index !== 0])>{{ $tab }}</span>
                    <span @class(['h-[3px] w-full rounded-sm', 'bg-leaf-dark' => $index === 0, 'bg-line' => $index !== 0])></span>
                </span>
            @endforeach
        </div>

        <x-empty-state title="Promo belum tersedia">Voucher, diskon produk, dan flash sale belum memiliki tabel pada skema saat ini, jadi halaman ini belum bisa menyimpan promo.</x-empty-state>

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Yang bisa dilakukan sekarang</p>
            <p class="text-[13px] text-muted">Atur harga menu langsung dari katalog.</p>
            <p class="text-[13px] text-muted">Gunakan kalkulasi bahan untuk menjaga margin.</p>
            <p class="text-[13px] text-muted">Pantau performa penjualan dari dashboard.</p>
        </section>

        <x-button :href="route('seller.katalog')">Kelola Menu</x-button>
    </main>

    <x-bottom-nav role="seller" active="seller.promosi" />
@endsection
