@extends('layouts.phone')

@section('title', 'Pesanan Saya')

@section('content')
    <x-app-bar title="Pesanan Saya" :subtitle="$jumlahAktif.' pesanan aktif'" :back="route('buyer.beranda')" />

    <main class="flex-1 space-y-3.5 p-4">
        <div class="flex">
            @foreach (['aktif' => 'Aktif', 'selesai' => 'Riwayat', 'batal' => 'Dibatalkan'] as $key => $label)
                <a href="{{ route('buyer.pesanan', ['tab' => $key]) }}" class="flex flex-1 flex-col items-center gap-2 py-0.5">
                    <span @class(['text-[13px]', 'font-bold text-forest' => $tab === $key, 'font-medium text-muted' => $tab !== $key])>{{ $label }}{{ $key === 'aktif' ? ' ('.$jumlahAktif.')' : '' }}</span>
                    <span @class(['h-[3px] w-full rounded-sm', 'bg-leaf-dark' => $tab === $key, 'bg-line' => $tab !== $key])></span>
                </a>
            @endforeach
        </div>

        @forelse ($orders as $order)
            <a href="{{ route('buyer.pesanan.detail', $order['id']) }}" class="block space-y-2.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1 space-y-0.5">
                        <p class="truncate text-[15px] font-bold text-ink">{{ $order['title'] }}</p>
                        <p class="text-caption text-muted">{{ $order['meta'] }}</p>
                    </div>
                    <x-badge :color="$order['tone']" class="shrink-0">{{ $order['status'] }}</x-badge>
                </div>
                @if ($tab === 'aktif')
                    <x-progress-bar :value="$order['progress'] / $order['steps'] * 100" :tone="$order['tone'] === 'amber' ? 'amber' : 'leaf'" />
                @endif
                <div class="flex items-center justify-between text-[11px] text-leaf-dark">
                    <span class="font-medium">{{ $tab === 'aktif' ? $order['eta'].' · '.$order['progress'].' dari '.$order['steps'].' tahap' : 'Rp '.number_format($order['total'], 0, ',', '.') }}</span>
                    <span class="font-semibold">Lihat detail ›</span>
                </div>
            </a>
        @empty
            <x-empty-state title="Belum ada pesanan">Tidak ada pesanan pada tab ini.</x-empty-state>
        @endforelse

        <x-button variant="secondary" :href="route('buyer.katalog')">Pesan Lagi dari Katalog</x-button>
    </main>

    <x-bottom-nav role="buyer" active="buyer.pesanan" />
@endsection
