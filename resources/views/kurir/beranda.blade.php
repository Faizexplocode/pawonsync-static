@extends('layouts.phone')

@section('title', 'Beranda Kurir')

@section('content')
    <header class="bg-[linear-gradient(148deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)]">
        <x-status-bar tone="light" />
        <div class="flex items-center gap-3 px-4 pb-[18px] pt-2.5">
            <div class="min-w-0 flex-1 space-y-0.5">
                <h1 class="truncate text-[18px] font-bold text-white">Halo, {{ $courier->nama }}</h1>
                <p class="truncate text-caption text-mint">{{ $courier->email }}</p>
            </div>
            <span class="text-caption font-semibold text-mint">Online</span>
        </div>
        <div class="flex gap-2.5 px-4 pb-[18px]">
            @foreach ([[$jumlahHariIni, 'Tugas hari ini'], [$active ? 1 : 0, 'Sedang aktif'], [$totalMuatan, 'Box dibawa']] as [$nilai, $label])
                <div class="flex-1 rounded-[14px] bg-white/15 px-3 py-2.5">
                    <p class="text-[20px] font-bold text-white">{{ $nilai }}</p>
                    <p class="text-[10px] font-medium text-mint">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </header>

    <main class="flex-1 space-y-4 p-4">
        <p class="text-title font-bold text-ink">Tugas Aktif</p>
        @if ($active)
            <section class="space-y-2 rounded-[20px] bg-[linear-gradient(150deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)] px-[18px] py-4">
                <a href="{{ route('kurir.tugas.detail', $active['id']) }}" class="block space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <p class="truncate text-[17px] font-bold text-white">{{ $active['title'] }}</p>
                        <x-badge color="mint" class="shrink-0">{{ $active['status'] }}</x-badge>
                    </div>
                    <p class="text-[13px] text-mint">{{ $active['address'] }}</p>
                    <div class="flex items-center justify-between text-[13px] font-semibold text-white">
                        <span>{{ $active['load'] }}</span>
                        <span>Target {{ $active['eta'] }}</span>
                        <span>{{ $active['kode'] }}</span>
                    </div>
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('kurir.departure', $active['id']) }}" class="flex-1 rounded-[14px] bg-mint px-3.5 py-3 text-center text-body font-bold text-forest-deep">Mulai Tugas</a>
                    <a href="{{ route('kurir.live', $active['id']) }}" class="rounded-[14px] border border-mint-mid px-4 py-3 text-center text-body font-semibold text-white">Live</a>
                </div>
            </section>
        @else
            <x-empty-state title="Belum ada tugas aktif">Tugas baru muncul saat seller menugaskanmu.</x-empty-state>
        @endif

        <x-section-title title="Tugas Berikutnya" :href="route('kurir.tugas')" action="Lihat Semua" />
        <div class="-mt-3 space-y-3">
            @forelse (array_slice($tasks, 1) as $task)
                <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="block space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
                    <div class="flex items-center justify-between gap-2">
                        <p class="truncate text-body font-bold text-ink">{{ $task['title'] }}</p>
                        <x-badge color="green" class="shrink-0">Target {{ $task['eta'] }}</x-badge>
                    </div>
                    <p class="text-caption text-muted">{{ $task['address'] }} · {{ $task['load'] }}</p>
                </a>
            @empty
                <p class="rounded-[18px] border border-dashed border-line bg-white p-4 text-center text-caption text-muted">Tidak ada tugas lain.</p>
            @endforelse
        </div>

        <x-alert tone="warning" title="Pengingat">Selalu foto muatan sebelum berangkat dan unggah bukti setelah serah terima.</x-alert>

        <form action="{{ route('logout') }}" method="post">
            @csrf
            <x-button type="submit" variant="outline">Keluar</x-button>
        </form>
    </main>

    <x-bottom-nav role="kurir" active="kurir.beranda" />
@endsection
