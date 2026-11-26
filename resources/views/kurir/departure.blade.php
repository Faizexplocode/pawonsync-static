@extends('layouts.phone')

@section('title', 'Departure Gate')

@php
    $belumBerangkat = $pengiriman->status->value === 'ditugaskan';
    $siapBerangkat = collect($checklist)->every(fn (array $item) => $item[1] || str_starts_with($item[0], 'Jumlah box'));
@endphp

@section('content')
    <div class="relative flex flex-1 flex-col bg-cream">
        <x-peta-rute
            :alamat="$task['address']"
            :lokasi-url="$belumBerangkat ? null : route('kurir.live.lokasi', $task['id'])"
            tinggi="h-[52vh] min-h-[340px]"
        >
            <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="absolute left-4 top-4 z-[500] grid size-11 place-items-center rounded-full bg-white text-ink shadow-float" aria-label="Kembali">
                <x-icon name="chevron-left" class="size-5" />
            </a>
            <span class="absolute left-1/2 top-5 z-[500] -translate-x-1/2 rounded-full bg-white/95 px-4 py-2 text-[12px] font-medium text-ink shadow-float">Departure Gate</span>
            <span class="absolute bottom-11 left-4 z-[500] inline-flex max-w-[70%] items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-medium text-forest shadow-float">
                <span class="size-1.5 shrink-0 rounded-full bg-leaf"></span>
                <span class="truncate" data-peta="status">Menyiapkan peta…</span>
            </span>
        </x-peta-rute>

        <section class="relative z-[600] -mt-7 flex-1 space-y-5 rounded-t-[30px] bg-cream px-5 pb-6 pt-3 shadow-[0_-12px_30px_rgba(6,48,31,0.08)]">
            <span class="mx-auto block h-1 w-10 rounded-full bg-line"></span>

            <div class="space-y-1">
                <p class="text-[12px] text-muted">{{ $task['kode'] }} · {{ $task['status'] }}</p>
                <h1 class="text-[19px] font-semibold leading-snug text-ink">{{ $task['title'] }}</h1>
                <p class="text-[13px] text-muted">{{ $task['address'] }}</p>
            </div>

            <div class="surface grid grid-cols-3 divide-x divide-line py-3.5 text-center">
                <div class="px-1">
                    <p class="tabular text-[17px] font-semibold text-ink" data-peta="jarak">–</p>
                    <p class="text-[11px] text-muted">Jarak</p>
                </div>
                <div class="px-1">
                    <p class="tabular text-[17px] font-semibold text-forest" data-peta="eta">–</p>
                    <p class="text-[11px] text-muted">Perkiraan</p>
                </div>
                <div class="px-1">
                    <p class="tabular text-[17px] font-semibold text-ink">{{ $task['load'] }}</p>
                    <p class="text-[11px] text-muted">Muatan</p>
                </div>
            </div>

            <div class="space-y-3">
                <h2 class="text-[15px] font-semibold text-ink">Cek sebelum berangkat</h2>
                <ul class="space-y-2.5">
                    @foreach ($checklist as [$label, $checked])
                        <li class="flex items-center gap-3 text-[14px]">
                            <span @class([
                                'grid size-6 shrink-0 place-items-center rounded-full',
                                'bg-leaf text-white' => $checked,
                                'bg-white text-line ring-1 ring-line' => ! $checked,
                            ])><x-icon name="check" class="size-3.5" /></span>
                            <span @class(['text-ink' => $checked, 'text-muted' => ! $checked])>{{ $label }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="flex items-start gap-2 text-[12px] leading-relaxed text-muted">
                <x-icon name="info" class="mt-0.5 size-4 shrink-0 text-leaf" />
                Saat berangkat, buyer menerima notifikasi dan posisimu dibagikan otomatis selama perjalanan.
            </p>

            @if ($belumBerangkat)
                <form action="{{ route('kurir.departure.mulai', $task['id']) }}" method="post">
                    @csrf
                    <button type="submit" class="flex w-full items-center rounded-full bg-[linear-gradient(140deg,var(--color-forest-night)_0%,var(--color-forest)_60%,var(--color-leaf)_100%)] p-1.5 text-white shadow-float transition active:scale-[0.98]">
                        <span class="grid size-12 shrink-0 place-items-center rounded-full bg-mint text-forest"><x-icon name="arrow-right" class="size-5" /></span>
                        <span class="flex-1 pr-12 text-center text-[15px] font-semibold">{{ $siapBerangkat ? 'Berangkat sekarang' : 'Coba berangkat' }}</span>
                    </button>
                </form>
            @else
                <a href="{{ route('kurir.live', $task['id']) }}" class="flex w-full items-center justify-center gap-2 rounded-full bg-forest py-4 text-[15px] font-semibold text-white">
                    <x-icon name="navigation" class="size-4" />
                    Buka navigasi perjalanan
                </a>
            @endif
        </section>
    </div>
@endsection
