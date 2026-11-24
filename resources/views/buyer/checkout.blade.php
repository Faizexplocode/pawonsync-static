@extends('layouts.phone')

@section('title', 'Checkout')

@php
    $kolom = 'w-full rounded-xl border border-line bg-white px-3.5 py-[13px] text-body text-ink outline-none focus:border-leaf';
@endphp

@section('content')
    <x-app-bar title="Checkout" subtitle="Form pemesanan katering" :back="route('buyer.keranjang')" />

    <form action="{{ route('buyer.checkout.store') }}" method="post" class="flex flex-1 flex-col">
        @csrf

        <main class="flex-1 space-y-3.5 p-4">
            <p class="text-[15px] font-bold text-ink">Alamat venue</p>
            <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <x-badge color="dark">Pemesan</x-badge>
                <p class="text-body font-semibold text-ink">{{ $buyer->nama }}</p>
                <p class="text-caption text-muted">{{ $buyer->email }}</p>
                <label class="block">
                    <span class="mb-1.5 block text-caption font-medium text-muted">Alamat venue</span>
                    <textarea name="alamat_venue" rows="2" required placeholder="Contoh: Graha ITS, Kampus ITS Sukolilo, Surabaya" class="{{ $kolom }}">{{ old('alamat_venue', $alamat) }}</textarea>
                </label>
            </section>

            <p class="text-[15px] font-bold text-ink">Jadwal acara</p>
            <label class="block">
                <span class="mb-1.5 block text-caption font-medium text-muted">Tanggal acara</span>
                <input type="date" name="tanggal_acara" value="{{ old('tanggal_acara', $tanggal) }}" min="{{ now()->toDateString() }}" required class="{{ $kolom }}" />
            </label>
            <label class="block">
                <span class="mb-1.5 block text-caption font-medium text-muted">Waktu acara (jam antar)</span>
                <input type="time" name="waktu_acara" value="{{ old('waktu_acara', $jam) }}" required class="{{ $kolom }}" />
            </label>
            <x-badge color="green" class="whitespace-normal">Kapasitas dapur dicek otomatis pada tanggal acara</x-badge>

            <div class="flex items-center justify-between">
                <p class="text-[15px] font-bold text-ink">Pesanan Anda</p>
                <a href="{{ route('buyer.keranjang') }}" class="text-caption font-semibold text-leaf-dark">Ubah</a>
            </div>
            <section class="space-y-2.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                @foreach ($items as $item)
                    <div class="flex items-center gap-2.5">
                        <x-photo :src="$item['photo']" label="" class="size-11 shrink-0 rounded-[10px]" />
                        <div class="min-w-0 flex-1 space-y-px">
                            <p class="truncate text-[13px] font-semibold text-ink">{{ $item['menu_nama'] }} x{{ $item['qty'] }}</p>
                            <p class="truncate text-[11px] text-muted">{{ collect([$item['seller'], $item['paket_nama'], ...$item['opsi_nama']])->filter()->implode(' · ') }}</p>
                        </div>
                        <p class="shrink-0 text-[13px] font-bold text-forest">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </section>

            <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <p class="text-body font-bold text-ink">Rincian biaya</p>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Subtotal ({{ $porsi }} porsi)</span>
                    <span class="font-semibold text-ink">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Total pesanan</span>
                    <span class="font-bold text-forest">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </section>

            <x-alert tone="warning">Setelah pesanan dibuat, kamu diarahkan ke halaman pembayaran. Pesanan diproses setelah pembayaran diterima.</x-alert>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button type="submit">Buat Pesanan &amp; Bayar</x-button>
        </div>
    </form>
@endsection
