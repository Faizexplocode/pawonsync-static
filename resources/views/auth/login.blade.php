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
    <x-app-bar title="Masuk" subtitle="Pilih peran lalu masuk" :back="route('splash')" />

    <main class="flex-1 space-y-3.5 p-4">
        <h2 class="text-[22px] font-bold text-ink">Selamat datang kembali</h2>
        <p class="text-[13px] text-muted">Kelola katering dari dapur sampai venue dengan satu aplikasi.</p>

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

        <p class="text-center text-[11px] text-muted">Dengan masuk, kamu menyetujui Syarat &amp; Kebijakan Privasi PawonSync.</p>

        <x-alert tone="info" :title="'Akun demo '.$roles[$role]">
            {{ $akun['name'] }} · {{ $akun['email'] }} · Password: password
        </x-alert>
    </main>
@endsection
