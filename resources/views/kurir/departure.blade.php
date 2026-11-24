@extends('layouts.phone')

@section('title', 'Departure Gate')

@section('content')
    <x-app-bar title="Departure Gate" subtitle="Mulai pengiriman" :back="route('kurir.tugas.detail', $task['id'])" />

    <main class="flex-1 space-y-3.5 p-4">
        <section class="space-y-1 rounded-[20px] bg-[linear-gradient(159deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)] px-[18px] py-4">
            <p class="text-caption font-medium text-mint">{{ $task['title'] }} · {{ $task['load'] }}</p>
            <p class="text-heading-xl font-bold text-white">{{ $task['status'] }}</p>
            <p class="text-caption text-mint">Tiba target {{ $task['eta'] }} · ETA otomatis dihitung dari lokasi</p>
        </section>

        <section class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Cek sebelum berangkat</p>
            @foreach ($checklist as [$label, $checked])
                <div class="flex items-center gap-3 py-1">
                    <span @class([
                        'grid size-[22px] shrink-0 place-items-center rounded-[7px] border-2 text-[12px] font-bold',
                        'border-leaf-dark bg-leaf-dark text-white' => $checked,
                        'border-line bg-white text-transparent' => ! $checked,
                    ])>✓</span>
                    <span @class(['text-body', 'font-medium text-ink' => $checked, 'text-ink' => ! $checked])>{{ $label }}</span>
                </div>
            @endforeach
        </section>

        <section class="space-y-2 rounded-[18px] border border-line bg-white px-4 py-3.5">
            <p class="text-body font-bold text-ink">Rute perjalanan</p>
            <div class="flex items-start gap-3 text-caption">
                <span class="mt-1 size-2 shrink-0 rounded-full bg-forest"></span>
                <div><p class="font-semibold text-ink">{{ $task['seller'] }}</p><p class="text-muted">Titik ambil pesanan</p></div>
            </div>
            <div class="ml-[3px] h-5 border-l-2 border-dashed border-line"></div>
            <div class="flex items-start gap-3 text-caption">
                <span class="mt-1 size-2 shrink-0 rounded-full bg-amber"></span>
                <div><p class="font-semibold text-ink">{{ $task['address'] }}</p><p class="text-muted">Target {{ $task['eta'] }}</p></div>
            </div>
        </section>

        <p class="text-body font-bold text-ink">Yang terjadi saat berangkat</p>
        <section class="space-y-1.5 rounded-[18px] border border-line bg-white px-4 py-3.5">
            @foreach (['Status pesanan berubah menjadi dikirim', 'Buyer menerima notifikasi keberangkatan', 'Seller melihat posisi kurir di dashboard'] as $langkah)
                <div class="flex items-center gap-3 py-1">
                    <span class="grid size-[22px] shrink-0 place-items-center rounded-[7px] border-2 border-leaf-dark bg-leaf-dark text-[12px] font-bold text-white">✓</span>
                    <span class="text-body font-medium text-ink">{{ $langkah }}</span>
                </div>
            @endforeach
        </section>

        <x-alert tone="warning">Jangan berangkat sebelum seller menandai pesanan selesai di-packing.</x-alert>

        @if ($pengiriman->status->value === 'ditugaskan')
            <form action="{{ route('kurir.departure.mulai', $task['id']) }}" method="post">
                @csrf
                <button type="submit" class="flex w-full items-center rounded-[40px] bg-[linear-gradient(166deg,var(--color-forest)_0%,var(--color-leaf)_71.4%)] p-1.5">
                    <span class="grid size-14 shrink-0 place-items-center rounded-full bg-white text-[16px] font-bold text-forest">›››</span>
                    <span class="flex-1 text-center text-body font-bold text-white">Aktifkan Departure Gate</span>
                </button>
            </form>
        @endif
    </main>

    @unless ($pengiriman->status->value === 'ditugaskan')
        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button :href="route('kurir.live', $task['id'])">Lanjut ke Live Location</x-button>
        </div>
    @endunless
@endsection
