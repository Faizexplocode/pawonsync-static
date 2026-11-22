@extends('layouts.phone')

@section('title', 'Konfirmasi Tiba')

@php
    $order = config('demo.orders')[0];
@endphp

@section('content')
    <x-app-bar title="Konfirmasi Tiba" subtitle="Pesanan di Graha ITS" :back="route('buyer.tracking')" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="flex flex-col items-center rounded-3xl bg-forest px-5 py-6 text-center text-white">
            <span class="grid size-14 place-items-center rounded-full bg-mint text-forest"><x-icon name="location" class="size-7" /></span>
            <h2 class="mt-3 text-[19px] font-extrabold">Kurir sudah tiba</h2>
            <p class="mt-1 text-[11px] text-mint/80">{{ $order['courier'] }} · {{ $order['vehicle'] }}</p>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Bukti serah terima</p>
            <x-photo label="Foto serah terima" class="mt-2 h-40 w-full" />
            <div class="mt-3 grid grid-cols-2 gap-2">
                <div class="rounded-xl bg-mist p-3">
                    <p class="text-[9px] font-semibold text-muted">Diterima oleh</p>
                    <p class="mt-0.5 text-[11px] font-bold text-ink">Bu Diana</p>
                </div>
                <div class="rounded-xl bg-mist p-3">
                    <p class="text-[9px] font-semibold text-muted">Waktu</p>
                    <p class="mt-0.5 text-[11px] font-bold text-ink">10.28 WIB</p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Checklist pesanan</p>
            <div class="mt-2 space-y-2">
                @foreach ([
                    ['Jumlah sesuai (150 box)', true],
                    ['Menu dan opsi tambahan sesuai', true],
                    ['Kemasan utuh & tidak rusak', true],
                    ['Dokumentasi diterima', true],
                ] as [$label, $done])
                    <div class="flex items-center gap-2 text-[11px]">
                        <span @class(['grid size-4.5 place-items-center rounded-full text-white', 'bg-leaf' => $done, 'bg-line' => ! $done])>
                            @if ($done)<x-icon name="check" class="size-2.5" />@endif
                        </span>
                        <span class="text-muted">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            Pastikan kamu memeriksa pesanan sebelum menekan konfirmasi. Setelah dikonfirmasi, pesanan dianggap selesai.
        </div>

        <a href="{{ route('buyer.ulasan') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[14px] font-bold text-white">
            <x-icon name="check" class="size-4" />
            Konfirmasi diterima
        </a>
    </main>
@endsection
