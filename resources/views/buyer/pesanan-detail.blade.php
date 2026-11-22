@extends('layouts.phone')

@section('title', 'Detail Pesanan')

@php
    $steps = [
        ['Pesanan dikonfirmasi', 'Seller menerima pesanan', true],
        ['Sedang dimasak', 'Dapur menyiapkan 150 porsi', true],
        ['Pengiriman', 'Kurir menuju Graha ITS', false],
        ['Selesai', 'Pesanan diterima di venue', false],
    ];
@endphp

@section('content')
    <x-app-bar title="Detail Pesanan" :subtitle="'Order ID · PS-' . strtoupper($order['id'])" :back="route('buyer.pesanan')" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl bg-forest p-4 text-white">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-semibold text-mint/80">Status pesanan</p>
                <x-badge color="mint">{{ $order['status'] }}</x-badge>
            </div>
            <p class="mt-1 text-[20px] font-extrabold">{{ $order['title'] }}</p>
            <p class="mt-0.5 text-[10px] text-mint/80">{{ $order['meta'] }}</p>
            <div class="mt-3">
                <x-progress-bar :value="$order['progress'] / $order['steps'] * 100" tone="mint" />
                <div class="mt-1.5 flex justify-between text-[10px] font-semibold text-mint/80">
                    <span>{{ $order['progress'] }} dari {{ $order['steps'] }} tahap</span>
                    <span>{{ $order['eta'] }}</span>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Timeline pesanan</p>
            <div class="mt-3 space-y-3">
                @foreach ($steps as [$title, $desc, $done])
                    <div class="flex gap-3">
                        <span @class(['mt-0.5 grid size-5 shrink-0 place-items-center rounded-full text-white', 'bg-leaf' => $done, 'bg-line text-muted' => ! $done])>
                            @if ($done)<x-icon name="check" class="size-3" />@else<span class="size-1.5 rounded-full bg-muted"></span>@endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold {{ $done ? 'text-forest-deep' : 'text-muted' }}">{{ $title }}</p>
                            <p class="text-[9px] leading-relaxed text-muted">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Rincian menu</p>
            <div class="mt-3 space-y-2">
                @foreach ($order['items'] as $item)
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-muted">{{ $item['qty'] }}x {{ $item['name'] }}</span>
                        <span class="font-semibold text-ink">Rp {{ number_format($item['qty'] * $item['price'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex items-center justify-between border-t border-line pt-2 text-[13px] font-extrabold text-forest-deep">
                    <span>Total</span>
                    <span class="text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Kurir</p>
            <div class="mt-2 flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-full bg-mint text-[12px] font-extrabold text-forest">
                    {{ strtoupper(substr($order['courier'], 0, 2)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-ink">{{ $order['courier'] }}</p>
                    <p class="text-[10px] text-muted">{{ $order['vehicle'] }}</p>
                </div>
                <a href="{{ route('buyer.tracking') }}" class="rounded-full bg-mint-soft px-3 py-1.5 text-[10px] font-bold text-forest">Lacak</a>
            </div>
        </section>

        <a href="{{ route('buyer.tracking') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[14px] font-bold text-white">
            <x-icon name="location" class="size-4" />
            Lihat live tracking
        </a>
    </main>
@endsection
