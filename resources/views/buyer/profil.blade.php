@extends('layouts.phone')

@section('title', 'Profil')

@php
    $items = [
        ['receipt', 'Pesanan saya', 'Riwayat dan pesanan aktif', route('buyer.pesanan')],
        ['bell', 'Notifikasi', 'Status pesanan, progres dapur, kurir', route('buyer.notifikasi')],
        ['search', 'Katalog menu', 'Cari menu katering', route('buyer.katalog')],
        ['cart', 'Keranjang', 'Lanjutkan checkout', route('buyer.keranjang')],
    ];
@endphp

@section('content')
    <div class="flex-1 bg-cream">
        <header class="px-5 pb-2 pt-5">
            <p class="text-[12px] text-muted">Buyer PawonSync</p>
            <h1 class="text-[20px] font-semibold text-ink">Profil saya</h1>
        </header>

        <main class="stagger space-y-6 px-5 pb-6 pt-3">
            <section class="relative overflow-hidden rounded-[28px] bg-[linear-gradient(140deg,var(--color-forest-night)_0%,var(--color-forest)_55%,var(--color-leaf)_100%)] p-5 text-white">
                <div class="pointer-events-none absolute -right-10 -top-10 size-44 rounded-full bg-white/5"></div>
                <div class="relative flex items-center gap-3.5">
                    <span class="grid size-16 shrink-0 place-items-center rounded-full bg-mint text-[22px] font-semibold text-forest ring-4 ring-white/15">{{ strtoupper(substr($buyer->nama, 0, 2)) }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-[18px] font-semibold">{{ $buyer->nama }}</p>
                        <p class="truncate text-[12px] text-mint/80">{{ $buyer->email }}</p>
                    </div>
                </div>
                <div class="relative mt-4 grid grid-cols-2 divide-x divide-white/15 rounded-[18px] bg-white/10 py-3 text-center">
                    @foreach ([[$jumlahPesanan, 'Pesanan'], [$jumlahUlasan, 'Ulasan']] as [$nilai, $label])
                        <div>
                            <p class="tabular text-[22px] font-semibold" data-count-to="{{ $nilai }}">{{ $nilai }}</p>
                            <p class="text-[11px] text-mint/80">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="space-y-3">
                <h2 class="text-[15px] font-semibold text-ink">Pintasan</h2>
                <div class="surface divide-y divide-line/70 px-4">
                    @foreach ($items as [$ikon, $label, $catatan, $href])
                        <a href="{{ $href }}" class="flex items-center gap-3 py-3">
                            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-mint-faint text-forest"><x-icon :name="$ikon" class="size-[18px]" /></span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-body font-medium text-ink">{{ $label }}</span>
                                <span class="block truncate text-[12px] text-muted">{{ $catatan }}</span>
                            </span>
                            <x-icon name="chevron-right" class="size-4 shrink-0 text-muted" />
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="space-y-3">
                <h2 class="text-[15px] font-semibold text-ink">Ubah profil</h2>
                <form action="{{ route('buyer.profil.update') }}" method="post" class="surface space-y-3.5 p-4">
                    @csrf
                    @method('PATCH')
                    <x-field label="Nama" name="nama" :value="old('nama', $buyer->nama)" required />
                    <x-field label="Email" name="email" type="email" :value="old('email', $buyer->email)" required />
                    <x-field label="Password baru" name="password" type="password" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password" />
                    <x-field label="Konfirmasi password" name="password_confirmation" type="password" placeholder="Ulangi password baru" autocomplete="new-password" />
                    <x-button pill type="submit">Simpan Perubahan</x-button>
                </form>
            </section>

            <form action="{{ route('logout') }}" method="post" class="text-center">
                @csrf
                <button type="submit" class="text-[13px] font-medium text-muted underline-offset-4 hover:underline">Keluar dari akun</button>
            </form>
        </main>
    </div>

    <x-bottom-nav role="buyer" active="buyer.profil" />
@endsection
