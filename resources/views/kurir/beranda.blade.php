@extends('layouts.phone')

@section('title', 'Beranda Kurir')

@php
    $jam = (int) now()->format('H');
    $sapaan = match (true) {
        $jam < 11 => 'Selamat pagi',
        $jam < 15 => 'Selamat siang',
        $jam < 18 => 'Selamat sore',
        default => 'Selamat malam',
    };
    $sudahBerangkat = $active && $active['waktu_berangkat'] !== null;
@endphp

@section('content')
    <div class="flex-1 bg-cream">
        <header class="flex items-center gap-3 px-5 pb-2 pt-5">
            <span class="grid size-11 shrink-0 place-items-center rounded-full bg-mint-soft text-[15px] font-semibold text-forest">{{ strtoupper(substr($courier->nama, 0, 2)) }}</span>
            <div class="min-w-0 flex-1">
                <p class="text-[12px] text-muted">{{ $sapaan }},</p>
                <h1 class="truncate text-[17px] font-semibold text-ink">{{ $courier->nama }}</h1>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-[12px] font-medium text-forest shadow-soft">
                <span class="relative flex size-2"><span class="absolute inset-0 rounded-full bg-leaf animate-ripple"></span><span class="relative size-2 rounded-full bg-leaf"></span></span>
                Online
            </span>
        </header>

        <main class="stagger space-y-6 px-5 pb-6 pt-3">
            @if ($active)
                <section class="relative overflow-hidden rounded-[28px] bg-[linear-gradient(150deg,var(--color-forest-night)_0%,var(--color-forest)_60%,var(--color-leaf)_100%)] p-5 text-white">
                    <div class="pointer-events-none absolute -right-8 -top-8 size-40 rounded-full bg-white/5"></div>
                    <a href="{{ route('kurir.tugas.detail', $active['id']) }}" class="relative block">
                        <div class="flex items-start gap-3">
                            <div class="min-w-0 flex-1 space-y-1">
                                <p class="text-[12px] text-mint/80">{{ $sudahBerangkat ? 'Sedang diantar' : 'Tugas berikutnya' }} · {{ $active['kode'] }}</p>
                                <p class="text-[19px] font-semibold leading-snug">{{ $active['title'] }}</p>
                            </div>
                            <span class="relative grid size-14 shrink-0 place-items-center">
                                <span class="absolute inset-0 rounded-full bg-mint/25 animate-ripple"></span>
                                <span class="relative grid size-12 place-items-center rounded-full bg-white/15 animate-float">
                                    <x-icon name="bike" class="size-6 text-mint" />
                                </span>
                            </span>
                        </div>

                        <div class="mt-4 space-y-2.5 text-[13px]">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 size-2 shrink-0 rounded-full bg-mint"></span>
                                <span class="text-mint/90">{{ $active['seller'] }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 size-2 shrink-0 rounded-full bg-amber"></span>
                                <span class="line-clamp-2">{{ $active['address'] }}</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-4 text-[12px] text-mint/80">
                            <span class="inline-flex items-center gap-1.5"><x-icon name="package" class="size-4" />{{ $active['load'] }}</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="size-4" />{{ $active['eta'] }}</span>
                        </div>
                    </a>

                    <a href="{{ $sudahBerangkat ? route('kurir.live', $active['id']) : route('kurir.departure', $active['id']) }}" class="relative mt-5 flex items-center justify-between rounded-full bg-mint py-1.5 pl-5 pr-1.5 text-[14px] font-semibold text-forest-deep transition active:scale-[0.98]">
                        {{ $sudahBerangkat ? 'Lanjutkan perjalanan' : 'Mulai antar' }}
                        <span class="grid size-10 place-items-center rounded-full bg-forest text-mint"><x-icon name="arrow-right" class="size-4" /></span>
                    </a>
                </section>
            @else
                <section class="flex flex-col items-center gap-3 rounded-[28px] bg-white/70 px-6 py-8 text-center">
                    <span class="relative grid size-20 place-items-center">
                        <span class="absolute inset-2 rounded-full bg-mint animate-ripple"></span>
                        <span class="relative grid size-16 place-items-center rounded-full bg-mint-soft text-forest animate-float"><x-icon name="bike" class="size-8" /></span>
                    </span>
                    <p class="text-[16px] font-semibold text-ink">Belum ada tugas aktif</p>
                    <p class="text-[13px] text-muted">Santai dulu. Tugas baru muncul di sini saat seller menugaskanmu.</p>
                </section>
            @endif

            <section class="surface grid grid-cols-3 divide-x divide-line py-4">
                @foreach ([[$jumlahHariIni, 'Tugas aktif'], [$active ? 1 : 0, 'Sedang jalan'], [$totalMuatan, 'Box dibawa']] as [$nilai, $label])
                    <div class="flex flex-col items-center gap-0.5 px-2">
                        <span class="tabular text-[22px] font-semibold text-ink" data-count-to="{{ $nilai }}">{{ $nilai }}</span>
                        <span class="text-[11px] text-muted">{{ $label }}</span>
                    </div>
                @endforeach
            </section>

            <section class="space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-[15px] font-semibold text-ink">Antrean berikutnya</h2>
                    <a href="{{ route('kurir.tugas') }}" class="text-[12px] font-medium text-leaf-dark">Semua tugas</a>
                </div>
                @if (count($tasks) > 1)
                    <div class="surface divide-y divide-line/70 px-4">
                        @foreach (array_slice($tasks, 1) as $task)
                            <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="flex items-center gap-3 py-3.5">
                                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-mint-faint text-forest"><x-icon name="location" class="size-[18px]" /></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-[14px] font-medium text-ink">{{ $task['title'] }}</span>
                                    <span class="block truncate text-[12px] text-muted">{{ $task['load'] }} · {{ $task['eta'] }}</span>
                                </span>
                                <x-icon name="chevron-right" class="size-4 shrink-0 text-muted" />
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="rounded-[24px] bg-white/60 px-4 py-5 text-center text-[13px] text-muted">Tidak ada antrean lain.</p>
                @endif
            </section>

            <p class="flex items-start gap-2 px-1 text-[12px] leading-relaxed text-muted">
                <x-icon name="camera" class="mt-0.5 size-4 shrink-0 text-leaf" />
                Foto muatan sebelum berangkat, lalu unggah bukti setelah serah terima.
            </p>

            <form action="{{ route('logout') }}" method="post" class="text-center">
                @csrf
                <button type="submit" class="text-[13px] font-medium text-muted underline-offset-4 hover:underline">Keluar dari akun</button>
            </form>
        </main>
    </div>

    <x-bottom-nav role="kurir" active="kurir.beranda" />
@endsection
