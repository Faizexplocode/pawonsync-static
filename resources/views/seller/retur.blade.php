@extends('layouts.phone')

@section('title', 'Retur & Refund')

@section('content')
    <x-app-bar title="Retur & Komplain" subtitle="Belum tersedia di skema saat ini" :back="route('seller.dashboard')" />

    <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
        <div class="no-scrollbar -mx-4 flex gap-2 overflow-x-auto px-4">
            @foreach (['Baru', 'Diproses', 'Selesai'] as $index => $tab)
                <span @class([
                    'shrink-0 rounded-full px-4 py-2 text-[13px] transition',
                    'bg-forest font-medium text-white' => $index === 0,
                    'bg-white text-muted shadow-soft' => ! ($index === 0),
                ])>{{ $tab }}</span>
            @endforeach
        </div>

        <x-empty-state title="Belum ada pengajuan">Pengajuan retur dan refund belum memiliki tabel pada skema saat ini.</x-empty-state>

        <section class="space-y-2 surface px-4 py-3.5">
            <p class="text-[15px] font-semibold text-ink">Jika ada kendala pesanan</p>
            <p class="text-[13px] text-muted">Hubungi buyer melalui kontak pesanan.</p>
            <p class="text-[13px] text-muted">Perbarui milestone atau status pengiriman bila perlu.</p>
            <p class="text-[13px] text-muted">Batalkan pesanan sebelum diproses jika tidak dapat dipenuhi.</p>
        </section>

        <x-button :href="route('seller.pesanan')">Lihat Pesanan Masuk</x-button>
    </main>

    <x-bottom-nav role="seller" active="seller.retur" />
@endsection
