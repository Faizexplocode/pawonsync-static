@extends('layouts.phone')

@section('title', 'Packaging')

@section('content')
    <x-app-bar title="Pengemasan" :subtitle="$order['title'].' · Target '.$order['porsi'].' kotak'" :back="route('seller.milestone', $order['id'])" />

    <form action="{{ route('seller.packaging.store', $order['id']) }}" method="post" class="flex flex-1 flex-col">
        @csrf

        <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
            <section class="space-y-2 rounded-[24px] bg-[linear-gradient(135deg,var(--color-forest-deep)_0%,var(--color-leaf)_71.4%)] p-[18px] text-center">
                <p class="text-caption font-medium text-mint">Counter Kotak</p>
                <p id="packaging-angka" class="text-[64px] font-bold leading-none text-white">{{ $packaging->jumlah_box }}</p>
                <p class="text-caption text-mint">dari {{ $order['porsi'] }} kotak selesai dikemas</p>
                <div class="h-2.5 w-full overflow-hidden rounded-full bg-mint-soft">
                    <div id="packaging-bar" class="h-full rounded-full bg-mint-mid" style="width: {{ $persen }}%"></div>
                </div>
                <div class="flex items-center justify-center gap-3.5 pt-1">
                    <button type="button" id="packaging-kurang" class="grid size-[60px] place-items-center rounded-full border border-mint-mid text-[30px] font-bold text-white" aria-label="Kurangi">−</button>
                    <button type="button" id="packaging-tambah" class="grid size-[60px] place-items-center rounded-full border border-mint-mid bg-mint text-[30px] font-semibold text-forest-deep" aria-label="Tambah">+</button>
                </div>
                <p class="text-[11px] font-medium text-mint">Tekan + setiap 1 kotak selesai dikemas</p>
                <input type="number" id="packaging-input" name="jumlah_box" min="0" max="{{ $order['porsi'] }}" value="{{ old('jumlah_box', $packaging->jumlah_box) }}" class="sr-only" required />
            </section>

            <section class="space-y-2 surface px-4 py-3.5">
                <div class="flex items-center justify-between">
                    <p class="text-[15px] font-semibold text-ink">Progress Pengemasan</p>
                    <x-badge color="green">{{ round($persen) }}%</x-badge>
                </div>
                <x-progress-bar :value="$persen" />
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Sisa</span>
                    <span class="font-semibold text-ink">{{ max(0, $order['porsi'] - $packaging->jumlah_box) }} kotak</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Status</span>
                    <span @class(['font-semibold', 'text-leaf' => $packaging->selesai, 'text-amber' => ! $packaging->selesai])>{{ $packaging->selesai ? 'Siap kirim' : 'Belum selesai' }}</span>
                </div>
            </section>

            <label class="flex cursor-pointer items-center gap-3 surface px-4 py-3.5">
                <input type="checkbox" name="selesai" value="1" @checked($packaging->selesai) class="peer sr-only" />
                <span class="grid size-[22px] shrink-0 place-items-center rounded-[7px] border-2 border-line bg-white text-[12px] font-bold text-transparent peer-checked:border-leaf-dark peer-checked:bg-leaf-dark peer-checked:text-white">✓</span>
                <span class="min-w-0 flex-1 space-y-0.5">
                    <span class="block text-body font-semibold text-ink">Packing selesai &amp; siap diambil kurir</span>
                    <span class="block text-[11px] text-muted">Menandai pesanan siap kirim dan memberi tahu buyer bahwa kurir akan segera ditugaskan.</span>
                </span>
            </label>

            <section class="space-y-2 surface px-4 py-3.5">
                <p class="text-[15px] font-semibold text-ink">Tips packaging</p>
                <p class="text-caption font-medium text-leaf-dark">Susun box per 25 pcs agar mudah dihitung kurir. Tempel label jumlah porsi di box paling depan.</p>
            </section>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button type="submit">Selesai Kemas - Kirim Update</x-button>
        </div>
    </form>

    <script>
        (() => {
            const input = document.getElementById('packaging-input');
            const angka = document.getElementById('packaging-angka');
            const bar = document.getElementById('packaging-bar');
            const sinkron = (nilai) => {
                const min = Number(input.min);
                const max = Number(input.max);
                input.value = Math.min(max, Math.max(min, nilai));
                angka.textContent = input.value;
                bar.style.width = (max > 0 ? (input.value / max) * 100 : 0) + '%';
            };
            document.getElementById('packaging-tambah').addEventListener('click', () => sinkron(Number(input.value) + 1));
            document.getElementById('packaging-kurang').addEventListener('click', () => sinkron(Number(input.value) - 1));
        })();
    </script>
@endsection
