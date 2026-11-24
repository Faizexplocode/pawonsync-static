@extends('layouts.phone')

@section('title', 'Live Tracking')

@php
    $statusKirim = $pengiriman->status->value;
    $berangkat = $pengiriman->waktu_berangkat !== null || in_array($statusKirim, ['berangkat', 'dalam_perjalanan', 'tiba'], true);
    $dalamPerjalanan = in_array($statusKirim, ['berangkat', 'dalam_perjalanan', 'tiba'], true);
    $tiba = $statusKirim === 'tiba';
@endphp

@section('content')
    <x-app-bar title="Pengiriman Live" subtitle="Diperbarui otomatis" :back="route('buyer.pesanan.detail', $order['id'])">
        <x-slot:right>
            <span class="text-caption font-semibold text-leaf-dark">{{ $pengiriman->status->label() }}</span>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-3.5 p-4">
        <section class="relative h-[300px] overflow-hidden rounded-[20px] bg-linear-[132deg] from-mint-soft from-0% to-mint to-70%">
            <div class="absolute inset-0 opacity-60 [background-image:linear-gradient(#c7e6d5_1px,transparent_1px),linear-gradient(90deg,#c7e6d5_1px,transparent_1px)] [background-size:28px_28px]"></div>
            <div class="absolute left-6 top-10 size-3 rounded-full bg-forest ring-4 ring-forest/20"></div>
            <div class="absolute bottom-10 right-8 size-3 rounded-full bg-amber ring-4 ring-amber/20"></div>
            <div class="absolute left-[8%] top-[46%] h-1 w-[80%] -rotate-6 rounded-full bg-leaf/40"></div>
            <div class="absolute left-[42%] top-[38%] grid size-9 -translate-x-1/2 place-items-center rounded-full bg-white text-leaf-dark shadow-lg">
                <x-icon name="bike" class="size-5" />
            </div>
            <span class="absolute bottom-3 left-3 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-forest-deep">{{ $order['title'] }}</span>
            <span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-forest-deep">{{ $order['seller'] }}</span>
        </section>

        <section class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1 space-y-0.5">
                    <p class="text-heading-l font-bold text-forest">{{ $pengiriman->eta_menit ? 'ETA '.$pengiriman->eta_menit.' menit' : 'ETA -' }}</p>
                    <p class="text-caption text-muted">
                        @if ($terakhir)
                            Lokasi terakhir {{ number_format((float) $terakhir->latitude, 5) }}, {{ number_format((float) $terakhir->longitude, 5) }} · {{ $terakhir->waktu?->locale('id')->diffForHumans() }}
                        @else
                            Kurir belum mengirim titik lokasi.
                        @endif
                    </p>
                </div>
                <x-badge color="green" class="shrink-0">{{ $pengiriman->status->label() }}</x-badge>
            </div>
            <x-progress-bar :value="$progress" />
        </section>

        <section class="flex items-center gap-3 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <span class="grid size-12 shrink-0 place-items-center rounded-full bg-forest text-[17px] font-bold text-white">{{ strtoupper(substr($order['courier'], 0, 2)) }}</span>
            <div class="min-w-0 flex-1 space-y-0.5">
                <p class="text-[15px] font-bold text-ink">{{ $order['courier'] }}</p>
                <p class="text-caption text-muted">{{ $pengiriman->waktu_berangkat ? 'Berangkat '.$pengiriman->waktu_berangkat->locale('id')->translatedFormat('j M, H.i') : 'Belum berangkat' }}</p>
            </div>
        </section>

        <p class="text-[15px] font-bold text-ink">Progres Pengiriman</p>
        <div>
            <x-timeline-item title="Kurir Berangkat" :note="'Departure Gate dari '.$order['seller']" :time="$pengiriman->waktu_berangkat?->format('H.i')" :done="$berangkat" />
            <x-timeline-item title="Dalam Perjalanan" :note="$terakhir ? 'Titik terakhir '.$terakhir->waktu?->format('H.i') : null" :done="$dalamPerjalanan" />
            <x-timeline-item title="Tiba di Lokasi" :time="$order['jam']" :done="$tiba" :last="true" />
        </div>

        @if ($lokasi->isNotEmpty())
            <x-badge color="green" class="whitespace-normal">Live location aktif · {{ $lokasi->count() }} titik tercatat</x-badge>
        @endif
    </main>

    <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
        <x-button :href="route('buyer.tiba', $order['id'])">Pesanan sudah tiba? Konfirmasi</x-button>
    </div>
@endsection
