@extends('layouts.phone')

@section('title', 'Kalender Produksi')

@php
    $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    $events = [
        ['date' => 12, 'title' => 'Wisuda ITS', 'load' => '150 box', 'time' => '11.00', 'tone' => 'green'],
        ['date' => 14, 'title' => 'Seminar FEB', 'load' => '80 box', 'time' => '10.00', 'tone' => 'mint'],
        ['date' => 15, 'title' => 'Arisan RT 05', 'load' => '50 box', 'time' => '12.00', 'tone' => 'amber'],
        ['date' => 21, 'title' => 'Syukuran Keluarga', 'load' => '30 box', 'time' => '17.00', 'tone' => 'mint'],
    ];
    $busy = [12 => 3, 14 => 2, 15 => 1, 21 => 1];
@endphp

@section('content')
    <x-app-bar title="Kalender Produksi" subtitle="Oktober 2026" :back="route('seller.dashboard')">
        <x-slot:right>
            <a href="#" class="grid size-9 place-items-center rounded-full bg-white text-forest-deep ring-1 ring-line" aria-label="Tambah jadwal">
                <x-icon name="plus" class="size-4" />
            </a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <button class="grid size-8 place-items-center rounded-full bg-mist text-muted"><x-icon name="chevron-left" class="size-4" /></button>
                <p class="text-[13px] font-extrabold text-forest-deep">Oktober 2026</p>
                <button class="grid size-8 place-items-center rounded-full bg-mist text-muted"><x-icon name="chevron-right" class="size-4" /></button>
            </div>

            <div class="mt-4 grid grid-cols-7 gap-1 text-center text-[9px] font-bold text-muted">
                @foreach ($days as $day)
                    <span>{{ $day }}</span>
                @endforeach
            </div>
            <div class="mt-1 grid grid-cols-7 gap-1 text-center">
                @foreach (range(1, 31) as $date)
                    @php($count = $busy[$date] ?? 0)
                    <div @class([
                        'relative grid aspect-square place-items-center rounded-lg text-[10px] font-semibold',
                        'bg-forest text-white' => $date === 12,
                        'bg-mint-soft text-forest' => $count > 0 && $date !== 12,
                        'text-muted' => $count === 0,
                    ])>
                        {{ $date }}
                        @if ($count > 0)
                            <span @class([
                                'absolute bottom-1 size-1 rounded-full',
                                'bg-mint' => $date === 12,
                                'bg-leaf' => $date !== 12,
                            ])></span>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex items-center gap-3 border-t border-line pt-3 text-[9px] font-semibold text-muted">
                <span class="inline-flex items-center gap-1"><span class="size-2 rounded-full bg-forest"></span>Hari ini</span>
                <span class="inline-flex items-center gap-1"><span class="size-2 rounded-full bg-leaf"></span>Ada produksi</span>
                <span class="inline-flex items-center gap-1"><span class="size-2 rounded-full bg-line"></span>Kosong</span>
            </div>
        </section>

        <section class="grid grid-cols-2 gap-2">
            <x-stat-card label="Pesanan bulan ini" value="18" sub="+4 vs September" />
            <x-stat-card label="Kapasitas terpakai" value="72%" sub="dari 1.200 porsi" tone="dark" />
        </section>

        <section>
            <x-section-title title="Jadwal terdekat" action="Lihat semua" href="#" />
            <div class="space-y-2">
                @foreach ($events as $event)
                    <a href="{{ route('seller.pesanan.detail', 'wisuda-its') }}" class="flex items-center gap-3 rounded-2xl border border-line bg-white p-3">
                        <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-mint-soft text-center">
                            <span class="text-[13px] font-extrabold leading-none text-forest">{{ $event['date'] }}</span>
                            <span class="text-[8px] font-semibold text-muted">Okt</span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12px] font-bold text-forest-deep">{{ $event['title'] }}</p>
                            <p class="text-[9px] text-muted">{{ $event['load'] }} · antar {{ $event['time'] }}</p>
                        </div>
                        <x-badge :color="$event['tone']">{{ $event['load'] }}</x-badge>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Kapasitas per tanggal</p>
            <div class="mt-3 space-y-2">
                @foreach ([['12 Okt', 96, 'amber'], ['14 Okt', 54, 'leaf'], ['15 Okt', 38, 'leaf'], ['21 Okt', 22, 'leaf']] as [$date, $percent, $tone])
                    <div>
                        <div class="mb-1 flex items-center justify-between text-[10px] font-semibold">
                            <span class="text-muted">{{ $date }}</span>
                            <span class="{{ $percent > 90 ? 'text-amber' : 'text-forest' }}">{{ $percent }}%</span>
                        </div>
                        <x-progress-bar :value="$percent" :tone="$tone" />
                    </div>
                @endforeach
            </div>
        </section>

        <div class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            Tanggal 12 Okt sudah 96% kapasitas. Pertimbangkan menolak pesanan baru di tanggal tersebut.
        </div>
    </main>
@endsection
