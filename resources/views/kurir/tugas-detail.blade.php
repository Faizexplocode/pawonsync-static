@extends('layouts.phone')

@section('title', 'Detail Tugas')

@php
    $steps = [
        ['Tugas diterima', '08.40 · Pawon Lestari', true],
        ['Tiba di dapur', '09.20 · Pawon Lestari', true],
        ['Muatan dicek & difoto', '09.45', true],
        ['Berangkat', '09.50 · Departure gate', false],
        ['Tiba di venue', '10.30 · Graha ITS', false],
    ];
@endphp

@section('content')
    <x-app-bar title="Detail Tugas" :subtitle="'Tugas #' . strtoupper(substr($task['id'], 0, 6))" :back="route('kurir.tugas')">
        <x-slot:right>
            <x-badge color="amber">{{ $task['status'] }}</x-badge>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="relative h-40 overflow-hidden rounded-3xl border border-line bg-mint-soft">
            <div class="absolute inset-0 opacity-60 [background-image:linear-gradient(#c7e6d5_1px,transparent_1px),linear-gradient(90deg,#c7e6d5_1px,transparent_1px)] [background-size:26px_26px]"></div>
            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[9px] font-bold text-forest-deep">Pawon Lestari</span>
            <span class="absolute bottom-3 right-3 rounded-full bg-white/90 px-2.5 py-1 text-[9px] font-bold text-forest-deep">Graha ITS</span>
            <span class="absolute left-[22%] top-1/2 size-3 -translate-y-1/2 rounded-full bg-forest ring-4 ring-forest/20"></span>
            <span class="absolute right-[18%] top-[38%] size-3 rounded-full bg-amber ring-4 ring-amber/20"></span>
            <div class="absolute left-[26%] top-[52%] h-1 w-[48%] -rotate-3 rounded-full bg-leaf/40"></div>
            <span class="absolute left-1/2 top-1/2 grid size-9 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-white text-leaf shadow-lg ring-1 ring-line">
                <x-icon name="bike" class="size-5" />
            </span>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[15px] font-extrabold text-forest-deep">{{ $task['title'] }}</p>
                    <p class="mt-0.5 text-[10px] text-muted">{{ $task['address'] }}</p>
                </div>
                <span class="text-right">
                    <span class="block text-[9px] font-semibold text-muted">Jarak</span>
                    <span class="block text-[12px] font-bold text-forest">{{ $task['distance'] }}</span>
                </span>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2">
                <div class="rounded-xl bg-mist p-3">
                    <p class="text-[9px] font-semibold text-muted">Muatan</p>
                    <p class="mt-0.5 text-[12px] font-bold text-forest-deep">{{ $task['load'] }}</p>
                    <p class="text-[9px] text-muted">4 karung · 150 box</p>
                </div>
                <div class="rounded-xl bg-mist p-3">
                    <p class="text-[9px] font-semibold text-muted">Target tiba</p>
                    <p class="mt-0.5 text-[12px] font-bold text-forest-deep">10.30 WIB</p>
                    <p class="text-[9px] text-muted">Sisa 45 menit</p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Kontak</p>
            <div class="mt-2 space-y-2">
                <div class="flex items-center gap-3 rounded-xl bg-mist p-3">
                    <span class="grid size-9 place-items-center rounded-full bg-white text-[10px] font-extrabold text-forest ring-1 ring-line">PL</span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold text-ink">Pawon Lestari</p>
                        <p class="text-[9px] text-muted">Seller · 0812 3456 7890</p>
                    </div>
                    <a href="#" class="grid size-8 place-items-center rounded-full bg-mint text-forest"><x-icon name="phone" class="size-4" /></a>
                </div>
                <div class="flex items-center gap-3 rounded-xl bg-mist p-3">
                    <span class="grid size-9 place-items-center rounded-full bg-white text-[10px] font-extrabold text-forest ring-1 ring-line">BD</span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold text-ink">Bu Diana</p>
                        <p class="text-[9px] text-muted">Penerima · 0812 3456 7890</p>
                    </div>
                    <a href="#" class="grid size-8 place-items-center rounded-full bg-mint text-forest"><x-icon name="phone" class="size-4" /></a>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Progres tugas</p>
            <div class="mt-3 space-y-3">
                @foreach ($steps as [$title, $desc, $done])
                    <div class="flex gap-3">
                        <span @class(['mt-0.5 grid size-5 shrink-0 place-items-center rounded-full text-white', 'bg-leaf' => $done, 'bg-line text-muted' => ! $done])>
                            @if ($done)<x-icon name="check" class="size-3" />@else<span class="size-1.5 rounded-full bg-muted"></span>@endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold {{ $done ? 'text-forest-deep' : 'text-muted' }}">{{ $title }}</p>
                            <p class="text-[9px] text-muted">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl bg-mint-soft p-3 text-[10px] leading-relaxed text-forest">
            <p class="font-bold">Instruksi seller</p>
            <p class="mt-0.5 text-leaf-dark">Masuk lewat gerbang barat, hubungi Pak Reza di lobi Graha ITS. Parkir di area belakang.</p>
        </section>

        <a href="{{ route('kurir.departure') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
            <x-icon name="navigation" class="size-4" />
            Buka departure gate
        </a>
    </main>
@endsection
