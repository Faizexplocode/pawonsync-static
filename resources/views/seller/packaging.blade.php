@extends('layouts.phone')

@section('title', 'Packaging')

@php
    $checklist = [
        ['Nasi box tertutup rapat', true],
        ['Label nama & jumlah porsi', true],
        ['Sambal dipisah (20 box)', true],
        ['Sendok & tisu masuk', false],
        ['Box dihitung ulang', false],
        ['Foto keseluruhan box', false],
    ];
@endphp

@section('content')
    <x-app-bar title="Packaging" subtitle="Wisuda ITS · 150 box" :back="route('seller.milestone')">
        <x-slot:right>
            <a href="#" class="rounded-full bg-mint-soft px-3 py-1.5 text-[10px] font-bold text-forest">Cetak label</a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="grid grid-cols-3 gap-2">
            <x-stat-card label="Target" value="150" sub="box" />
            <x-stat-card label="Selesai" value="98" sub="65%" />
            <x-stat-card label="Sisa" value="52" sub="box" tone="dark" />
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Progres packing</p>
                <span class="text-[10px] font-bold text-leaf">65%</span>
            </div>
            <div class="mt-2"><x-progress-bar :value="65" /></div>
            <div class="mt-3 grid grid-cols-2 gap-2 text-[10px]">
                <div class="rounded-xl bg-mist p-2.5">
                    <p class="font-semibold text-muted">Nasi box</p>
                    <p class="mt-0.5 font-bold text-forest-deep">98 / 150</p>
                </div>
                <div class="rounded-xl bg-mist p-2.5">
                    <p class="font-semibold text-muted">Es teh</p>
                    <p class="mt-0.5 font-bold text-forest-deep">98 / 150</p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Checklist packaging</p>
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
                <p class="text-[12px] font-bold text-forest-deep">Dokumentasi</p>
                <span class="text-[9px] text-muted">Min. 1 foto</span>
            </div>
            <div class="mt-2 grid grid-cols-2 gap-2">
                <x-photo label="Foto box selesai" class="h-24" />
                <x-photo label="+ Ambil foto" class="h-24 border border-dashed border-line bg-white text-muted" />
            </div>
            <label class="mt-3 block">
                <span class="mb-1.5 block text-[11px] font-semibold text-forest-deep">Catatan packing</span>
                <textarea rows="2" placeholder="Contoh: 20 box tanpa sambal, label A1–A20..." class="w-full rounded-xl border border-line bg-mist px-3 py-2.5 text-[11px] text-ink outline-none placeholder:text-muted/70 focus:border-leaf focus:ring-2 focus:ring-mint"></textarea>
            </label>
        </section>

        <section class="rounded-2xl bg-mint-soft p-3 text-[10px] leading-relaxed text-forest">
            <p class="font-bold">Tips packaging</p>
            <p class="mt-0.5 text-leaf-dark">Susun box per 25 pcs agar mudah dihitung kurir. Tempel label jumlah porsi di box paling depan.</p>
        </section>

        <a href="{{ route('seller.kurir') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
            <x-icon name="truck" class="size-4" />
            Tandai siap &amp; panggil kurir
        </a>
    </main>
@endsection
