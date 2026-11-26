@extends('layouts.phone')

@section('title', 'Live Location')

@php
    $berangkat = $pengiriman->waktu_berangkat !== null || in_array($pengiriman->status->value, ['berangkat', 'tiba'], true);
    $tiba = $pengiriman->status->value === 'tiba';
@endphp

@section('content')
    <div class="relative flex flex-1 flex-col bg-cream">
        <x-peta-rute
            :alamat="$task['address']"
            :lokasi-url="$tiba ? null : route('kurir.live.lokasi', $task['id'])"
            :awal="$terakhir ? [(float) $terakhir->latitude, (float) $terakhir->longitude] : null"
            tinggi="h-[58vh] min-h-[360px]"
        >
            <a href="{{ route('kurir.beranda') }}" class="absolute left-4 top-4 z-[500] grid size-11 place-items-center rounded-full bg-white text-ink shadow-float" aria-label="Kembali">
                <x-icon name="chevron-left" class="size-5" />
            </a>
            <div class="absolute right-4 top-4 z-[500] flex items-center gap-1.5 rounded-full bg-forest px-3 py-2 text-[12px] font-medium text-white shadow-float">
                <span class="relative flex size-2"><span class="absolute inset-0 rounded-full bg-mint animate-ripple"></span><span class="relative size-2 rounded-full bg-mint"></span></span>
                Live
            </div>
            <span class="absolute bottom-11 left-4 z-[500] inline-flex max-w-[70%] items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-medium text-forest shadow-float">
                <span class="size-1.5 shrink-0 rounded-full bg-leaf"></span>
                <span class="truncate" data-peta="status">Menyiapkan peta…</span>
            </span>
        </x-peta-rute>

        <section class="relative z-[600] -mt-7 flex-1 space-y-5 rounded-t-[30px] bg-cream px-5 pb-6 pt-3 shadow-[0_-12px_30px_rgba(6,48,31,0.08)]">
            <span class="mx-auto block h-1 w-10 rounded-full bg-line"></span>

            <div class="flex items-end justify-between gap-3">
                <div class="min-w-0 space-y-0.5">
                    <p class="text-[12px] text-muted">Menuju venue</p>
                    <p class="truncate text-[17px] font-semibold text-ink">{{ $task['title'] }}</p>
                </div>
                <div class="shrink-0 text-right">
                    <p class="tabular text-[24px] font-semibold leading-none text-forest" data-peta="eta">{{ $pengiriman->eta_menit ? $pengiriman->eta_menit.' mnt' : '–' }}</p>
                    <p class="tabular mt-1 text-[12px] text-muted" data-peta="jarak">menghitung jarak…</p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-[20px] bg-mint-faint px-4 py-3 text-[12px] text-forest">
                <x-icon name="share" class="size-4 shrink-0" />
                <span class="min-w-0 flex-1">{{ $tiba ? 'Perjalanan selesai, berbagi lokasi dihentikan.' : 'Lokasi dibagikan otomatis ke buyer dan seller.' }}</span>
                @unless ($tiba)
                    <span class="shrink-0 text-muted" data-peta="kirim">{{ $terakhir ? 'Terakhir '.$terakhir->waktu?->format('H.i') : 'Menunggu GPS' }}</span>
                @endunless
            </div>

            <div>
                <x-timeline-item title="Berangkat dari dapur" :note="$task['seller']" :time="$pengiriman->waktu_berangkat?->format('H.i')" :done="$berangkat" />
                <x-timeline-item title="Dalam perjalanan" :note="$task['address']" :done="$berangkat" />
                <x-timeline-item title="Tiba dan serah terima" :time="$task['eta']" :done="$tiba" :last="true" />
            </div>
        </section>

        <div class="sticky bottom-0 z-[700] flex items-center gap-2.5 bg-cream/95 px-5 pb-[max(1rem,env(safe-area-inset-bottom))] pt-3 backdrop-blur">
            <a href="{{ route('kurir.tugas') }}" class="flex-1 rounded-full bg-white py-3.5 text-center text-[14px] font-medium text-ink shadow-soft">Daftar tugas</a>
            <a href="{{ route('kurir.bukti', $task['id']) }}" class="flex-1 rounded-full bg-forest py-3.5 text-center text-[14px] font-semibold text-white">{{ $tiba ? 'Lihat bukti' : 'Sudah tiba' }}</a>
        </div>
    </div>
@endsection
