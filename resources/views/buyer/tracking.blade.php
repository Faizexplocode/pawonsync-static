@extends('layouts.phone')

@section('title', 'Live Tracking')

@php
    $order = config('demo.orders')[0];
@endphp

@section('content')
    <x-app-bar title="Live Tracking" subtitle="Pesanan sedang dalam perjalanan" :back="route('buyer.pesanan.detail', $order['id'])" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="relative h-56 overflow-hidden rounded-3xl border border-line bg-mint-soft">
            <div class="absolute inset-0 opacity-60 [background-image:linear-gradient(#c7e6d5_1px,transparent_1px),linear-gradient(90deg,#c7e6d5_1px,transparent_1px)] [background-size:28px_28px]"></div>
            <div class="absolute left-6 top-10 size-3 rounded-full bg-forest ring-4 ring-forest/20"></div>
            <div class="absolute right-8 bottom-10 size-3 rounded-full bg-amber ring-4 ring-amber/20"></div>
            <div class="absolute left-[8%] top-[46%] h-1 w-[80%] -rotate-6 rounded-full bg-leaf/40"></div>
            <div class="absolute left-[42%] top-[38%] grid size-9 -translate-x-1/2 place-items-center rounded-full bg-white text-leaf shadow-lg ring-1 ring-line">
                <x-icon name="bike" class="size-5" />
            </div>
            <span class="absolute bottom-3 left-3 rounded-full bg-white/90 px-2.5 py-1 text-[9px] font-bold text-forest-deep">Graha ITS</span>
            <span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[9px] font-bold text-forest-deep">Pawon Lestari</span>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-muted">Estimasi tiba</p>
                    <p class="text-[20px] font-extrabold text-forest">10.30</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-semibold text-muted">Sisa jarak</p>
                    <p class="text-[13px] font-bold text-forest-deep">3,2 km</p>
                </div>
            </div>
            <div class="mt-3"><x-progress-bar :value="68" /></div>
            <p class="mt-1.5 text-[10px] text-muted">Kurir sedang di Jl. Arief Rahman Hakim.</p>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center gap-3">
                <span class="grid size-11 place-items-center rounded-full bg-mint text-[13px] font-extrabold text-forest">BS</span>
                <div class="min-w-0 flex-1">
                    <p class="text-[13px] font-bold text-ink">{{ $order['courier'] }}</p>
                    <p class="text-[10px] text-muted">{{ $order['vehicle'] }} · ★ 4.9</p>
                </div>
                <div class="flex gap-1.5">
                    <a href="#" class="grid size-9 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="phone" class="size-4" /></a>
                    <a href="#" class="grid size-9 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="mail" class="size-4" /></a>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Riwayat perjalanan</p>
            <div class="mt-3 space-y-3">
                @foreach ([
                    ['Pesanan diambil kurir', '09.48 · Pawon Lestari', true],
                    ['Dalam perjalanan', '09.55 · Jl. Arief Rahman Hakim', true],
                    ['Tiba di venue', 'Estimasi 10.30 · Graha ITS', false],
                ] as [$title, $desc, $done])
                    <div class="flex gap-3">
                        <span @class(['mt-0.5 grid size-5 shrink-0 place-items-center rounded-full text-white', 'bg-leaf' => $done, 'bg-line text-muted' => ! $done])>
                            @if ($done)<x-icon name="check" class="size-3" />@else<span class="size-1.5 rounded-full bg-muted"></span>@endif
                        </span>
                        <div>
                            <p class="text-[11px] font-bold {{ $done ? 'text-forest-deep' : 'text-muted' }}">{{ $title }}</p>
                            <p class="text-[9px] text-muted">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <a href="{{ route('buyer.tiba') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[14px] font-bold text-white">
            <x-icon name="check" class="size-4" />
            Pesanan sudah tiba
        </a>
    </main>
@endsection
