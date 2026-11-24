@extends('layouts.phone')

@section('title', 'Pesanan Dibuat')

@section('content')
    <x-app-bar title="Pesanan Dibuat" />

    <main class="flex-1 space-y-3.5 p-4">
        <div class="flex flex-col items-center gap-2.5 py-2.5 text-center">
            <span class="grid size-[88px] place-items-center rounded-full bg-linear-[127deg] from-forest from-0% to-leaf-soft to-70% text-[40px] font-bold text-white">✓</span>
            <h2 class="text-[22px] font-bold text-ink">Pesanan Berhasil Dibuat!</h2>
            <p class="text-[13px] text-muted">Pembayaran diterima. {{ $order['seller'] }} akan mengonfirmasi pesananmu.</p>
        </div>

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <div class="flex items-center justify-between">
                <p class="text-title font-bold text-ink">{{ $order['kode'] }}</p>
                <x-badge :color="$order['tone']">{{ $order['status'] }}</x-badge>
            </div>
            @foreach ([
                ['Acara', $order['title'].' · '.$order['tanggal'].', '.$order['jam']],
                ['Venue', $order['alamat']],
                ['Porsi', $order['porsi'].' porsi'],
                ['Total', 'Rp '.number_format($order['total'], 0, ',', '.')],
            ] as [$label, $value])
                <div class="flex items-center justify-between gap-3 text-[13px]">
                    <span class="shrink-0 text-muted">{{ $label }}</span>
                    <span class="text-right font-semibold text-ink">{{ $value }}</span>
                </div>
            @endforeach
        </section>

        <p class="text-body font-bold text-ink">Langkah berikutnya</p>
        <div>
            <x-timeline-item title="Pesanan diterima" :note="'Notifikasi terkirim ke '.$order['seller']" :done="true" />
            <x-timeline-item title="Konfirmasi seller" />
            <x-timeline-item title="Pantau progres dapur & foto" note="Dimasak, dikemas, siap berangkat" />
            <x-timeline-item title="Kurir berangkat (Departure Gate)" :last="true" />
        </div>

        <x-button :href="route('buyer.pesanan.detail', $order['id'])">Lihat Detail Pesanan</x-button>
        <x-button variant="outline" :href="route('buyer.beranda')">Kembali ke Beranda</x-button>
    </main>
@endsection
