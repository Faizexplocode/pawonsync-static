@extends('layouts.phone')

@section('title', 'Kalender Produksi')

@php
    $namaHari = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    $hariIni = now()->format('Y-m-d');
    $sel = array_merge(array_fill(0, $offsetHari - 1, null), range(1, $hariDalamBulan));
    $baris = array_chunk($sel, 7);
@endphp

@section('content')
    <x-app-bar title="Kalender & Jadwal" :subtitle="$bulanLabel" :back="route('seller.dashboard')">
        <x-slot:right>
            <a href="{{ route('seller.kalender') }}" class="text-caption font-semibold text-leaf-dark">Hari ini</a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-3.5 p-4">
        <section class="space-y-2 rounded-[18px] border border-line bg-white px-3 py-3.5">
            <div class="flex items-center justify-between">
                <a href="{{ route('seller.kalender', ['bulan' => $awal->copy()->subMonth()->format('Y-m')]) }}" class="text-[20px] font-medium text-forest" aria-label="Bulan sebelumnya">‹</a>
                <p class="text-[15px] font-bold text-ink">{{ $bulanLabel }}</p>
                <a href="{{ route('seller.kalender', ['bulan' => $awal->copy()->addMonth()->format('Y-m')]) }}" class="text-[20px] font-medium text-forest" aria-label="Bulan berikutnya">›</a>
            </div>
            <div class="flex text-center text-[10px] font-medium text-muted">
                @foreach ($namaHari as $hari)
                    <span class="flex-1">{{ $hari }}</span>
                @endforeach
            </div>
            @foreach ($baris as $pekan)
                <div class="flex">
                    @foreach (array_pad($pekan, 7, null) as $tanggalKe)
                        @if ($tanggalKe === null)
                            <span class="flex-1"></span>
                        @else
                            @php
                                $jumlah = $busy[$tanggalKe] ?? 0;
                                $tanggal = $awal->copy()->day($tanggalKe)->format('Y-m-d');
                                $adalahHariIni = $tanggal === $hariIni;
                            @endphp
                            <div @class(['flex flex-1 flex-col items-center gap-[3px] rounded-xl py-1.5', 'bg-forest' => $adalahHariIni])>
                                <span @class(['text-[13px]', 'font-bold text-white' => $adalahHariIni, 'font-medium text-ink' => ! $adalahHariIni])>{{ $tanggalKe }}</span>
                                <span @class([
                                    'size-1.5 rounded-full',
                                    'bg-transparent' => $jumlah === 0,
                                    'bg-leaf-light' => $jumlah === 1,
                                    'bg-leaf' => $jumlah === 2,
                                    'bg-forest-deep' => $jumlah > 2,
                                    'ring-1 ring-mint' => $adalahHariIni && $jumlah > 0,
                                ])></span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
            <div class="flex items-center gap-3 pt-1 text-[10px] font-medium text-muted">
                <span class="inline-flex items-center gap-1"><span class="size-2 rounded-full bg-leaf-light"></span>Rendah</span>
                <span class="inline-flex items-center gap-1"><span class="size-2 rounded-full bg-leaf"></span>Sedang</span>
                <span class="inline-flex items-center gap-1"><span class="size-2 rounded-full bg-forest-deep"></span>Padat</span>
            </div>
        </section>

        <div class="flex gap-2.5">
            <x-stat-card label="Pesanan bulan ini" :value="$totalPesanan" sub="pesanan" />
            <x-stat-card tone="dark" label="Kapasitas terpakai" :value="$kapasitasPersen.'%'" :sub="$totalPorsi.' porsi'" />
        </div>

        <p class="text-title font-bold text-ink">Jadwal pengiriman</p>
        <div>
            @forelse ($events as $event)
                <a href="{{ route('seller.pesanan.detail', $event['id']) }}" class="block">
                    <x-timeline-item :title="$event['time'].' · '.$event['title']" :note="$event['load']" :time="$event['date'].' '.$awal->locale('id')->translatedFormat('M')" :done="false" :last="$loop->last" />
                </a>
            @empty
                <x-empty-state title="Belum ada jadwal">Belum ada jadwal produksi bulan ini.</x-empty-state>
            @endforelse
        </div>

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Alokasi kapasitas</p>
            @forelse ($kapasitas as $kapasitasItem)
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-muted">{{ $kapasitasItem->tanggal->locale('id')->translatedFormat('j M') }}</span>
                        <span @class(['font-semibold', 'text-amber' => $kapasitasItem->persenTerpakai() > 90, 'text-forest' => $kapasitasItem->persenTerpakai() <= 90])>{{ $kapasitasItem->terpakai }} / {{ $kapasitasItem->kapasitas_maks }} porsi</span>
                    </div>
                    <x-progress-bar :value="$kapasitasItem->persenTerpakai()" :tone="$kapasitasItem->persenTerpakai() > 90 ? 'amber' : 'leaf'" />
                </div>
            @empty
                <p class="text-caption text-muted">Belum ada data kapasitas. Kapasitas terbentuk otomatis saat buyer checkout.</p>
            @endforelse
        </section>
    </main>
@endsection
