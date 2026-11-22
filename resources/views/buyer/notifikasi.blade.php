@extends('layouts.phone')

@section('title', 'Notifikasi')

@php
    $notifications = config('demo.notifications');
    $tones = [
        'green' => 'bg-mint text-forest',
        'mint' => 'bg-mint-soft text-leaf-dark',
        'amber' => 'bg-amber-soft text-amber',
    ];
@endphp

@section('content')
    <x-app-bar title="Notifikasi" subtitle="{{ collect($notifications)->where('unread', true)->count() }} belum dibaca" :back="route('buyer.beranda')">
        <x-slot:right>
            <a href="#" class="text-[10px] font-bold text-leaf">Tandai semua</a>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 px-4 py-4">
        <div class="grid grid-cols-3 gap-1 rounded-2xl bg-white p-1 ring-1 ring-line">
            <span class="rounded-xl bg-forest py-2 text-center text-[11px] font-bold text-white">Semua</span>
            <a href="#" class="rounded-xl py-2 text-center text-[11px] font-bold text-muted">Pesanan</a>
            <a href="#" class="rounded-xl py-2 text-center text-[11px] font-bold text-muted">Promo</a>
        </div>

        <div class="mt-4 space-y-2">
            @foreach ($notifications as $notification)
                <div @class(['flex gap-3 rounded-2xl border p-3', 'border-line bg-white' => ! $notification['unread'], 'border-mint-mid bg-mint-soft/50' => $notification['unread']])>
                    <span class="grid size-9 shrink-0 place-items-center rounded-full {{ $tones[$notification['tone']] ?? $tones['mint'] }}">
                        <x-icon :name="$notification['icon']" class="size-4" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-[11px] font-bold text-forest-deep">{{ $notification['title'] }}</p>
                            <span class="shrink-0 text-[9px] text-muted">{{ $notification['time'] }}</span>
                        </div>
                        <p class="mt-0.5 text-[10px] leading-relaxed text-muted">{{ $notification['body'] }}</p>
                    </div>
                    @if ($notification['unread'])
                        <span class="mt-1 size-2 shrink-0 rounded-full bg-leaf"></span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4 rounded-2xl border border-dashed border-line p-4 text-center text-[10px] text-muted">
            Itu semua notifikasi minggu ini.
        </div>
    </main>

    <x-bottom-nav role="buyer" active="buyer.notifikasi" />
@endsection
