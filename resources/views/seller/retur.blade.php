@extends('layouts.phone')

@section('title', 'Retur & Refund')

@section('content')
    <x-app-bar title="Retur & Komplain" subtitle="Belum tersedia di skema saat ini" :back="route('seller.dashboard')" />

    <main class="flex-1 space-y-3.5 p-4">
        <div class="flex">
            @foreach (['Baru', 'Diproses', 'Selesai'] as $index => $tab)
                <span class="flex flex-1 flex-col items-center gap-2 py-0.5">
                    <span @class(['text-[13px]', 'font-bold text-forest' => $index === 0, 'font-medium text-muted' => $index !== 0])>{{ $tab }}</span>
                    <span @class(['h-[3px] w-full rounded-sm', 'bg-leaf-dark' => $index === 0, 'bg-line' => $index !== 0])></span>
                </span>
            @endforeach
        </div>

        <x-empty-state title="Belum ada pengajuan">Pengajuan retur dan refund belum memiliki tabel pada skema saat ini.</x-empty-state>

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Jika ada kendala pesanan</p>
            <p class="text-[13px] text-muted">Hubungi buyer melalui kontak pesanan.</p>
            <p class="text-[13px] text-muted">Perbarui milestone atau status pengiriman bila perlu.</p>
            <p class="text-[13px] text-muted">Batalkan pesanan sebelum diproses jika tidak dapat dipenuhi.</p>
        </section>

        <x-button :href="route('seller.pesanan')">Lihat Pesanan Masuk</x-button>
    </main>

    <x-bottom-nav role="seller" active="seller.retur" />
@endsection
