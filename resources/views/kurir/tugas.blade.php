@extends('layouts.phone')

@section('title', 'Daftar Tugas')

@section('content')
    <div class="flex-1 bg-cream">
        <header class="px-5 pb-2 pt-5">
            <p class="text-[12px] text-muted">{{ $jumlahAktif }} tugas aktif</p>
            <h1 class="text-[20px] font-semibold text-ink">Tugas pengiriman</h1>
        </header>

        <main class="stagger space-y-4 px-5 pb-6 pt-3">
            <div class="no-scrollbar -mx-5 flex gap-2 overflow-x-auto px-5">
                @foreach (['aktif' => 'Aktif ('.$jumlahAktif.')', 'selesai' => 'Selesai', 'batal' => 'Batal'] as $key => $label)
                    <a href="{{ route('kurir.tugas', ['tab' => $key]) }}" @class([
                        'shrink-0 rounded-full px-4 py-2 text-[13px] transition',
                        'bg-forest font-medium text-white' => $tab === $key,
                        'bg-white text-muted shadow-soft' => $tab !== $key,
                    ])>{{ $label }}</a>
                @endforeach
            </div>

            @forelse ($tasks as $task)
                <section class="surface space-y-4 p-4">
                    <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="block space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-[15px] font-semibold text-ink">{{ $task['title'] }}</p>
                                <p class="text-[11px] text-muted">{{ $task['kode'] }} · {{ $task['load'] }}</p>
                            </div>
                            <x-badge :color="$task['tone']" class="shrink-0">{{ $task['status'] }}</x-badge>
                        </div>
                        <div class="relative space-y-2.5 pl-5 text-[13px]">
                            <span class="absolute left-[3px] top-2 h-[calc(100%-16px)] border-l border-dashed border-line"></span>
                            <p class="relative text-muted"><span class="absolute -left-5 top-1.5 size-2 rounded-full bg-leaf"></span>{{ $task['seller'] }}</p>
                            <p class="relative text-ink"><span class="absolute -left-5 top-1.5 size-2 rounded-full bg-amber"></span>{{ $task['address'] }}</p>
                        </div>
                    </a>
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center gap-1.5 text-[12px] font-medium text-forest"><x-icon name="clock" class="size-4" />{{ $task['eta'] }}</span>
                        @if ($task['status'] === 'Ditugaskan')
                            <a href="{{ route('kurir.departure', $task['id']) }}" class="rounded-full bg-forest px-4 py-2 text-[12px] font-medium text-white">Berangkat</a>
                        @elseif ($task['status'] === 'Dalam Perjalanan')
                            <a href="{{ route('kurir.live', $task['id']) }}" class="rounded-full bg-forest px-4 py-2 text-[12px] font-medium text-white">Lihat peta</a>
                        @elseif ($task['status'] === 'Tiba di Venue')
                            <a href="{{ route('kurir.bukti', $task['id']) }}" class="rounded-full bg-mint-soft px-4 py-2 text-[12px] font-medium text-forest">Lihat bukti</a>
                        @endif
                    </div>
                </section>
            @empty
                <div class="flex flex-col items-center gap-2 rounded-[24px] bg-white/60 px-6 py-10 text-center">
                    <span class="grid size-14 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="package" class="size-6" /></span>
                    <p class="text-[15px] font-semibold text-ink">Belum ada tugas</p>
                    <p class="text-[13px] text-muted">Tidak ada tugas pada tab ini.</p>
                </div>
            @endforelse
        </main>
    </div>

    <x-bottom-nav role="kurir" active="kurir.tugas" />
@endsection
