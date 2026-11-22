@extends('layouts.phone')

@section('title', 'Pengaturan Toko')

@php
    $sections = [
        [
            'title' => 'Profil toko',
            'items' => [
                ['Nama dapur', 'Pawon Lestari', 'store'],
                ['Deskripsi', 'Katering rumahan sejak 2016', 'edit'],
                ['Alamat dapur', 'Jl. Rungkut Asri 21, Surabaya', 'location'],
                ['Nomor telepon', '0812 3456 7890', 'phone'],
            ],
        ],
        [
            'title' => 'Operasional',
            'items' => [
                ['Jam operasional', 'Setiap hari, 06.00–20.00', 'clock'],
                ['Kapasitas harian', '200 porsi', 'boxes'],
                ['Zona pengiriman', 'Surabaya Timur & Pusat', 'map'],
                ['Metode pembayaran', 'QRIS, VA, COD', 'credit-card'],
            ],
        ],
        [
            'title' => 'Keamanan',
            'items' => [
                ['Verifikasi toko', 'Terverifikasi', 'shield'],
                ['Rekening pencairan', 'BCA · 1234 5678 90', 'banknote'],
                ['Ubah password', 'Terakhir diubah 2 bulan lalu', 'lock'],
            ],
        ],
    ];
@endphp

@section('content')
    <x-app-bar title="Pengaturan Toko" subtitle="Pawon Lestari" :back="route('seller.dashboard')" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center gap-3">
                <span class="grid size-14 place-items-center rounded-2xl bg-mint text-[16px] font-extrabold text-forest">PL</span>
                <div class="min-w-0 flex-1">
                    <p class="text-[14px] font-extrabold text-forest-deep">Pawon Lestari</p>
                    <p class="text-[10px] text-muted">Bergabung Mei 2024 · 318 ulasan</p>
                    <div class="mt-1 flex items-center gap-1.5">
                        <x-badge color="green">Terverifikasi</x-badge>
                        <x-badge color="mint">★ 4.9</x-badge>
                    </div>
                </div>
                <a href="#" class="grid size-9 place-items-center rounded-full bg-mist text-muted" aria-label="Edit foto">
                    <x-icon name="camera" class="size-4" />
                </a>
            </div>
            <div class="mt-3 grid grid-cols-3 gap-2 border-t border-line pt-3 text-center">
                <div><p class="text-[13px] font-extrabold text-forest-deep">612</p><p class="text-[9px] text-muted">Pesanan</p></div>
                <div><p class="text-[13px] font-extrabold text-forest-deep">96%</p><p class="text-[9px] text-muted">Tepat waktu</p></div>
                <div><p class="text-[13px] font-extrabold text-forest-deep">4.9</p><p class="text-[9px] text-muted">Rating</p></div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[12px] font-bold text-forest-deep">Status toko</p>
                    <p class="text-[9px] text-muted">Toko terbuka &amp; menerima pesanan</p>
                </div>
                <span class="relative h-6 w-11 rounded-full bg-leaf">
                    <span class="absolute right-1 top-1 size-4 rounded-full bg-white shadow"></span>
                </span>
            </div>
            <div class="mt-3 rounded-xl bg-mint-soft p-3 text-[10px] leading-relaxed text-forest">
                <span class="font-bold">Mode libur:</span> aktifkan jika dapur tutup sementara. Pesanan baru akan ditolak otomatis.
            </div>
        </section>

        @foreach ($sections as $section)
            <section>
                <p class="mb-2 px-1 text-[11px] font-bold uppercase tracking-wide text-muted">{{ $section['title'] }}</p>
                <div class="overflow-hidden rounded-2xl border border-line bg-white">
                    @foreach ($section['items'] as [$label, $value, $icon])
                        <a href="#" class="flex items-center gap-3 border-b border-line px-4 py-3 last:border-0">
                            <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-mint-soft text-leaf">
                                <x-icon :name="$icon" class="size-4" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-[11px] font-semibold text-forest-deep">{{ $label }}</span>
                                <span class="block truncate text-[9px] text-muted">{{ $value }}</span>
                            </span>
                            <x-icon name="chevron-right" class="size-4 shrink-0 text-muted" />
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach

        <section class="overflow-hidden rounded-2xl border border-line bg-white">
            <a href="{{ route('screens') }}" class="flex items-center gap-3 border-b border-line px-4 py-3">
                <span class="grid size-8 place-items-center rounded-lg bg-mist text-muted"><x-icon name="help" class="size-4" /></span>
                <span class="flex-1 text-[11px] font-semibold text-forest-deep">Pusat bantuan</span>
                <x-icon name="chevron-right" class="size-4 text-muted" />
            </a>
            <a href="{{ route('splash') }}" class="flex items-center gap-3 px-4 py-3">
                <span class="grid size-8 place-items-center rounded-lg bg-mist text-muted"><x-icon name="logout" class="size-4" /></span>
                <span class="flex-1 text-[11px] font-semibold text-forest-deep">Keluar</span>
                <x-icon name="chevron-right" class="size-4 text-muted" />
            </a>
        </section>

        <p class="text-center text-[9px] text-muted">PawonSync Seller v1.0 · Mode statis</p>
    </main>

    <x-bottom-nav role="seller" active="seller.pengaturan" />
@endsection
