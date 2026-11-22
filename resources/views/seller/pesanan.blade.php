@extends('layouts.phone')

@section('title', 'Pesanan Masuk')

@php
    $orders = config('demo.seller_orders');
@endphp

@section('content')
    <x-app-bar title="Pesanan Masuk" subtitle="5 pesanan baru hari ini" :back="route('seller.dashboard')" />

    <main class="flex-1 px-4 py-4">
        <div class="flex gap-1 overflow-x-auto rounded-2xl bg-white p-1 ring-1 ring-line no-scrollbar">
            <span class="shrink-0 rounded-xl bg-forest px-4 py-2 text-[11px] font-bold text-white">Baru</span>
            <a href="#" class="shrink-0 rounded-xl px-4 py-2 text-[11px] font-bold text-muted">Diproses</a>
            <a href="#" class="shrink-0 rounded-xl px-4 py-2 text-[11px] font-bold text-muted">Siap Kirim</a>
            <a href="#" class="shrink-0 rounded-xl px-4 py-2 text-[11px] font-bold text-muted">Selesai</a>
        </div>

        <div class="mt-4 space-y-3">
            @foreach ($orders as $order)
                <div class="rounded-2xl border border-line bg-white p-4">
                    <a href="{{ route('seller.pesanan.detail', $order['id']) }}" class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-[13px] font-extrabold text-forest-deep">{{ $order['title'] }}</p>
                            <p class="mt-0.5 text-[10px] text-muted">{{ $order['customer'] }} · {{ $order['detail'] }}</p>
                        </div>
                        <x-badge :color="$order['tone']">{{ $order['status'] }}</x-badge>
                    </a>
                    <div class="mt-3 grid grid-cols-3 gap-2 rounded-xl bg-mist p-2 text-center">
                        <div><p class="text-[9px] text-muted">Porsi</p><p class="text-[11px] font-bold text-forest-deep">150</p></div>
                        <div><p class="text-[9px] text-muted">Antar</p><p class="text-[11px] font-bold text-forest-deep">11.00</p></div>
                        <div><p class="text-[9px] text-muted">Total</p><p class="text-[11px] font-bold text-forest">Rp 5,3 jt</p></div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('seller.pesanan.detail', $order['id']) }}" class="flex-1 rounded-xl border border-line py-2.5 text-center text-[11px] font-bold text-muted">Lihat detail</a>
                        <a href="{{ route('seller.milestone') }}" class="flex-1 rounded-xl bg-forest py-2.5 text-center text-[11px] font-bold text-white">Proses</a>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <x-bottom-nav role="seller" active="seller.pesanan" />
@endsection
