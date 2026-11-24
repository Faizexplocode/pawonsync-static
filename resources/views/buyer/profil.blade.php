@extends('layouts.phone')

@section('title', 'Profil')

@php
    $items = [
        ['Ps', 'Pesanan saya', 'Riwayat dan pesanan aktif', route('buyer.pesanan')],
        ['Nt', 'Notifikasi', 'Status pesanan, progres dapur, kurir', route('buyer.notifikasi')],
        ['Kt', 'Katalog menu', 'Cari menu katering', route('buyer.katalog')],
        ['Kr', 'Keranjang', 'Lanjutkan checkout', route('buyer.keranjang')],
    ];
@endphp

@section('content')
    <header class="bg-[linear-gradient(147deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)]">
        <x-status-bar tone="light" />
        <div class="px-4 pb-5 pt-2.5">
            <h1 class="text-[18px] font-bold text-white">Profil Saya</h1>
            <p class="mt-0.5 text-caption text-mint">Buyer PawonSync</p>
        </div>
        <div class="flex items-center gap-3.5 px-4 pb-[18px]">
            <span class="grid size-16 shrink-0 place-items-center rounded-full bg-mint text-[23px] font-bold text-forest">{{ strtoupper(substr($buyer->nama, 0, 2)) }}</span>
            <div class="min-w-0 flex-1 space-y-0.5">
                <p class="truncate text-[17px] font-bold text-white">{{ $buyer->nama }}</p>
                <p class="truncate text-caption text-mint">{{ $buyer->email }}</p>
            </div>
        </div>
    </header>

    <main class="flex-1 space-y-4 p-4">
        <div class="flex gap-2.5">
            <div class="flex-1 space-y-0.5 rounded-2xl border border-line bg-white px-3.5 py-3">
                <p class="text-[11px] font-medium text-muted">Pesanan</p>
                <p class="text-[18px] font-bold text-ink">{{ $jumlahPesanan }}</p>
            </div>
            <div class="flex-1 space-y-0.5 rounded-2xl border border-line bg-white px-3.5 py-3">
                <p class="text-[11px] font-medium text-muted">Ulasan</p>
                <p class="text-[18px] font-bold text-ink">{{ $jumlahUlasan }}</p>
            </div>
        </div>

        <p class="text-title font-bold text-ink">Pintasan</p>
        <section class="divide-y divide-line rounded-[18px] border border-line bg-white px-4 py-1.5">
            @foreach ($items as [$kode, $label, $catatan, $href])
                <a href="{{ $href }}" class="flex items-center gap-3 py-2.5">
                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-mint-soft text-caption font-bold text-forest">{{ $kode }}</span>
                    <span class="min-w-0 flex-1 space-y-0.5">
                        <span class="block text-body font-semibold text-ink">{{ $label }}</span>
                        <span class="block text-caption text-muted">{{ $catatan }}</span>
                    </span>
                    <span class="text-[18px] font-medium text-muted">›</span>
                </a>
            @endforeach
        </section>

        <p class="text-title font-bold text-ink">Ubah Profil</p>
        <form action="{{ route('buyer.profil.update') }}" method="post" class="space-y-3.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
            @csrf
            @method('PATCH')
            <x-field label="Nama" name="nama" :value="old('nama', $buyer->nama)" required />
            <x-field label="Email" name="email" type="email" :value="old('email', $buyer->email)" required />
            <x-field label="Password baru" name="password" type="password" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password" />
            <x-field label="Konfirmasi password" name="password_confirmation" type="password" placeholder="Ulangi password baru" autocomplete="new-password" />
            <x-button type="submit">Simpan Perubahan</x-button>
        </form>

        <form action="{{ route('logout') }}" method="post">
            @csrf
            <x-button type="submit" variant="outline">Keluar</x-button>
        </form>
    </main>

    <x-bottom-nav role="buyer" active="buyer.profil" />
@endsection
