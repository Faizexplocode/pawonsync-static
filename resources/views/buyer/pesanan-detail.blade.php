@extends('layouts.phone')

@section('title', 'Detail Pesanan')

@php
    $pengiriman = $order['pengiriman'];
    $pembayaran = $order['pembayaran'];
    $statusKirim = $pengiriman?->status?->value;
@endphp

@section('content')
    <x-app-bar title="Detail Pesanan" :subtitle="$order['kode']" :back="route('buyer.pesanan')" />

    <main class="stagger flex-1 space-y-4 bg-cream px-5 pb-6 pt-4">
        <section class="relative overflow-hidden rounded-[28px] bg-[linear-gradient(150deg,var(--color-forest-night)_0%,var(--color-forest)_60%,var(--color-leaf)_100%)] p-5 text-white">
            <div class="pointer-events-none absolute -right-8 -top-8 size-40 rounded-full bg-white/5"></div>
            <div class="relative flex items-start gap-3">
                <div class="min-w-0 flex-1 space-y-1">
                    <p class="text-[12px] text-mint/80">Estimasi kedatangan</p>
                    <p class="tabular text-[30px] font-semibold leading-tight">{{ $order['jam'] }} WIB</p>
                    <p class="text-[12px] text-mint/80">{{ $pengiriman ? ($pengiriman->status->label().' · '.$order['courier']) : 'Belum berangkat · Kurir ditugaskan saat dikemas' }}</p>
                </div>
                <span class="relative grid size-14 shrink-0 place-items-center">
                    <span class="absolute inset-0 rounded-full bg-mint/25 animate-ripple"></span>
                    <span class="relative grid size-12 place-items-center rounded-full bg-white/15 animate-float">
                        <x-icon :name="$pengiriman ? 'bike' : 'chef'" class="size-6 text-mint" />
                    </span>
                </span>
            </div>
        </section>

        <section class="surface space-y-2.5 p-4">
            <div class="flex items-start justify-between gap-2">
                <p class="min-w-0 text-[15px] font-semibold leading-snug text-ink">{{ $order['title'] }} - {{ $order['seller'] }}</p>
                <x-badge :color="$order['tone']" class="shrink-0">{{ $order['status'] }}</x-badge>
            </div>
            <p class="text-[12px] leading-relaxed text-muted">{{ $order['meta'] }}, {{ $order['jam'] }}</p>
            <div class="h-px bg-line"></div>
            @foreach ($order['items'] as $item)
                <div class="space-y-0.5">
                    <div class="flex items-start justify-between gap-3 text-[13px]">
                        <span class="text-muted">{{ $item['qty'] }}x {{ $item['name'] }}</span>
                        <span class="tabular shrink-0 font-semibold text-ink">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    @if (count($item['opsi']) > 0)
                        <p class="text-[11px] text-leaf-dark">+ {{ implode(', ', $item['opsi']) }}</p>
                    @endif
                </div>
            @endforeach
        </section>

        <section class="space-y-3">
            <p class="text-[15px] font-semibold text-ink">Status pesanan</p>
            <div class="surface px-4 pb-1 pt-4">
                @foreach ($order['steps'] as [$title, $desc, $done])
                    <x-timeline-item :title="$title" :note="$desc" :done="$done" :last="$loop->last" />
                @endforeach
            </div>
        </section>

        <section class="surface space-y-2.5 p-4">
            <p class="text-[15px] font-semibold text-ink">Pembayaran</p>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Total pesanan</span>
                <span class="tabular font-semibold text-ink">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
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

        <section class="surface flex items-center gap-3 p-4">
            <span class="grid size-12 shrink-0 place-items-center rounded-full bg-mint-soft text-[15px] font-semibold text-forest">{{ strtoupper(substr($order['courier'], 0, 2)) }}</span>
            <div class="min-w-0 flex-1">
                <p class="truncate text-[15px] font-semibold text-ink">{{ $order['courier'] }}</p>
                <p class="text-[12px] text-muted">{{ $pengiriman?->status?->label() ?? 'Menunggu penugasan' }}</p>
            </div>
            @if ($pengiriman)
                <a href="{{ route('buyer.tracking', $order['id']) }}" class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-mint-soft px-4 py-2 text-[12px] font-medium text-forest">
                    <x-icon name="navigation" class="size-3.5" />Lacak
                </a>
            @endif
        </section>

        @if ($order['ulasan'])
            <section class="surface space-y-1.5 p-4">
                <p class="text-[15px] font-semibold text-ink">Ulasanmu</p>
                <p class="text-[18px] leading-none text-amber">{{ str_repeat('★', $order['ulasan']->rating) }}{{ str_repeat('☆', 5 - $order['ulasan']->rating) }}</p>
                <p class="text-[13px] text-muted">{{ $order['ulasan']->komentar ?: 'Tanpa komentar.' }}</p>
            </section>
        @endif

        @if ($order['status_value'] === 'selesai' && ! $order['ulasan'])
            <x-button pill variant="secondary" :href="route('buyer.ulasan', $order['id'])">Beri Ulasan</x-button>
        @endif
    </main>

    @if ($order['status_value'] === 'menunggu_pembayaran')
        <x-action-bar>
            <x-button pill :href="route('buyer.pembayaran', $order['id'])">Selesaikan Pembayaran</x-button>
        </x-action-bar>
    @elseif ($pengiriman && $order['status_value'] !== 'selesai')
        <x-action-bar>
            @if ($statusKirim === 'tiba')
                <x-button pill :href="route('buyer.tiba', $order['id'])">Konfirmasi Penerimaan</x-button>
            @else
                <x-button pill :href="route('buyer.tracking', $order['id'])">Lacak Pengiriman</x-button>
            @endif
        </x-action-bar>
    @endif
@endsection
