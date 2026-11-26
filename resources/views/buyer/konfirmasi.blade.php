@extends('layouts.phone')

@section('title', 'Pesanan Dibuat')

@section('content')
    <main class="stagger flex-1 space-y-5 bg-cream px-5 pb-6 pt-8">
        <div class="flex flex-col items-center gap-3 text-center">
            <span class="relative grid size-28 place-items-center">
                <span class="absolute inset-3 rounded-full bg-mint animate-ripple"></span>
                <span class="relative grid size-20 place-items-center rounded-full bg-forest text-mint shadow-float"><x-icon name="check" class="size-10" /></span>
            </span>
            <h1 class="text-[22px] font-semibold text-ink">Pesanan berhasil dibuat!</h1>
            <p class="text-[13px] text-muted">Pembayaran diterima. {{ $order['seller'] }} akan mengonfirmasi pesananmu.</p>
        </div>

        <section class="surface space-y-2.5 p-4">
            <div class="flex items-center justify-between">
                <p class="text-title font-semibold text-ink">{{ $order['kode'] }}</p>
                <x-badge :color="$order['tone']">{{ $order['status'] }}</x-badge>
            </div>
            <div class="h-px bg-line"></div>
            @foreach ([
                ['Acara', $order['title'].' · '.$order['tanggal'].', '.$order['jam']],
                ['Venue', $order['alamat']],
                ['Porsi', $order['porsi'].' porsi'],
                ['Total', 'Rp '.number_format($order['total'], 0, ',', '.')],
            ] as [$label, $value])
                <div class="flex items-start justify-between gap-3 text-[13px]">
                    <span class="shrink-0 text-muted">{{ $label }}</span>
                    <span class="text-right font-semibold text-ink">{{ $value }}</span>
                </div>
            @endforeach
        </section>

        <section class="space-y-3">
            <p class="text-[15px] font-semibold text-ink">Langkah berikutnya</p>
            <div class="surface px-4 pb-1 pt-4">
                <x-timeline-item title="Pesanan diterima" :note="'Notifikasi terkirim ke '.$order['seller']" :done="true" />
                <x-timeline-item title="Konfirmasi seller" />
                <x-timeline-item title="Pantau progres dapur & foto" note="Dimasak, dikemas, siap berangkat" />
                <x-timeline-item title="Kurir berangkat (Departure Gate)" :last="true" />
            </div>
        </section>

        <div class="space-y-2.5">
            <x-button pill :href="route('buyer.pesanan.detail', $order['id'])">Lihat Detail Pesanan</x-button>
            <x-button pill variant="outline" :href="route('buyer.beranda')">Kembali ke Beranda</x-button>
        </div>
    </main>
@endsection
