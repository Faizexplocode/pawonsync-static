@extends('layouts.phone')

@section('title', 'Daftar Layar')

@php
    $groups = [
        'Buyer · 15 layar' => ['Beranda', 'Katalog', 'Filter', 'Detail menu', 'Keranjang', 'Checkout', 'Pembayaran', 'Konfirmasi pesanan', 'Pesanan saya', 'Detail pesanan', 'Live tracking', 'Konfirmasi tiba', 'Ulasan', 'Notifikasi', 'Profil'],
        'Seller · 14 layar' => ['Dashboard', 'Pesanan masuk', 'Detail pesanan', 'Update milestone', 'Packaging', 'Penugasan kurir', 'Kelola katalog', 'Form menu', 'Kalkulasi bahan', 'Kalender', 'Keuangan', 'Promosi', 'Pengaturan toko', 'Retur'],
        'Kurir · 6 layar' => ['Beranda kurir', 'Daftar tugas', 'Detail tugas', 'Departure gate', 'Live location', 'Bukti serah terima'],
    ];
@endphp

@section('content')
    <x-app-bar title="PawonSync" subtitle="39 layar terhubung database" :back="route('splash')" />

    <main class="flex-1 space-y-5 px-4 py-5">
        <div class="rounded-2xl bg-forest p-4 text-mint">
            <p class="text-[11px] font-bold uppercase tracking-wide text-mint/70">Aplikasi aktif</p>
            <p class="mt-1 text-[15px] font-extrabold text-white">Buyer, Seller, dan Kurir</p>
            <p class="mt-1 text-[11px] leading-relaxed text-mint/80">Masuk dengan akun demo untuk membuka seluruh layar sesuai peran. Data diambil langsung dari database.</p>
        </div>

        <section>
            <x-section-title title="Akun demo" />
            <div class="space-y-2">
                @foreach ([['buyer', 'Buyer', 'buyer@pawonsync.test'], ['seller', 'Seller', 'seller@pawonsync.test'], ['kurir', 'Kurir', 'kurir@pawonsync.test']] as [$role, $label, $email])
                    <a href="{{ route('login', $role) }}" class="flex items-center gap-3 rounded-2xl border border-line bg-white p-3">
                        <span class="grid size-10 place-items-center rounded-full bg-mint text-[12px] font-extrabold text-forest">{{ substr($label, 0, 2) }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[12px] font-bold text-forest-deep">Masuk sebagai {{ $label }}</span>
                            <span class="block text-[9px] text-muted">{{ $email }} · password</span>
                        </span>
                        <x-icon name="chevron-right" class="size-4 text-muted" />
                    </a>
                @endforeach
            </div>
        </section>

        @foreach ($groups as $group => $screens)
            <section>
                <x-section-title :title="$group" />
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($screens as $screen)
                        <span class="rounded-xl border border-line bg-white px-3 py-2.5 text-[11px] font-semibold text-forest-deep">{{ $screen }}</span>
                    @endforeach
                </div>
            </section>
        @endforeach
    </main>
@endsection
