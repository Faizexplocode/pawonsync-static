@extends('layouts.phone')

@section('title', 'Masuk')

@php
    $roles = ['buyer' => 'Buyer', 'seller' => 'Seller', 'kurir' => 'Kurir'];
    $labels = [
        'buyer' => ['field' => 'Alamat kirim', 'hint' => 'Contoh: Graha ITS, Surabaya', 'home' => 'buyer.beranda'],
        'seller' => ['field' => 'Nama dapur', 'hint' => 'Contoh: Pawon Lestari', 'home' => 'seller.dashboard'],
        'kurir' => ['field' => 'Nomor kendaraan', 'hint' => 'Contoh: L 1234 AB', 'home' => 'kurir.beranda'],
    ];
    $current = $labels[$role];
@endphp

@section('content')
    <x-app-bar title="Masuk" subtitle="Pilih peran lalu masuk" :back="route('splash')" />

    <main class="flex-1 px-4 py-5">
        <h2 class="text-[22px] font-extrabold tracking-tight text-forest-deep">Selamat datang kembali</h2>
        <p class="mt-1 text-[13px] leading-relaxed text-muted">Kelola katering dari dapur sampai venue dengan satu aplikasi.</p>

        <div class="mt-5 grid grid-cols-3 gap-1 rounded-2xl bg-white p-1 ring-1 ring-line">
            @foreach ($roles as $key => $label)
                <a href="{{ route('login', $key) }}" @class([
                    'rounded-xl py-2 text-center text-[12px] font-bold transition',
                    'bg-forest text-white shadow-sm' => $role === $key,
                    'text-muted hover:text-forest' => $role !== $key,
                ])>{{ $label }}</a>
            @endforeach
        </div>

        <form class="mt-6 space-y-4" action="{{ route($current['home']) }}" method="get">
            <x-field label="Email" type="email" placeholder="nama@email.com" icon="mail" />
            <x-field label="Password" type="password" placeholder="••••••••" icon="lock" />
            <x-field :label="$current['field']" :placeholder="$current['hint']" :hint="$current['hint']" icon="location" />

            <div class="flex items-center justify-between pt-1 text-[11px] font-semibold">
                <label class="inline-flex items-center gap-2 text-muted">
                    <input type="checkbox" checked class="size-4 rounded border-line text-leaf focus:ring-mint" />
                    Ingat saya
                </label>
                <a href="#" class="text-leaf">Lupa password?</a>
            </div>

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[15px] font-semibold text-white shadow-lg shadow-forest/20 transition hover:bg-forest-deep">
                Masuk
                <x-icon name="arrow-right" class="size-4" />
            </button>
        </form>

        <p class="mt-4 text-center text-[11px] leading-relaxed text-muted">
            Dengan masuk, kamu menyetujui
            <a href="#" class="font-semibold text-forest">Syarat &amp; Kebijakan Privasi</a> PawonSync.
        </p>

        <div class="mt-6 rounded-2xl bg-mint-soft p-4 text-[11px] leading-relaxed text-forest">
            <p class="font-bold">Mode statis</p>
            <p class="mt-1 text-leaf-dark">Halaman ini belum terhubung database. Tombol Masuk langsung membuka beranda sesuai peran.</p>
        </div>
    </main>
@endsection
