@extends('layouts.phone')

@section('title', 'Form Menu')

@section('content')
    <x-app-bar title="Tambah Menu" subtitle="Lengkapi detail menu katering" :back="route('seller.katalog')" />

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Foto menu</p>
            <div class="mt-2 grid grid-cols-3 gap-2">
                <x-photo label="Foto utama" class="col-span-2 h-28" />
                <x-photo label="+ Tambah" class="h-28 border border-dashed border-line bg-white text-muted" />
            </div>
            <p class="mt-1.5 text-[9px] text-muted">Rasio 4:3, minimal 800×600 px. Maksimal 5 foto.</p>
        </section>

        <section class="space-y-3 rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Informasi menu</p>
            <x-field label="Nama menu" value="Nasi Box Ayam Geprek" placeholder="Contoh: Nasi Box Ayam Geprek" />
            <div>
                <span class="mb-1.5 block text-[12px] font-semibold text-forest-deep">Kategori</span>
                <div class="flex flex-wrap gap-2">
                    <x-chip :active="true" href="#">Nasi Box</x-chip>
                    <x-chip href="#">Prasmanan</x-chip>
                    <x-chip href="#">Snack</x-chip>
                    <x-chip href="#">Tumpeng</x-chip>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <x-field label="Harga / porsi" value="38000" type="number" />
                <x-field label="Minimal porsi" value="30" type="number" />
            </div>
            <div class="grid grid-cols-2 gap-2">
                <x-field label="Kapasitas harian" value="200" type="number" />
                <x-field label="Estimasi masak" value="H-1" />
            </div>
            <label class="block">
                <span class="mb-1.5 block text-[12px] font-semibold text-forest-deep">Deskripsi</span>
                <textarea rows="3" class="w-full rounded-2xl border border-line bg-white px-4 py-3 text-[12px] leading-relaxed text-ink outline-none focus:border-leaf focus:ring-2 focus:ring-mint">Nasi putih pulen, ayam geprek sambal bawang, lalapan, kerupuk. Cocok untuk wisuda, seminar, dan arisan.</textarea>
            </label>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Varian paket</p>
                <span class="text-[10px] font-bold text-leaf">+ Tambah</span>
            </div>
            <div class="mt-2 space-y-2">
                @foreach ([['Nasi Box Standar', 'Nasi + ayam + lalapan'], ['Nasi Box + Es Teh', 'Tambah es teh manis'], ['Nasi Box Premium', 'Tambah buah & dessert']] as [$name, $note])
                    <div class="flex items-center gap-3 rounded-xl bg-mist p-3">
                        <span class="grid size-7 place-items-center rounded-lg bg-white text-[10px] font-bold text-forest ring-1 ring-line">{{ $loop->iteration }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold text-ink">{{ $name }}</p>
                            <p class="text-[9px] text-muted">{{ $note }}</p>
                        </div>
                        <x-icon name="edit" class="size-4 text-muted" />
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Opsi tambahan</p>
            <div class="mt-2 divide-y divide-line">
                @foreach ([['Es teh manis', 'Rp 4.000 / gelas'], ['Kemasan eco-friendly', 'Rp 1.500 / box'], ['Sambal terpisah', 'Gratis']] as [$name, $price])
                    <div class="flex items-center justify-between py-2.5">
                        <div>
                            <p class="text-[11px] font-semibold text-ink">{{ $name }}</p>
                            <p class="text-[9px] text-muted">{{ $price }}</p>
                        </div>
                        <span class="relative h-5 w-9 rounded-full bg-leaf">
                            <span class="absolute right-0.5 top-0.5 size-4 rounded-full bg-white"></span>
                        </span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Standar porsi (dummy plate)</p>
                <a href="{{ route('seller.kalkulasi') }}" class="text-[10px] font-bold text-leaf">Hitung</a>
            </div>
            <x-photo label="Foto piring acuan" class="mt-2 h-24 w-full" />
            <div class="mt-2 grid grid-cols-2 gap-2 text-[10px]">
                @foreach ([['Nasi', '150 g'], ['Ayam geprek', '180 g'], ['Sayur', '80 g'], ['Sambal', '30 g']] as [$name, $value])
                    <div class="flex justify-between rounded-lg bg-mist px-2.5 py-2">
                        <span class="text-muted">{{ $name }}</span>
                        <span class="font-bold text-forest-deep">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('seller.katalog') }}" class="flex items-center justify-center rounded-2xl border border-line bg-white px-4 py-3.5 text-[13px] font-bold text-muted">Simpan draft</a>
            <a href="{{ route('seller.katalog') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
                Publikasikan
                <x-icon name="check" class="size-4" />
            </a>
        </div>
    </main>
@endsection
