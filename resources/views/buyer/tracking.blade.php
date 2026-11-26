@extends('layouts.phone')

@section('title', 'Live Tracking')

@php
    $statusKirim = $pengiriman->status->value;
    $berangkat = $pengiriman->waktu_berangkat !== null || in_array($statusKirim, ['berangkat', 'dalam_perjalanan', 'tiba'], true);
    $dalamPerjalanan = in_array($statusKirim, ['berangkat', 'dalam_perjalanan', 'tiba'], true);
    $tiba = $statusKirim === 'tiba';
@endphp

@section('content')
    <div class="relative flex flex-1 flex-col bg-cream">
        <x-peta-rute
            :alamat="$order['alamat']"
            :lacak="false"
            :awal="$terakhir ? [(float) $terakhir->latitude, (float) $terakhir->longitude] : null"
            tinggi="h-[50vh] min-h-[320px]"
        >
            <a href="{{ route('buyer.pesanan.detail', $order['id']) }}" class="absolute left-4 top-4 z-[500] grid size-11 place-items-center rounded-full bg-white text-ink shadow-float" aria-label="Kembali">
                <x-icon name="chevron-left" class="size-5" />
            </a>
            <div class="absolute right-4 top-4 z-[500] flex items-center gap-1.5 rounded-full bg-forest px-3 py-2 text-[12px] font-medium text-white shadow-float">
                @if ($dalamPerjalanan && ! $tiba)
                    <span class="relative flex size-2"><span class="absolute inset-0 rounded-full bg-mint animate-ripple"></span><span class="relative size-2 rounded-full bg-mint"></span></span>
                @endif
                {{ $pengiriman->status->label() }}
            </div>
            <span class="absolute bottom-11 left-4 z-[500] inline-flex max-w-[70%] items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-medium text-forest shadow-float">
                <span class="size-1.5 shrink-0 rounded-full bg-leaf"></span>
                <span class="truncate" data-peta="status">{{ $order['seller'] }}</span>
            </span>
        </x-peta-rute>

        <section class="stagger relative z-[600] -mt-7 flex-1 space-y-4 rounded-t-[30px] bg-cream px-5 pb-6 pt-3 shadow-[0_-12px_30px_rgba(6,48,31,0.08)]">
            <span class="mx-auto block h-1 w-10 rounded-full bg-line"></span>

            <div class="flex items-end justify-between gap-3">
                <div class="min-w-0 space-y-0.5">
                    <p class="text-[12px] text-muted">Pengiriman live</p>
                    <h1 class="truncate text-[17px] font-semibold text-ink">{{ $order['title'] }}</h1>
                </div>
                <div class="shrink-0 text-right">
                    <p class="tabular text-[24px] font-semibold leading-none text-forest">{{ $pengiriman->eta_menit ? $pengiriman->eta_menit.' mnt' : '–' }}</p>
                    <p class="tabular mt-1 text-[12px] text-muted" @if ($terakhir && ! $tiba) data-peta="jarak" @endif>Estimasi tiba</p>
                </div>
            </div>

            <x-progress-bar :value="$progress" />

            <div class="flex items-center gap-3 rounded-[20px] bg-mint-faint px-4 py-3 text-[12px] text-forest">
                <x-icon name="location" class="size-4 shrink-0" />
                <span class="min-w-0 flex-1">
                    @if ($terakhir)
                        Lokasi terakhir kurir {{ $terakhir->waktu?->locale('id')->diffForHumans() }} · {{ $lokasi->count() }} titik tercatat
                    @else
                        Kurir belum mengirim titik lokasi.
                    @endif
                </span>
            </div>

            <div class="surface flex items-center gap-3 p-4">
                <span class="grid size-12 shrink-0 place-items-center rounded-full bg-mint-soft text-[15px] font-semibold text-forest">{{ strtoupper(substr($order['courier'], 0, 2)) }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[15px] font-semibold text-ink">{{ $order['courier'] }}</p>
                    <p class="text-[12px] text-muted">{{ $pengiriman->waktu_berangkat ? 'Berangkat '.$pengiriman->waktu_berangkat->locale('id')->translatedFormat('j M, H.i') : 'Belum berangkat' }}</p>
                </div>
                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-mint-faint text-forest"><x-icon name="bike" class="size-5" /></span>
            </div>

            <div>
                <x-timeline-item title="Kurir Berangkat" :note="'Departure Gate dari '.$order['seller']" :time="$pengiriman->waktu_berangkat?->format('H.i')" :done="$berangkat" />
                <x-timeline-item title="Dalam Perjalanan" :note="$terakhir ? 'Titik terakhir '.$terakhir->waktu?->format('H.i') : null" :done="$dalamPerjalanan" />
                <x-timeline-item title="Tiba di Lokasi" :note="$order['alamat']" :time="$order['jam']" :done="$tiba" :last="true" />
            </div>
        </section>

        <x-action-bar class="z-[700]!">
            <x-button pill :href="route('buyer.tiba', $order['id'])">Pesanan sudah tiba? Konfirmasi</x-button>
        </x-action-bar>
    </div>
@endsection
