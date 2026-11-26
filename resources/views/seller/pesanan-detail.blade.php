@extends('layouts.phone')

@section('title', 'Detail Pesanan Seller')

@php
    $pembayaran = $order['pembayaran'];
    $pengiriman = $order['pengiriman'];
    $statusValue = $order['status_value'];
@endphp

@section('content')
    <x-app-bar title="Detail Pesanan" :subtitle="$order['kode']" :back="route('seller.pesanan')" />

    <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
        <section class="space-y-2 surface px-4 py-3.5">
            <div class="flex items-center justify-between gap-2">
                <p class="truncate text-[15px] font-semibold text-ink">{{ $order['title'] }} - {{ $order['buyer'] }}</p>
                <x-badge :color="$order['tone']" class="shrink-0">{{ $order['status'] }}</x-badge>
            </div>
            <p class="text-caption text-muted">{{ $order['porsi'] }} porsi · {{ $order['tanggal'] }}, {{ $order['jam'] }}</p>
        </section>

        <section class="space-y-2 surface px-4 py-3.5">
            <p class="text-[15px] font-semibold text-ink">Alamat pengiriman</p>
            <p class="text-[13px] text-ink">{{ $order['alamat'] }}</p>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Pemesan</span>
                <span class="font-semibold text-ink">{{ $order['buyer'] }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Target tiba</span>
                <span class="font-semibold text-ink">{{ $order['jam'] }} WIB</span>
            </div>
        </section>

        <section class="space-y-2 surface px-4 py-3.5">
            <p class="text-[15px] font-semibold text-ink">Item pesanan</p>
            @foreach ($order['items'] as $item)
                <div class="flex items-center justify-between gap-3 text-[13px]">
                    <span class="text-muted">{{ $item['name'] }} x{{ $item['qty'] }}{{ count($item['opsi']) > 0 ? ' · '.implode(', ', $item['opsi']) : '' }}</span>
                    <span class="shrink-0 font-semibold text-ink">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="h-px bg-line"></div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Total</span>
                <span class="font-semibold text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Pembayaran</span>
                <span class="font-semibold text-ink">{{ $pembayaran?->metodeLabel() ?? 'Belum dibayar' }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Status bayar</span>
                <span @class(['font-semibold', 'text-leaf' => $pembayaran?->status?->value === 'berhasil', 'text-amber' => $pembayaran?->status?->value !== 'berhasil'])>{{ $pembayaran?->status?->label() ?? 'Menunggu' }}</span>
            </div>
        </section>

        <p class="text-[15px] font-semibold text-ink">Update Progres</p>
        <div>
            @foreach ($order['steps'] as [$title, $desc, $done])
                <x-timeline-item :title="$title" :note="$desc" :done="$done" :last="$loop->last" />
            @endforeach
        </div>

        @if (count($order['milestones']) > 0)
            <section class="space-y-1.5 surface px-4 py-3.5">
                @foreach ($order['milestones'] as $milestone)
                    <div class="flex items-center justify-between text-caption">
                        <span class="text-muted">{{ $milestone['label'] }}</span>
                        <span class="font-semibold text-ink">{{ $milestone['waktu'] }}</span>
                    </div>
                @endforeach
            </section>
        @endif

        @if ($pengiriman)
            <section class="space-y-2 surface px-4 py-3.5">
                <p class="text-[15px] font-semibold text-ink">Pengiriman</p>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Kurir</span>
                    <span class="font-semibold text-ink">{{ $order['courier'] }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Status</span>
                    <x-badge :color="$pengiriman->status->tone()">{{ $pengiriman->status->label() }}</x-badge>
                </div>
            </section>
        @endif

        @if ($order['packaging'])
            <section class="space-y-2 surface px-4 py-3.5">
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Progress packaging</span>
                    <span class="font-semibold text-ink">{{ $order['packaging']->jumlah_box }} / {{ $order['porsi'] }} box</span>
                </div>
                <x-progress-bar :value="$order['packaging']->persen()" />
            </section>
        @endif

        @if ($statusValue === 'siap_kirim')
            <a href="{{ route('seller.kurir', $order['id']) }}" class="flex items-center gap-3 surface px-4 py-3.5">
                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-mint-soft text-caption font-semibold text-forest">&gt;</span>
                <span class="min-w-0 flex-1 space-y-0.5">
                    <span class="block text-body font-semibold text-ink">Tugaskan kurir</span>
                    <span class="block text-caption text-muted">Kurir belum boleh berangkat sebelum ditugaskan</span>
                </span>
                <span class="text-caption font-semibold text-leaf-dark">Buka</span>
            </a>
        @endif
    </main>

    @if ($statusValue === 'dibayar')
        <div class="sticky bottom-0 z-20 mt-auto flex items-center gap-2.5 border-t border-line bg-white px-4 py-3">
            <form action="{{ route('seller.pesanan.tolak', $order['id']) }}" method="post" class="flex-1">
                @csrf
                <x-button type="submit" variant="outline">Tolak</x-button>
            </form>
            <form action="{{ route('seller.pesanan.konfirmasi', $order['id']) }}" method="post" class="flex-1">
                @csrf
                <x-button type="submit">Terima &amp; Proses</x-button>
            </form>
        </div>
    @elseif (in_array($statusValue, ['dikonfirmasi', 'diproses'], true))
        <div class="sticky bottom-0 z-20 mt-auto flex items-center gap-2.5 border-t border-line bg-white px-4 py-3">
            <x-button variant="outline" :href="route('seller.packaging', $order['id'])" class="flex-1">Packaging</x-button>
            <x-button :href="route('seller.milestone', $order['id'])" class="flex-1">Update Progres</x-button>
        </div>
    @elseif ($statusValue === 'siap_kirim')
        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button :href="route('seller.kurir', $order['id'])">Tugaskan Kurir</x-button>
        </div>
    @endif
@endsection
