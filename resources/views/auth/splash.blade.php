@extends('layouts.phone')

@section('title', 'Selamat datang')

@section('content')
    <div class="relative flex min-h-screen flex-col overflow-hidden bg-forest-night sm:min-h-[calc(100vh-3rem)]">
        <img src="{{ asset('images/menu/prasmanan.jpg') }}" alt="" aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-[62%] w-full object-cover" />
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(180deg,rgba(4,36,23,0.35)_0%,rgba(4,36,23,0.72)_38%,var(--color-forest-night)_62%,var(--color-forest)_82%,var(--color-leaf)_100%)]"></div>
        <div class="pointer-events-none absolute -right-24 bottom-24 size-72 rounded-full bg-leaf-light/15 blur-3xl"></div>

        <div class="relative mt-auto flex flex-col items-center gap-3 px-7 pb-10">
            <div class="flex w-[240px] items-center justify-center rounded-[28px] bg-white px-6 py-4 shadow-[0_18px_40px_rgba(0,0,0,0.28)]">
                <img src="{{ asset('images/logo-pawonsync-mark.png') }}" alt="PawonSync" class="h-auto w-full" />
            </div>
            <h1 class="mt-2 text-[34px] font-bold text-white">PawonSync</h1>
            <p class="text-center text-[15px] font-medium text-mint">Ketenangan dari Dapur hingga Venue</p>

            <div class="mt-6 w-full space-y-3">
                @foreach ([['buyer', 'Masuk sebagai Buyer'], ['seller', 'Masuk sebagai Seller'], ['kurir', 'Masuk sebagai Kurir']] as [$role, $label])
                    <x-button variant="secondary" :href="route('login', $role)">{{ $label }}</x-button>
                @endforeach
            </div>

            <p class="mt-1 text-center text-[13px] font-medium text-mint">Belum punya akun? Daftar</p>
        </div>
    </div>
@endsection
