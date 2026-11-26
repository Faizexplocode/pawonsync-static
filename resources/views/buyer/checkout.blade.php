@extends('layouts.phone')

@section('title', 'Checkout')

@php
    $kolom = 'w-full min-w-0 rounded-2xl border border-line bg-white px-3.5 py-[13px] text-body text-ink outline-none transition focus:border-leaf focus:ring-2 focus:ring-leaf/20';
@endphp

@section('content')
    <x-app-bar title="Checkout" subtitle="Form pemesanan katering" :back="route('buyer.keranjang')" />

    <form action="{{ route('buyer.checkout.store') }}" method="post" class="flex flex-1 flex-col bg-cream">
        @csrf

        <main class="stagger flex-1 space-y-4 px-5 pb-6 pt-4">
            <section class="surface space-y-3 p-4">
                <div class="flex items-center gap-3">
                    <span class="grid size-10 shrink-0 place-items-center rounded-full bg-mint-faint text-forest"><x-icon name="location" class="size-[18px]" /></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-body font-semibold text-ink">{{ $buyer->nama }}</p>
                        <p class="truncate text-[12px] text-muted">{{ $buyer->email }}</p>
                    </div>
                    <x-badge color="dark" class="shrink-0">Pemesan</x-badge>
                </div>
                <label class="block">
                    <span class="mb-1.5 block text-[12px] font-medium text-muted">Alamat venue</span>
                    <textarea name="alamat_venue" rows="2" required placeholder="Contoh: Graha ITS, Kampus ITS Sukolilo, Surabaya" class="{{ $kolom }}">{{ old('alamat_venue', $alamat) }}</textarea>
                </label>
            </section>

            <section class="surface space-y-3 p-4">
                <p class="text-[15px] font-semibold text-ink">Jadwal acara</p>
                <div class="grid grid-cols-2 gap-3">
                    <label class="block">
                        <span class="mb-1.5 block text-[12px] font-medium text-muted">Tanggal acara</span>
                        <input type="date" name="tanggal_acara" value="{{ old('tanggal_acara', $tanggal) }}" min="{{ now()->toDateString() }}" required class="{{ $kolom }}" />
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-[12px] font-medium text-muted">Jam antar</span>
                        <input type="time" name="waktu_acara" value="{{ old('waktu_acara', $jam) }}" required class="{{ $kolom }}" />
                    </label>
                </div>
                <p class="flex items-center gap-2 text-[12px] text-forest"><x-icon name="shield" class="size-4 shrink-0" />Kapasitas dapur dicek otomatis pada tanggal acara</p>
            </section>

            <section class="surface space-y-3 p-4">
                <div class="flex items-center justify-between">
                    <p class="text-[15px] font-semibold text-ink">Pesanan Anda</p>
                    <a href="{{ route('buyer.keranjang') }}" class="text-[12px] font-medium text-leaf-dark">Ubah</a>
                </div>
                @foreach ($items as $item)
                    <div class="flex items-center gap-3">
                        <x-photo :src="$item['photo']" label="" class="size-12 shrink-0 rounded-2xl" />
                        <div class="min-w-0 flex-1 space-y-px">
                            <p class="truncate text-[13px] font-semibold text-ink">{{ $item['menu_nama'] }} x{{ $item['qty'] }}</p>
                            <p class="truncate text-[11px] text-muted">{{ collect([$item['seller'], $item['paket_nama'], ...$item['opsi_nama']])->filter()->implode(' · ') }}</p>
                        </div>
                        <p class="tabular shrink-0 text-[13px] font-semibold text-forest">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Subtotal ({{ $porsi }} porsi)</span>
                    <span class="tabular font-semibold text-ink">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[13px] text-muted">Total pesanan</span>
                    <span class="tabular text-[18px] font-semibold text-forest">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </section>

            <x-alert tone="warning">Setelah pesanan dibuat, kamu diarahkan ke halaman pembayaran. Pesanan diproses setelah pembayaran diterima.</x-alert>
        </main>

        <x-action-bar>
            <x-button pill type="submit">Buat Pesanan &amp; Bayar</x-button>
        </x-action-bar>
    </form>
@endsection
