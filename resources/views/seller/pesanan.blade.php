@extends('layouts.phone')

@section('title', 'Pesanan Masuk')

@section('content')
    <x-app-bar title="Pesanan Masuk" :subtitle="$jumlahBaru.' pesanan perlu dikonfirmasi'" :back="route('seller.dashboard')" />

    <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
        <div class="no-scrollbar -mx-4 flex gap-2 overflow-x-auto px-4">
            @foreach (['baru' => 'Baru', 'diproses' => 'Diproses', 'siap' => 'Siap', 'selesai' => 'Selesai', 'batal' => 'Batal'] as $key => $label)
                <a href="{{ route('seller.pesanan', ['tab' => $key]) }}" @class([
                    'shrink-0 rounded-full px-4 py-2 text-[13px] transition',
                    'bg-forest font-medium text-white' => $tab === $key,
                    'bg-white text-muted shadow-soft' => ! ($tab === $key),
                ])>{{ $label }}</a>
            @endforeach
        </div>

        @forelse ($orders as $order)
            <section class="space-y-2 surface px-4 py-3.5">
                <a href="{{ route('seller.pesanan.detail', $order['id']) }}" class="flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1 space-y-px">
                        <p class="truncate text-[15px] font-semibold text-ink">{{ $order['title'] }}</p>
                        <p class="text-[11px] font-medium text-muted">{{ $order['kode'] }}</p>
                    </div>
                    <x-badge :color="$order['tone']" class="shrink-0">{{ $order['status'] }}</x-badge>
                </a>
                <p class="text-caption text-ink">{{ $order['customer'] }}</p>
                <p class="text-caption text-muted">{{ $order['porsi'] }} porsi · antar {{ $order['jam'] }}</p>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between gap-2">
                    <p class="text-body font-semibold text-forest">Rp {{ number_format($order['total'], 0, ',', '.') }}</p>
                    <div class="flex items-center gap-1.5">
                        @if ($order['status_value'] === 'dibayar')
                            <form action="{{ route('seller.pesanan.tolak', $order['id']) }}" method="post">
                                @csrf
                                <button type="submit" class="rounded-full border border-line bg-white px-3.5 py-2.5 text-caption font-medium text-ink">Tolak</button>
                            </form>
                            <form action="{{ route('seller.pesanan.konfirmasi', $order['id']) }}" method="post">
                                @csrf
                                <button type="submit" class="rounded-full bg-forest px-3.5 py-2.5 text-caption font-semibold text-white">Terima &amp; Proses</button>
                            </form>
                        @elseif (in_array($order['status_value'], ['dikonfirmasi', 'diproses'], true))
                            <a href="{{ route('seller.milestone', $order['id']) }}" class="rounded-full bg-forest px-3.5 py-2.5 text-caption font-semibold text-white">Proses</a>
                        @elseif ($order['status_value'] === 'siap_kirim')
                            <a href="{{ route('seller.kurir', $order['id']) }}" class="rounded-full bg-forest px-3.5 py-2.5 text-caption font-semibold text-white">Tugaskan Kurir</a>
                        @else
                            <a href="{{ route('seller.pesanan.detail', $order['id']) }}" class="rounded-full bg-forest px-3.5 py-2.5 text-caption font-semibold text-white">Kelola</a>
                        @endif
                    </div>
                </div>
            </section>
        @empty
            <x-empty-state title="Belum ada pesanan">Tidak ada pesanan pada tab ini.</x-empty-state>
        @endforelse
    </main>

    <x-bottom-nav role="seller" active="seller.pesanan" />
@endsection
