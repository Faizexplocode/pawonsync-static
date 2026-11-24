@extends('layouts.phone')

@section('title', 'Konfirmasi Tiba')

@php
    $bukti = $pengiriman->buktiSerahTerima;
    $sudahTiba = $pengiriman->status->value === 'tiba';
    $selesai = $order['status_value'] === 'selesai';
@endphp

@section('content')
    <x-app-bar title="Pesanan Tiba!" :subtitle="$sudahTiba ? 'Kurir sudah tiba di venue' : 'Kurir dalam perjalanan'" :back="route('buyer.tracking', $order['id'])" />

    <main class="flex-1 space-y-3.5 p-4">
        <p class="text-body font-medium text-ink">Pesanan {{ $order['title'] }} dari {{ $order['seller'] }} {{ $sudahTiba ? 'sudah tiba di venue.' : 'sedang dalam perjalanan ke venue.' }}</p>

        <x-photo :src="$bukti?->foto_url" :label="$bukti ? 'Foto serah terima diunggah '.$bukti->waktu?->locale('id')->translatedFormat('j M, H.i') : 'Kurir belum mengunggah bukti serah terima'" class="h-[230px] w-full rounded-[20px]" />

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Bukti serah terima</p>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Diserahkan oleh</span>
                <span class="font-semibold text-ink">{{ $order['courier'] }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Diterima oleh</span>
                <span class="font-semibold text-ink">{{ $order['buyer'] }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Jumlah box</span>
                <span class="font-semibold text-ink">{{ $order['porsi'] }} box</span>
            </div>
        </section>

        <section class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Periksa kondisi pesanan</p>
            @foreach ([
                ['Jumlah box sesuai ('.$order['porsi'].' box)', true],
                ['Menu dan opsi tambahan sesuai', true],
                ['Kemasan utuh & tidak rusak', true],
                ['Bukti dokumentasi tersedia', $bukti !== null],
            ] as [$label, $done])
                <div class="flex items-center gap-3 py-1">
                    <span @class([
                        'grid size-[22px] shrink-0 place-items-center rounded-[7px] border-2 text-[12px] font-bold',
                        'border-leaf-dark bg-leaf-dark text-white' => $done,
                        'border-line bg-white text-transparent' => ! $done,
                    ])>✓</span>
                    <span class="text-body font-medium text-ink">{{ $label }}</span>
                </div>
            @endforeach
        </section>

        <x-badge color="green" class="whitespace-normal">Dana pesanan diteruskan ke seller setelah kamu konfirmasi</x-badge>
    </main>

    <div class="sticky bottom-0 z-20 mt-auto flex items-center gap-2.5 border-t border-line bg-white px-4 py-3">
        @if (! $selesai)
            <x-button variant="outline" :href="route('buyer.beranda')" class="flex-1">Nanti Saja</x-button>
            <form action="{{ route('buyer.tiba.konfirmasi', $order['id']) }}" method="post" class="flex-1">
                @csrf
                <x-button type="submit" class="text-[12px]">Konfirmasi Diterima</x-button>
            </form>
        @else
            <x-button :href="route('buyer.ulasan', $order['id'])">Beri Ulasan</x-button>
        @endif
    </div>
@endsection
