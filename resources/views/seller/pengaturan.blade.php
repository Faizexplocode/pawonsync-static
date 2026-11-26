@extends('layouts.phone')

@section('title', 'Pengaturan Toko')

@php
    $pintasan = [
        ['Kt', 'Kelola katalog menu', 'Tambah, ubah, dan hapus menu', route('seller.katalog')],
        ['Kl', 'Kalender kapasitas', 'Jadwal produksi dan kapasitas harian', route('seller.kalender')],
        ['Rp', 'Keuangan', 'Pendapatan dan riwayat transaksi', route('seller.keuangan')],
    ];
@endphp

@section('content')
    <x-app-bar title="Pengaturan Toko" :subtitle="$seller->nama" />

    <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
        <section class="flex items-center gap-3.5 surface px-4 py-3.5">
            <span class="grid size-16 shrink-0 place-items-center rounded-full bg-forest text-[23px] font-bold text-white">{{ strtoupper(substr($seller->nama, 0, 2)) }}</span>
            <div class="min-w-0 flex-1 space-y-0.5">
                <p class="truncate text-[17px] font-semibold text-ink">{{ $seller->nama }}</p>
                <p class="truncate text-caption text-muted">{{ $seller->email }}</p>
                <x-badge color="green">Seller / Dapur</x-badge>
            </div>
        </section>

        <p class="text-[15px] font-semibold text-ink">Profil Toko</p>
        <form action="{{ route('seller.pengaturan.update') }}" method="post" class="space-y-3.5 surface px-4 py-3.5">
            @csrf
            @method('PATCH')
            <x-field label="Nama katering" name="nama" :value="old('nama', $seller->nama)" required />
            <x-field label="Email" name="email" type="email" :value="old('email', $seller->email)" required />
            <x-field label="Password baru" name="password" type="password" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password" />
            <x-field label="Konfirmasi password" name="password_confirmation" type="password" placeholder="Ulangi password baru" autocomplete="new-password" />
            <x-button type="submit">Simpan Perubahan</x-button>
        </form>

        <p class="text-[15px] font-semibold text-ink">Pintasan</p>
        <section class="divide-y divide-line surface px-4 py-1.5">
            @foreach ($pintasan as [$kode, $label, $catatan, $href])
                <a href="{{ $href }}" class="flex items-center gap-3 py-2.5">
                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-mint-soft text-caption font-semibold text-forest">{{ $kode }}</span>
                    <span class="min-w-0 flex-1 space-y-0.5">
                        <span class="block text-body font-semibold text-ink">{{ $label }}</span>
                        <span class="block text-caption text-muted">{{ $catatan }}</span>
                    </span>
                    <span class="text-[18px] font-medium text-muted">›</span>
                </a>
            @endforeach
        </section>

        <form action="{{ route('logout') }}" method="post">
            @csrf
            <x-button type="submit" variant="outline">Keluar</x-button>
        </form>
    </main>

    <x-bottom-nav role="seller" active="seller.pengaturan" />
@endsection
