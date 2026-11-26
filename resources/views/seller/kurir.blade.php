@extends('layouts.phone')

@section('title', 'Penugasan Kurir')

@section('content')
    <x-app-bar title="Penugasan & Departure Gate" :subtitle="$order['title'].' · '.($pengiriman ? $pengiriman->status->label() : 'Siap berangkat')" :back="route('seller.packaging', $order['id'])" />

    <form action="{{ route('seller.kurir.tugaskan', $order['id']) }}" method="post" class="flex flex-1 flex-col">
        @csrf

        <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
            <section class="space-y-1.5 surface px-4 py-3.5">
                <div class="flex items-center justify-between">
                    <p class="text-[15px] font-semibold text-ink">{{ $order['kode'] }}</p>
                    <x-badge color="green">Dikemas {{ $order['porsi'] }}/{{ $order['porsi'] }}</x-badge>
                </div>
                <div class="flex items-center justify-between gap-3 text-[13px]">
                    <span class="shrink-0 text-muted">Venue</span>
                    <span class="text-right font-semibold text-ink">{{ $order['alamat'] }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Target tiba</span>
                    <span class="font-semibold text-ink">{{ $order['tanggal'] }}, {{ $order['jam'] }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Jumlah box</span>
                    <span class="font-semibold text-ink">{{ $order['porsi'] }} box</span>
                </div>
            </section>

            <x-badge color="amber" class="whitespace-normal">Kurir belum boleh berangkat sebelum form ini diisi</x-badge>

            <p class="text-[15px] font-semibold text-ink">Pilih kurir</p>
            @forelse ($couriers as $courier)
                <x-radio-card name="kurir_id" :value="$courier['id']" :title="$courier['name']" :note="$courier['vehicle']" :trailing="$courier['status']" :checked="($pengiriman?->kurir_id ?? null) === $courier['id'] || (! $pengiriman && $loop->first)" />
            @empty
                <x-empty-state title="Belum ada kurir">Belum ada akun kurir terdaftar.</x-empty-state>
            @endforelse

            <p class="text-[15px] font-semibold text-ink">Form Departure Gate</p>
            <label class="block">
                <span class="mb-1.5 block text-caption font-medium text-muted">Estimasi perjalanan (menit)</span>
                <input type="number" name="eta_menit" min="5" max="600" value="{{ old('eta_menit', $pengiriman?->eta_menit ?? 45) }}" class="w-full rounded-2xl border border-line/70 bg-white px-3.5 py-[13px] text-body text-ink outline-none focus:border-leaf" />
            </label>
            <label class="block">
                <span class="mb-1.5 block text-caption font-medium text-muted">Catatan khusus untuk kurir</span>
                <textarea name="instruksi" rows="3" placeholder="Contoh: masuk lewat pintu samping, hubungi PIC di lobi" class="w-full rounded-2xl border border-line/70 bg-white px-3.5 py-[13px] text-body text-ink outline-none placeholder:text-muted/70 focus:border-leaf">{{ old('instruksi') }}</textarea>
            </label>

            <x-alert tone="info">Kurir akan menerima notifikasi dan wajib memulai perjalanan dari departure gate sebelum berangkat.</x-alert>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button type="submit" :disabled="$couriers === [] || $order['status_value'] !== 'siap_kirim'">Konfirmasi Keberangkatan</x-button>
        </div>
    </form>
@endsection
