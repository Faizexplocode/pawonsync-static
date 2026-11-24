@extends('layouts.phone')

@section('title', 'Detail Pesanan')

@php
    $pengiriman = $order['pengiriman'];
    $pembayaran = $order['pembayaran'];
    $statusKirim = $pengiriman?->status?->value;
@endphp

@section('content')
    <x-app-bar title="Detail Pesanan" :subtitle="$order['kode']" :back="route('buyer.pesanan')" />

    <main class="flex-1 space-y-3.5 p-4">
        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <div class="flex items-center justify-between gap-2">
                <p class="truncate text-[15px] font-bold text-ink">{{ $order['title'] }} - {{ $order['seller'] }}</p>
                <x-badge :color="$order['tone']" class="shrink-0">{{ $order['status'] }}</x-badge>
            </div>
            <p class="text-caption text-muted">{{ $order['meta'] }}, {{ $order['jam'] }}</p>
            @foreach ($order['items'] as $item)
                <div class="flex items-center justify-between gap-3 text-[13px]">
                    <span class="text-muted">{{ $item['qty'] }}x {{ $item['name'] }}</span>
                    <span class="shrink-0 font-semibold text-ink">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
                @if (count($item['opsi']) > 0)
                    <p class="text-[11px] text-leaf-dark">+ {{ implode(', ', $item['opsi']) }}</p>
                @endif
            @endforeach
        </section>

        <section class="space-y-1 rounded-[20px] bg-[linear-gradient(158deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)] px-[18px] py-4">
            <p class="text-caption font-medium text-mint">Estimasi kedatangan</p>
            <p class="text-[28px] font-bold text-white">{{ $order['jam'] }} WIB</p>
            <p class="text-caption text-mint">{{ $pengiriman ? ($pengiriman->status->label().' · '.$order['courier']) : 'Belum berangkat · Kurir ditugaskan saat dikemas' }}</p>
        </section>

        <p class="text-[15px] font-bold text-ink">Status Pesanan</p>
        <div>
            @foreach ($order['steps'] as [$title, $desc, $done])
                <x-timeline-item :title="$title" :note="$desc" :done="$done" :last="$loop->last" />
            @endforeach
        </div>

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Pembayaran</p>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Total pesanan</span>
                <span class="font-semibold text-ink">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">{{ $pembayaran?->metodeLabel() ?? 'Belum dibayar' }}</span>
                @if ($pembayaran)
                    <x-badge :color="$pembayaran->status->tone()">{{ $pembayaran->status->label() }}</x-badge>
                @else
                    <x-badge color="amber">Menunggu</x-badge>
                @endif
            </div>
            @if ($pembayaran?->waktu_bayar)
                <p class="text-[11px] text-muted">Dibayar {{ $pembayaran->waktu_bayar->locale('id')->translatedFormat('j M Y, H.i') }}</p>
            @endif
        </section>

        <section class="flex items-center gap-3 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <span class="grid size-12 shrink-0 place-items-center rounded-full bg-forest text-[17px] font-bold text-white">{{ strtoupper(substr($order['courier'], 0, 2)) }}</span>
            <div class="min-w-0 flex-1 space-y-0.5">
                <p class="text-[15px] font-bold text-ink">{{ $order['courier'] }}</p>
                <p class="text-caption text-muted">{{ $pengiriman?->status?->label() ?? 'Menunggu penugasan' }}</p>
            </div>
            @if ($pengiriman)
                <a href="{{ route('buyer.tracking', $order['id']) }}" class="text-caption font-semibold text-leaf-dark">Lacak</a>
            @endif
        </section>

        @if ($order['ulasan'])
            <section class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <p class="text-body font-bold text-ink">Ulasanmu</p>
                <p class="text-[15px] leading-none text-amber">{{ str_repeat('★', $order['ulasan']->rating) }}{{ str_repeat('☆', 5 - $order['ulasan']->rating) }}</p>
                <p class="text-[13px] text-muted">{{ $order['ulasan']->komentar ?: 'Tanpa komentar.' }}</p>
            </section>
        @endif

        @if ($order['status_value'] === 'selesai' && ! $order['ulasan'])
            <x-button variant="outline" :href="route('buyer.ulasan', $order['id'])">Beri Ulasan</x-button>
        @endif
    </main>

    @if ($order['status_value'] === 'menunggu_pembayaran')
        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button :href="route('buyer.pembayaran', $order['id'])">Selesaikan Pembayaran</x-button>
        </div>
    @elseif ($pengiriman && $order['status_value'] !== 'selesai')
        <div class="sticky bottom-0 z-20 mt-auto flex items-center gap-2.5 border-t border-line bg-white px-4 py-3">
            @if ($statusKirim === 'tiba')
                <x-button :href="route('buyer.tiba', $order['id'])">Konfirmasi Penerimaan</x-button>
            @else
                <x-button :href="route('buyer.tracking', $order['id'])">Lacak Pengiriman</x-button>
            @endif
        </div>
    @endif
@endsection
