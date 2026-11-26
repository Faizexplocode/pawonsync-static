@extends('layouts.phone')

@section('title', 'Masuk')

@php
    $roles = ['buyer' => 'Buyer', 'seller' => 'Seller', 'kurir' => 'Kurir'];
    $demo = [
        'buyer' => ['email' => 'buyer@pawonsync.test', 'name' => 'Bu Diana'],
        'seller' => ['email' => 'seller@pawonsync.test', 'name' => 'Pawon Lestari'],
        'kurir' => ['email' => 'kurir@pawonsync.test', 'name' => 'Budi Santoso'],
    ];
    $akun = $demo[$role];
@endphp

@section('content')
    <header class="relative overflow-hidden rounded-b-[32px] bg-[linear-gradient(150deg,var(--color-forest-night)_0%,var(--color-forest)_45%,var(--color-leaf)_100%)] pb-8">
        <div class="pointer-events-none absolute -right-16 -top-20 size-64 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-10 size-56 rounded-full bg-leaf-light/15 blur-2xl"></div>
        <div class="relative flex items-center px-4 pt-4">
            <a href="{{ route('splash') }}" class="grid size-9 place-items-center rounded-full bg-white/10 text-[24px] leading-none text-white" aria-label="Kembali">‹</a>
        </div>
        <div class="relative flex flex-col items-center gap-3 px-6 pt-2 text-center">
            <div class="flex w-[188px] items-center justify-center rounded-[22px] bg-white px-5 py-3 shadow-[0_14px_32px_rgba(0,0,0,0.25)]">
                <img src="{{ asset('images/logo-pawonsync-mark.png') }}" alt="PawonSync" class="h-auto w-full" />
            </div>
            <h1 class="text-[22px] font-bold text-white">Selamat datang kembali</h1>
            <p class="text-[13px] text-mint">Kelola katering dari dapur sampai venue dengan satu aplikasi.</p>
        </div>
    </header>

    <main class="relative -mt-5 flex-1 space-y-3.5 px-4 pb-6">
        <div class="space-y-3.5 rounded-[24px] border border-line bg-white p-4 shadow-[0_10px_30px_rgba(6,48,31,0.08)]">
            <div class="flex rounded-full bg-mint-soft p-1">
                @foreach ($roles as $key => $label)
                    <a href="{{ route('login', $key) }}" @class([
                        'flex-1 rounded-full py-2 text-center text-caption',
                        'bg-white font-bold text-forest shadow-[0_3px_10px_rgba(5,51,26,0.08)]' => $role === $key,
                        'font-medium text-muted' => $role !== $key,
                    ])>{{ $label }}</a>
                @endforeach
            </div>

            <form class="space-y-3.5" action="{{ route('login.store', $role) }}" method="post">
                @csrf

                <x-field label="Email" name="email" type="email" :value="old('email')" placeholder="nama@email.com" autocomplete="email" required />
                <x-field label="Password" name="password" type="password" placeholder="••••••••" autocomplete="current-password" required />

                <div class="flex items-center justify-between text-caption">
                    <span class="font-medium text-muted">Ingat saya</span>
                    <span class="font-semibold text-leaf-dark">Lupa password?</span>
                </div>

                <x-button type="submit">Masuk</x-button>
            </form>
        </div>

        <p class="text-center text-[11px] text-muted">Dengan masuk, kamu menyetujui Syarat &amp; Kebijakan Privasi PawonSync.</p>

        <x-alert tone="info" :title="'Akun demo '.$roles[$role]">
            {{ $akun['name'] }} · {{ $akun['email'] }} · Password: password
        </x-alert>
    </main>
@endsection
