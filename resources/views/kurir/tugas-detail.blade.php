@extends('layouts.phone')

@section('title', 'Detail Tugas')

@section('content')
    <x-app-bar title="Detail Tugas" :subtitle="$task['kode']" :back="route('kurir.tugas')">
        <x-slot:right>
            <span class="text-caption font-semibold text-leaf-dark">{{ $task['status'] }}</span>
        </x-slot:right>
    </x-app-bar>

    <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
        <x-peta-rute :alamat="$task['address']" :lacak="false" tinggi="h-[200px]" class="rounded-[24px] shadow-soft">
            <span class="absolute bottom-3 left-3 z-[500] inline-flex max-w-[80%] items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-medium text-forest shadow-float">
                <x-icon name="location" class="size-3.5 shrink-0" />
                <span class="truncate">{{ $task['address'] }}</span>
            </span>
        </x-peta-rute>

        <section class="space-y-2 surface px-4 py-3.5">
            <p class="text-caption font-medium text-muted">Alamat venue</p>
            <p class="text-body font-semibold text-ink">{{ $task['address'] }}</p>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Penerima</span>
                <span class="font-semibold text-ink">{{ $task['buyer'] }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Seller</span>
                <span class="font-semibold text-ink">{{ $task['seller'] }}</span>
            </div>
        </section>

        <section class="space-y-2 surface px-4 py-3.5">
            <p class="text-[15px] font-semibold text-ink">Rincian muatan</p>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Jumlah box</span>
                <span class="font-semibold text-ink">{{ $task['load'] }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Jam target tiba</span>
                <span class="font-semibold text-forest">{{ $task['eta'] }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Kode pesanan</span>
                <span class="font-semibold text-ink">{{ $task['kode'] }}</span>
            </div>
        </section>

        <section class="space-y-1.5 surface px-4 py-3.5">
            <p class="text-caption font-medium text-muted">Catatan khusus</p>
            <p class="text-[13px] text-ink">Hubungi seller sebelum berangkat dan pastikan semua box sudah dicek sesuai jumlah pesanan.</p>
        </section>

        <p class="text-[15px] font-semibold text-ink">Progres tugas</p>
        <div>
            @foreach ($steps as [$title, $desc, $done])
                <x-timeline-item :title="$title" :note="$desc" :done="$done" :last="$loop->last" />
            @endforeach
        </div>
    </main>

    <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
        <x-button :href="route('kurir.departure', $task['id'])">Ambil di Dapur &amp; Departure Gate</x-button>
    </div>
@endsection
