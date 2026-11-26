@extends('layouts.phone')

@section('title', 'Notifikasi')

@section('content')
    <div class="flex-1 bg-cream">
        <header class="flex items-end justify-between gap-3 px-5 pb-2 pt-5">
            <div class="min-w-0">
                <p class="text-[12px] text-muted">{{ $belumDibaca }} belum dibaca</p>
                <h1 class="text-[20px] font-semibold text-ink">Notifikasi</h1>
            </div>
            @if ($belumDibaca > 0)
                <form action="{{ route('buyer.notifikasi.dibaca') }}" method="post" class="shrink-0">
                    @csrf
                    <button type="submit" class="rounded-full bg-white px-3.5 py-2 text-[12px] font-medium text-forest shadow-soft">Tandai semua dibaca</button>
                </form>
            @endif
        </header>

        <main class="stagger space-y-3 px-5 pb-6 pt-3">
            @forelse ($notifications as $notification)
                <a href="{{ $notification['pesanan_id'] ? route('buyer.pesanan.detail', $notification['pesanan_id']) : route('buyer.beranda') }}" @class([
                    'flex items-start gap-3 rounded-[24px] p-4 transition active:scale-[0.99]',
                    'bg-white shadow-soft' => $notification['unread'],
                    'bg-white/55' => ! $notification['unread'],
                ])>
                    <span @class([
                        'relative grid size-11 shrink-0 place-items-center rounded-full',
                        'bg-amber-soft text-amber' => $notification['tone'] === 'amber',
                        'bg-mint-soft text-forest' => $notification['tone'] !== 'amber',
                    ])>
                        <x-icon :name="$notification['icon']" class="size-5" />
                        @if ($notification['unread'])
                            <span class="absolute right-0 top-0 size-3 rounded-full bg-amber ring-2 ring-white"></span>
                            <span class="sr-only">Belum dibaca</span>
                        @endif
                    </span>
                    <span class="min-w-0 flex-1 space-y-1">
                        <span class="flex items-start justify-between gap-2">
                            <span @class(['text-body leading-snug text-ink', 'font-semibold' => $notification['unread'], 'font-medium' => ! $notification['unread']])>{{ $notification['title'] }}</span>
                            <span class="shrink-0 text-[11px] text-muted">{{ $notification['time'] }}</span>
                        </span>
                        <span class="block text-[12px] leading-relaxed text-muted">{{ $notification['body'] }}</span>
                    </span>
                </a>
            @empty
                <x-empty-state title="Belum ada notifikasi">Kabar pesanan dan pengiriman akan muncul di sini.</x-empty-state>
            @endforelse
        </main>
    </div>

    <x-bottom-nav role="buyer" active="buyer.notifikasi" />
@endsection
