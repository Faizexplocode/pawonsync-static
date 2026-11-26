@extends('layouts.phone')

@section('title', 'Konfirmasi Tiba')

@php
    $bukti = $pengiriman->buktiSerahTerima;
    $sudahTiba = $pengiriman->status->value === 'tiba';
    $selesai = $order['status_value'] === 'selesai';
@endphp

@section('content')
    <x-app-bar :title="$sudahTiba ? 'Pesanan Tiba!' : 'Konfirmasi Penerimaan'" :subtitle="$sudahTiba ? 'Kurir sudah tiba di venue' : 'Kurir dalam perjalanan'" :back="route('buyer.tracking', $order['id'])" />

    <main class="stagger flex-1 space-y-4 bg-cream px-5 pb-6 pt-4">
        <p class="text-body text-ink">Pesanan <span class="font-semibold">{{ $order['title'] }}</span> dari {{ $order['seller'] }} {{ $sudahTiba ? 'sudah tiba di venue.' : 'sedang dalam perjalanan ke venue.' }}</p>

        <x-photo :src="$bukti?->foto_url" :label="$bukti ? 'Foto serah terima diunggah '.$bukti->waktu?->locale('id')->translatedFormat('j M, H.i') : 'Kurir belum mengunggah bukti serah terima'" class="h-[230px] w-full rounded-[28px] shadow-soft" />

        <section class="surface space-y-2.5 p-4">
            <p class="text-[15px] font-semibold text-ink">Bukti serah terima</p>
            @foreach ([
                ['Diserahkan oleh', $order['courier']],
                ['Diterima oleh', $order['buyer']],
                ['Jumlah box', $order['porsi'].' box'],
            ] as [$label, $value])
                <div class="flex items-center justify-between gap-3 text-[13px]">
                    <span class="text-muted">{{ $label }}</span>
                    <span class="text-right font-semibold text-ink">{{ $value }}</span>
                </div>
            @endforeach
        </section>

        <section class="surface space-y-1 p-4">
            <p class="text-[15px] font-semibold text-ink">Periksa kondisi pesanan</p>
            @foreach ([
                ['Jumlah box sesuai ('.$order['porsi'].' box)', true],
                ['Menu dan opsi tambahan sesuai', true],
                ['Kemasan utuh & tidak rusak', true],
                ['Bukti dokumentasi tersedia', $bukti !== null],
            ] as [$label, $done])
                <div class="flex items-center gap-3 py-1.5">
                    <span @class([
                        'grid size-6 shrink-0 place-items-center rounded-full border-2 text-[12px] font-bold',
                        'border-forest bg-forest text-white' => $done,
                        'border-line bg-white text-transparent' => ! $done,
                    ])>✓</span>
                    <span class="text-body text-ink">{{ $label }}</span>
                </div>
            @endforeach
        </section>

        <p class="flex items-start gap-2 px-1 text-[12px] leading-relaxed text-muted">
            <x-icon name="shield" class="mt-0.5 size-4 shrink-0 text-leaf" />
            Dana pesanan diteruskan ke seller setelah kamu konfirmasi.
        </p>
    </main>

    <x-action-bar>
        @if (! $selesai)
            <x-button pill variant="outline" :href="route('buyer.beranda')" class="flex-1">Nanti Saja</x-button>
            <form action="{{ route('buyer.tiba.konfirmasi', $order['id']) }}" method="post" class="flex-[1.5]">
                @csrf
                <x-button pill type="submit" class="px-3!">Konfirmasi Diterima</x-button>
            </form>
        @else
            <x-button pill :href="route('buyer.ulasan', $order['id'])">Beri Ulasan</x-button>
        @endif
    </x-action-bar>
@endsection
