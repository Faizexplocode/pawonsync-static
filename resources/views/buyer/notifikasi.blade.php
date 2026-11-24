@extends('layouts.phone')

@section('title', 'Notifikasi')

@section('content')
    <x-app-bar title="Notifikasi" :subtitle="$belumDibaca.' belum dibaca'">
        <x-slot:right>
            @if ($belumDibaca > 0)
                <form action="{{ route('buyer.notifikasi.dibaca') }}" method="post">
                    @csrf
                    <button type="submit" class="text-caption font-semibold text-leaf-dark">Tandai semua dibaca</button>
                </form>
            @endif
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-3.5 p-4">
        @forelse ($notifications as $notification)
            <a href="{{ $notification['pesanan_id'] ? route('buyer.pesanan.detail', $notification['pesanan_id']) : route('buyer.beranda') }}" @class([
                'block space-y-1.5 rounded-[18px] border border-line px-4 py-3.5',
                'bg-mint-faint' => $notification['unread'],
                'bg-white' => ! $notification['unread'],
            ])>
                <div class="flex items-center justify-between gap-2">
                    <x-badge :color="$notification['unread'] ? 'green' : 'mint'">{{ $notification['unread'] ? 'Baru' : 'Dibaca' }}</x-badge>
                    <span class="text-[11px] font-medium text-muted">{{ $notification['time'] }}</span>
                </div>
                <p class="text-body font-semibold text-ink">{{ $notification['title'] }}</p>
                <p class="text-caption text-muted">{{ $notification['body'] }}</p>
            </a>
        @empty
            <x-empty-state title="Belum ada notifikasi">Kabar pesanan dan pengiriman akan muncul di sini.</x-empty-state>
        @endforelse
    </main>

    <x-bottom-nav role="buyer" active="buyer.notifikasi" />
@endsection
