@extends('layouts.phone')

@section('title', 'Pesanan Saya')

@php
    $orders = config('demo.orders');
@endphp

@section('content')
    <x-app-bar title="Pesanan Saya" subtitle="{{ count($orders) }} pesanan aktif" :back="route('buyer.beranda')" />

    <main class="flex-1 px-4 py-4">
        <div class="grid grid-cols-3 gap-1 rounded-2xl bg-white p-1 ring-1 ring-line">
            <span class="rounded-xl bg-forest py-2 text-center text-[11px] font-bold text-white">Aktif</span>
            <a href="#" class="rounded-xl py-2 text-center text-[11px] font-bold text-muted">Selesai</a>
            <a href="#" class="rounded-xl py-2 text-center text-[11px] font-bold text-muted">Dibatalkan</a>
        </div>

        <div class="mt-4 space-y-3">
            @foreach ($orders as $order)
                <a href="{{ route('buyer.pesanan.detail', $order['id']) }}" class="block rounded-2xl border border-line bg-white p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-[13px] font-extrabold text-forest-deep">{{ $order['title'] }}</p>
                            <p class="mt-0.5 text-[10px] text-muted">{{ $order['meta'] }}</p>
                        </div>
                        <x-badge :color="$order['status'] === 'Siap Kirim' ? 'green' : ($order['status'] === 'Menunggu Konfirmasi' ? 'amber' : 'mint')">{{ $order['status'] }}</x-badge>
                    </div>
                    <div class="mt-3">
                        <x-progress-bar :value="$order['progress'] / $order['steps'] * 100" :tone="$order['status'] === 'Menunggu Konfirmasi' ? 'amber' : 'leaf'" />
                        <div class="mt-1.5 flex items-center justify-between text-[10px] font-semibold text-muted">
                            <span>Tahap {{ $order['progress'] }} dari {{ $order['steps'] }}</span>
                            <span class="text-leaf">{{ $order['eta'] }}</span>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-line pt-3">
                        <span class="text-[11px] text-muted">Total</span>
                        <span class="text-[13px] font-extrabold text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </main>

    <x-bottom-nav role="buyer" active="buyer.pesanan" />
@endsection
