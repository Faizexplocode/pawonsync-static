@extends('layouts.phone')

@section('title', 'Selamat datang')

@section('content')
    <div class="relative flex min-h-screen flex-col justify-end overflow-hidden bg-gradient-to-b from-forest-deep to-leaf px-7 pb-10 sm:min-h-[calc(100vh-3rem)]">
        <div class="pointer-events-none absolute -left-16 -top-24 size-[420px] rounded-full bg-leaf/40 blur-2xl"></div>
        <div class="pointer-events-none absolute -right-10 top-1/3 size-[280px] rounded-full bg-mint/20 blur-2xl"></div>

        <div class="relative flex flex-1 flex-col items-center justify-center">
            <div class="grid size-[92px] place-items-center rounded-[30px] bg-mint text-[34px] font-extrabold text-forest">PS</div>
            <h1 class="mt-6 text-[34px] font-extrabold tracking-tight text-white">PawonSync</h1>
            <p class="mt-1 text-center text-[15px] font-medium text-mint">Ketenangan dari Dapur hingga Venue</p>
        </div>

        <div class="relative space-y-3">
            @foreach ([['buyer', 'Masuk sebagai Buyer'], ['seller', 'Masuk sebagai Seller'], ['kurir', 'Masuk sebagai Kurir']] as [$role, $label])
                <a href="{{ route('login', $role) }}" class="flex items-center justify-center rounded-2xl bg-mint px-4 py-3.5 text-[15px] font-semibold text-forest transition hover:bg-white">
                    {{ $label }}
                </a>
            @endforeach
            <p class="pt-2 text-center text-[13px] font-medium text-mint">
                Mode prototype · <a href="{{ route('screens') }}" class="font-bold underline underline-offset-4">Lihat semua layar</a>
            </p>
        </div>
    </div>
@endsection
