@extends('layouts.phone')

@section('title', 'Daftar Tugas')

@php
    $tasks = config('demo.tasks');
@endphp

@section('content')
    <x-app-bar title="Daftar Tugas" subtitle="3 tugas hari ini" :back="route('kurir.beranda')">
        <x-slot:right>
            <a href="#" class="grid size-9 place-items-center rounded-full bg-white text-forest-deep ring-1 ring-line" aria-label="Filter">
                <x-icon name="sliders" class="size-4" />
            </a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 px-4 py-4">
        <div class="flex gap-1 rounded-2xl bg-white p-1 ring-1 ring-line">
            <span class="flex-1 rounded-xl bg-forest py-2 text-center text-[11px] font-bold text-white">Aktif</span>
            <a href="#" class="flex-1 rounded-xl py-2 text-center text-[11px] font-bold text-muted">Selesai</a>
            <a href="#" class="flex-1 rounded-xl py-2 text-center text-[11px] font-bold text-muted">Batal</a>
        </div>

        <div class="mt-4 grid grid-cols-3 gap-2">
            <x-stat-card label="Hari ini" value="3" sub="tugas" />
            <x-stat-card label="Total muatan" value="260" sub="box" />
            <x-stat-card label="Estimasi" value="38 km" sub="rute" tone="dark" />
        </div>

        <div class="mt-4 space-y-3">
            @foreach ($tasks as $task)
                <div class="rounded-2xl border border-line bg-white p-4">
                    <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-[13px] font-extrabold text-forest-deep">{{ $task['title'] }}</p>
                            <p class="mt-0.5 flex items-center gap-1 text-[10px] text-muted">
                                <x-icon name="location" class="size-3" />
                                {{ $task['address'] }}
                            </p>
                        </div>
                        <x-badge :color="$task['status'] === 'Ditugaskan' ? 'amber' : 'mint'">{{ $task['status'] }}</x-badge>
                    </a>
                    <div class="mt-3 grid grid-cols-3 gap-2 rounded-xl bg-mist p-2 text-center">
                        <div><p class="text-[9px] text-muted">Muatan</p><p class="text-[11px] font-bold text-forest-deep">{{ $task['load'] }}</p></div>
                        <div><p class="text-[9px] text-muted">Jarak</p><p class="text-[11px] font-bold text-forest-deep">{{ $task['distance'] }}</p></div>
                        <div><p class="text-[9px] text-muted">Target</p><p class="text-[11px] font-bold text-forest-deep">{{ str_replace('Target ', '', $task['eta']) }}</p></div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="flex-1 rounded-xl border border-line py-2.5 text-center text-[11px] font-bold text-muted">Detail</a>
                        <a href="{{ route('kurir.departure') }}" class="flex-1 rounded-xl bg-forest py-2.5 text-center text-[11px] font-bold text-white">Berangkat</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 rounded-2xl border border-dashed border-line p-4 text-center text-[10px] leading-relaxed text-muted">
            Tugas baru akan muncul otomatis saat seller menugaskanmu. Pastikan status Online.
        </div>
    </main>

    <x-bottom-nav role="kurir" active="kurir.tugas" />
@endsection
