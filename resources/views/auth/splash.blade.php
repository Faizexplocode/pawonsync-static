@extends('layouts.phone')

@section('title', 'Selamat datang')

@section('content')
    <div class="relative flex min-h-screen flex-col items-center justify-end gap-3.5 overflow-hidden bg-linear-to-b from-forest-night to-leaf px-7 pb-10 sm:min-h-[calc(100vh-3rem)]">
        <div class="pointer-events-none absolute -left-[60px] -top-[90px] size-[420px] rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute left-[190px] top-[330px] size-[280px] rounded-full bg-mint/10"></div>

        <div class="relative grid h-[92px] w-[222px] place-items-center overflow-hidden rounded-[30px] bg-mint">
            <img src="{{ asset('images/logo-pawonsync.png') }}" alt="PawonSync" class="h-[148px] w-[222px] max-w-none object-cover" />
        </div>
        <h1 class="relative text-[34px] font-bold text-white">PawonSync</h1>
        <p class="relative text-center text-[15px] font-medium text-mint">Ketenangan dari Dapur hingga Venue</p>

        <div class="relative mt-[30px] w-full space-y-3.5">
            @foreach ([['buyer', 'Masuk sebagai Buyer'], ['seller', 'Masuk sebagai Seller'], ['kurir', 'Masuk sebagai Kurir']] as [$role, $label])
                <x-button variant="secondary" :href="route('login', $role)">{{ $label }}</x-button>
            @endforeach
        </div>

        <p class="relative text-center text-[13px] font-medium text-mint">Belum punya akun? Daftar</p>
    </div>
@endsection
