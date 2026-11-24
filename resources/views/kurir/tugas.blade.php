@extends('layouts.phone')

@section('title', 'Daftar Tugas')

@section('content')
    <x-app-bar title="Tugas Pengiriman" :subtitle="$jumlahAktif.' tugas aktif'" :back="route('kurir.beranda')" />

    <main class="flex-1 space-y-3.5 p-4">
        <div class="flex">
            @foreach (['aktif' => 'Hari ini', 'selesai' => 'Selesai', 'batal' => 'Batal'] as $key => $label)
                <a href="{{ route('kurir.tugas', ['tab' => $key]) }}" class="flex flex-1 flex-col items-center gap-2 py-0.5">
                    <span @class(['text-[13px]', 'font-bold text-forest' => $tab === $key, 'font-medium text-muted' => $tab !== $key])>{{ $label }}{{ $key === 'aktif' ? ' ('.$jumlahAktif.')' : '' }}</span>
                    <span @class(['h-[3px] w-full rounded-sm', 'bg-leaf-dark' => $tab === $key, 'bg-line' => $tab !== $key])></span>
                </a>
            @endforeach
        </div>

        @forelse ($tasks as $task)
            <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
                <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="block space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1 space-y-px">
                            <p class="truncate text-[15px] font-bold text-ink">{{ $task['title'] }}</p>
                            <p class="text-[11px] font-medium text-muted">{{ $task['kode'] }}</p>
                        </div>
                        <x-badge :color="$task['tone']" class="shrink-0">{{ $task['status'] }}</x-badge>
                    </div>
                    <p class="text-caption text-ink">{{ $task['address'] }}</p>
                </a>
                <div class="h-px bg-line"></div>
                <div class="flex items-center justify-between gap-2">
                    <div class="space-y-0.5">
                        <p class="text-caption font-medium text-muted">{{ $task['load'] }}</p>
                        <p class="text-caption font-bold text-forest">Target {{ $task['eta'] }}</p>
                    </div>
                    @if (in_array($task['status'], ['Ditugaskan', 'Dalam Perjalanan'], true))
                        <a href="{{ route('kurir.departure', $task['id']) }}" class="rounded-full bg-forest px-3.5 py-2.5 text-caption font-semibold text-white">Berangkat</a>
                    @elseif ($task['status'] === 'Tiba di Venue')
                        <a href="{{ route('kurir.bukti', $task['id']) }}" class="rounded-full bg-forest px-3.5 py-2.5 text-caption font-semibold text-white">Upload Bukti</a>
                    @else
                        <a href="{{ route('kurir.tugas.detail', $task['id']) }}" class="rounded-full border border-line bg-white px-3.5 py-2.5 text-caption font-medium text-ink">Detail</a>
                    @endif
                </div>
            </section>
        @empty
            <x-empty-state title="Belum ada tugas">Tidak ada tugas pada tab ini.</x-empty-state>
        @endforelse

        <p class="text-center text-caption font-medium text-muted">{{ count($tasks) }} tugas ditampilkan</p>
    </main>

    <x-bottom-nav role="kurir" active="kurir.tugas" />
@endsection
