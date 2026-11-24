@extends('layouts.phone')

@section('title', 'Detail Tugas')

@section('content')
    <x-app-bar title="Detail Tugas" :subtitle="$task['kode']" :back="route('kurir.tugas')">
        <x-slot:right>
            <span class="text-caption font-semibold text-leaf-dark">{{ $task['status'] }}</span>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-3.5 p-4">
        <section class="relative h-[170px] overflow-hidden rounded-[20px] bg-linear-[148deg] from-mint-soft from-0% to-mint to-70%">
            <div class="absolute inset-0 opacity-60 [background-image:linear-gradient(#c7e6d5_1px,transparent_1px),linear-gradient(90deg,#c7e6d5_1px,transparent_1px)] [background-size:26px_26px]"></div>
            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-forest-deep">{{ $task['seller'] }}</span>
            <span class="absolute bottom-3 right-3 max-w-[70%] truncate rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-forest-deep">{{ $task['address'] }}</span>
            <span class="absolute left-1/2 top-1/2 grid size-9 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-white text-leaf-dark shadow-lg">
                <x-icon name="bike" class="size-5" />
            </span>
        </section>

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
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

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Rincian muatan</p>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Jumlah box</span>
                <span class="font-semibold text-ink">{{ $task['load'] }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Jam target tiba</span>
                <span class="font-bold text-forest">{{ $task['eta'] }}</span>
            </div>
            <div class="flex items-center justify-between text-[13px]">
                <span class="text-muted">Kode pesanan</span>
                <span class="font-semibold text-ink">{{ $task['kode'] }}</span>
            </div>
        </section>

        <section class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-caption font-medium text-muted">Catatan khusus</p>
            <p class="text-[13px] text-ink">Hubungi seller sebelum berangkat dan pastikan semua box sudah dicek sesuai jumlah pesanan.</p>
        </section>

        <p class="text-body font-bold text-ink">Progres tugas</p>
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
