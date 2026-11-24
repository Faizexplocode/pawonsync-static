@extends('layouts.phone')

@section('title', 'Live Location')

@php
    $berangkat = $pengiriman->waktu_berangkat !== null || in_array($pengiriman->status->value, ['berangkat', 'dalam_perjalanan', 'tiba'], true);
    $tiba = $pengiriman->status->value === 'tiba';
@endphp

@section('content')
    <x-app-bar title="Perjalanan" :subtitle="'Dalam perjalanan ke '.$task['address']" :back="route('kurir.departure', $task['id'])">
        <x-slot:right>
            <span class="text-caption font-semibold text-leaf-dark">Live</span>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-3.5 p-4">
        <section class="relative h-[300px] overflow-hidden rounded-[20px] bg-linear-[132deg] from-mint-soft from-0% to-mint to-70%">
            <div class="absolute inset-0 opacity-70 [background-image:linear-gradient(#c7e6d5_1px,transparent_1px),linear-gradient(90deg,#c7e6d5_1px,transparent_1px)] [background-size:30px_30px]"></div>
            <div class="absolute left-[14%] top-[68%] h-1.5 w-[62%] -rotate-12 rounded-full bg-leaf/40"></div>
            <div class="absolute left-[18%] top-[62%] size-3 rounded-full bg-forest ring-4 ring-forest/20"></div>
            <div class="absolute right-[14%] top-[26%] size-3 rounded-full bg-amber ring-4 ring-amber/20"></div>
            <div class="absolute left-[46%] top-[44%] grid size-11 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-forest text-mint shadow-xl ring-4 ring-white">
                <x-icon name="bike" class="size-6" />
            </div>
            <span class="absolute bottom-3 left-3 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-semibold text-forest-deep">{{ $task['seller'] }}</span>
            <span class="absolute right-3 top-3 max-w-[60%] truncate rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-semibold text-forest-deep">{{ $task['address'] }}</span>
        </section>

        <section class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1 space-y-0.5">
                    <p class="text-heading-l font-bold text-forest">ETA {{ $pengiriman->eta_menit ?? '-' }} mnt</p>
                    <p class="text-caption text-muted">
                        @if ($terakhir)
                            {{ number_format((float) $terakhir->latitude, 5) }}, {{ number_format((float) $terakhir->longitude, 5) }} · {{ $terakhir->waktu?->locale('id')->diffForHumans() }}
                        @else
                            Lokasi belum dikirim
                        @endif
                    </p>
                </div>
                <x-badge color="green" class="shrink-0">{{ $pengiriman->status->label() }}</x-badge>
            </div>
            <x-progress-bar :value="$tiba ? 100 : ($terakhir ? 60 : 20)" />
        </section>

        <form id="lokasi-form" action="{{ route('kurir.live.lokasi', $task['id']) }}" method="post" class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
            @csrf
            <input type="hidden" name="latitude" id="latitude" />
            <input type="hidden" name="longitude" id="longitude" />
            <p class="text-caption font-medium text-muted">Bagikan lokasi</p>
            <x-button type="button" id="kirim-lokasi" variant="secondary">Kirim lokasi saya sekarang</x-button>
            <p id="lokasi-status" class="text-[11px] text-muted">Izinkan akses lokasi agar buyer dan seller bisa melacak perjalanan.</p>
        </form>

        <div>
            <x-timeline-item title="Berangkat dari dapur" :time="$pengiriman->waktu_berangkat?->format('H.i')" :done="$berangkat" />
            <x-timeline-item title="Dalam perjalanan" :note="$task['address']" :time="$terakhir?->waktu?->format('H.i')" :done="$berangkat && ($terakhir || $tiba)" />
            <x-timeline-item title="Tiba di venue" :time="$task['eta']" :done="$tiba" :last="true" />
        </div>

        <x-badge color="green" class="whitespace-normal">Lokasi dibagikan ke buyer setiap kali kamu mengirim titik baru</x-badge>
    </main>

    <div class="sticky bottom-0 z-20 mt-auto flex items-center gap-2.5 border-t border-line bg-white px-4 py-3">
        <x-button variant="outline" :href="route('kurir.tugas')" class="flex-1">Daftar Tugas</x-button>
        <x-button :href="route('kurir.bukti', $task['id'])" class="flex-1">Sudah Tiba</x-button>
    </div>

    <script>
        document.getElementById('kirim-lokasi')?.addEventListener('click', function () {
            const status = document.getElementById('lokasi-status');
            if (! navigator.geolocation) {
                status.textContent = 'Perangkat tidak mendukung geolocation.';
                return;
            }

            status.textContent = 'Mengambil lokasi...';
            navigator.geolocation.getCurrentPosition(function (position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                document.getElementById('lokasi-form').submit();
            }, function () {
                status.textContent = 'Gagal mengambil lokasi. Izinkan akses lokasi pada browser.';
            }, { enableHighAccuracy: true, timeout: 10000 });
        });
    </script>
@endsection
