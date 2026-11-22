@extends('layouts.phone')

@section('title', 'Departure Gate')

@php
    $checklist = [
        ['Jumlah box sesuai (150)', true],
        ['Suhu makanan masih hangat', true],
        ['Box tidak rusak / bocor', true],
        ['Semua box sudah dilabel', true],
        ['Foto muatan diambil', false],
        ['QR gate discan', false],
    ];
@endphp

@section('content')
    <x-app-bar title="Departure Gate" subtitle="Wisuda ITS · 150 box" :back="route('kurir.tugas.detail', 'wisuda-its')">
        <x-slot:right>
            <x-badge color="amber">Belum berangkat</x-badge>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="relative overflow-hidden rounded-3xl bg-forest p-5 text-center text-white">
            <div class="pointer-events-none absolute -left-10 -top-12 size-32 rounded-full bg-leaf/40 blur-xl"></div>
            <p class="text-[10px] font-semibold uppercase tracking-wide text-mint/70">Scan QR di gate</p>
            <div class="mx-auto mt-3 grid size-32 place-items-center rounded-2xl bg-white">
                <div class="grid size-24 grid-cols-4 grid-rows-4 gap-1">
                    @foreach (range(1, 16) as $cell)
                        <span class="{{ in_array($cell, [1, 2, 3, 4, 5, 8, 9, 12, 13, 14, 15, 16]) ? 'bg-forest-deep' : 'bg-white' }} rounded-[2px]"></span>
                    @endforeach
                </div>
            </div>
            <p class="mt-3 text-[11px] font-semibold text-mint">PS-GATE-{{ strtoupper(substr(md5('wisuda-its'), 0, 8)) }}</p>
            <p class="mt-0.5 text-[9px] text-mint/70">Arahkan kamera ke QR di dapur seller</p>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Checklist keberangkatan</p>
                <span class="text-[10px] font-bold text-leaf">4/6</span>
            </div>
            <div class="mt-3 space-y-2.5">
                @foreach ($checklist as [$label, $checked])
                    <label class="flex cursor-pointer items-center gap-3">
                        <span @class(['grid size-5 shrink-0 place-items-center rounded-md border-2', 'border-leaf bg-leaf text-white' => $checked, 'border-line bg-white' => ! $checked])>
                            @if ($checked)<x-icon name="check" class="size-3" />@endif
                        </span>
                        <span class="text-[11px] {{ $checked ? 'font-semibold text-forest-deep' : 'text-muted' }}">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Foto muatan</p>
                <span class="text-[9px] text-muted">Wajib</span>
            </div>
            <div class="mt-2 grid grid-cols-2 gap-2">
                <x-photo label="Foto bak kendaraan" class="h-24" />
                <x-photo label="+ Ambil foto" class="h-24 border border-dashed border-line bg-white text-muted" />
            </div>
            <p class="mt-1.5 text-[9px] leading-relaxed text-muted">Pastikan seluruh box terlihat dan kondisi kendaraan aman.</p>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Rute perjalanan</p>
                <span class="text-[10px] font-bold text-forest">12,4 km</span>
            </div>
            <div class="mt-2 space-y-2 text-[10px]">
                <div class="flex items-start gap-2">
                    <span class="mt-1 size-2 shrink-0 rounded-full bg-forest"></span>
                    <div><p class="font-bold text-forest-deep">Pawon Lestari</p><p class="text-muted">Jl. Rungkut Asri 21</p></div>
                </div>
                <div class="ml-[3px] h-6 border-l-2 border-dashed border-line"></div>
                <div class="flex items-start gap-2">
                    <span class="mt-1 size-2 shrink-0 rounded-full bg-amber"></span>
                    <div><p class="font-bold text-forest-deep">Graha ITS</p><p class="text-muted">Kampus ITS Sukolilo · target 10.30</p></div>
                </div>
            </div>
        </section>

        <div class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            Jangan berangkat sebelum semua checklist tercentang. Gate hanya bisa dibuka satu kali per tugas.
        </div>

        <a href="{{ route('kurir.live') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
            <x-icon name="navigation" class="size-4" />
            Mulai perjalanan
        </a>
    </main>
@endsection
