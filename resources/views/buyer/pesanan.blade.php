@extends('layouts.phone')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="flex-1 bg-cream">
        <header class="px-5 pb-2 pt-5">
            <p class="text-[12px] text-muted">{{ $jumlahAktif }} pesanan aktif</p>
            <h1 class="text-[20px] font-semibold text-ink">Pesanan saya</h1>
        </header>

        <main class="stagger space-y-4 px-5 pb-6 pt-3">
            <div class="no-scrollbar -mx-5 flex gap-2 overflow-x-auto px-5 py-0.5">
                @foreach (['aktif' => 'Aktif ('.$jumlahAktif.')', 'selesai' => 'Riwayat', 'batal' => 'Dibatalkan'] as $key => $label)
                    <x-chip :active="$tab === $key" :href="route('buyer.pesanan', ['tab' => $key])">{{ $label }}</x-chip>
                @endforeach
            </div>

            @forelse ($orders as $order)
                <a href="{{ route('buyer.pesanan.detail', $order['id']) }}" class="surface block space-y-3 p-4 transition active:scale-[0.99]">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[15px] font-semibold text-ink">{{ $order['title'] }}</p>
                            <p class="text-[11px] text-muted">{{ $order['kode'] }}</p>
                        </div>
                        <x-badge :color="$order['tone']" class="shrink-0">{{ $order['status'] }}</x-badge>
                    </div>
                    <p class="text-[12px] leading-relaxed text-muted">{{ $order['meta'] }}</p>
                    @if ($tab === 'aktif')
                        <x-progress-bar :value="$order['progress'] / $order['steps'] * 100" :tone="$order['tone'] === 'amber' ? 'amber' : 'leaf'" />
                    @endif
                    <div class="flex items-center justify-between gap-2">
                        @if ($tab === 'aktif')
                            <span class="inline-flex items-center gap-1.5 text-[12px] font-medium text-forest"><x-icon name="clock" class="size-4" />{{ $order['eta'] }} · {{ $order['progress'] }}/{{ $order['steps'] }} tahap</span>
                        @else
                            <span class="tabular text-[15px] font-semibold text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                        @endif
                        <span class="grid size-8 shrink-0 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="arrow-right" class="size-4" /></span>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center gap-2 rounded-[24px] bg-white/60 px-6 py-10 text-center">
                    <span class="grid size-14 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="receipt" class="size-6" /></span>
                    <p class="text-[15px] font-semibold text-ink">Belum ada pesanan</p>
                    <p class="text-[13px] text-muted">Tidak ada pesanan pada tab ini.</p>
                </div>
            @endforelse

            <x-button pill variant="secondary" :href="route('buyer.katalog')">Pesan Lagi dari Katalog</x-button>
        </main>
    </div>

    <x-bottom-nav role="buyer" active="buyer.pesanan" />
@endsection
