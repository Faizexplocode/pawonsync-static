@extends('layouts.phone')

@section('title', 'Update Milestone')

@php
    $selesaiCount = collect($milestones)->where('done', true)->count();
    $nextMilestone = collect($milestones)->firstWhere('done', false);
@endphp

@section('content')
    <x-app-bar title="Update Milestone" :subtitle="$order['title'].' · '.$order['porsi'].' porsi'" :back="route('seller.pesanan.detail', $order['id'])" />

    <form action="{{ route('seller.milestone.store', $order['id']) }}" method="post" enctype="multipart/form-data" class="flex flex-1 flex-col">
        @csrf

        <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
            <x-badge color="green" class="whitespace-normal">Update ini otomatis dikirim ke pembeli sebagai notifikasi</x-badge>

            <section class="space-y-2 surface px-4 py-3.5">
                <div class="flex items-center justify-between">
                    <p class="text-[15px] font-semibold text-ink">Progres pesanan</p>
                    <span class="text-caption font-semibold text-leaf-dark">{{ $selesaiCount }} dari {{ count($milestones) }} selesai</span>
                </div>
                <x-progress-bar :value="$progress / 4 * 100" />
                <p class="text-[11px] text-muted">Jadwal antar: {{ $order['tanggal'] }}, {{ $order['jam'] }} WIB</p>
            </section>

            <p class="text-[15px] font-semibold text-ink">Pilih status saat ini</p>
            @foreach ($milestones as $milestone)
                <x-radio-card name="status" :value="$milestone['status']->value" :title="$milestone['title']" :note="$milestone['desc'].($milestone['done'] ? ' · Selesai '.$milestone['time'] : '')" :checked="$nextMilestone && $nextMilestone['status'] === $milestone['status']" />
            @endforeach

            <p class="text-[15px] font-semibold text-ink">Foto bukti (opsional)</p>
            <input type="file" name="foto" accept="image/*" class="w-full rounded-2xl border border-line/70 bg-white px-3.5 py-[13px] text-caption text-ink" />

            <label class="block">
                <span class="mb-1.5 block text-caption font-medium text-muted">Catatan untuk pembeli</span>
                <textarea name="catatan" rows="3" placeholder="Contoh: packing selesai 08.20, menunggu kurir..." class="w-full rounded-2xl border border-line/70 bg-white px-3.5 py-[13px] text-body text-ink outline-none placeholder:text-muted/70 focus:border-leaf">{{ old('catatan') }}</textarea>
            </label>

            <a href="{{ route('seller.packaging', $order['id']) }}" class="flex items-center gap-3 surface px-4 py-3.5">
                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-mint-soft text-caption font-semibold text-forest">Pk</span>
                <span class="min-w-0 flex-1 text-body font-semibold text-ink">Lanjut ke packaging</span>
                <span class="text-[18px] font-medium text-muted">›</span>
            </a>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button type="submit">Kirim Update ke Buyer</x-button>
        </div>
    </form>
@endsection
