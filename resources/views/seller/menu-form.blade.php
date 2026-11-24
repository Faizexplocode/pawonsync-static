@extends('layouts.phone')

@section('title', $menu ? 'Edit Menu' : 'Tambah Menu')

@php
    $input = 'w-full rounded-xl border border-line bg-white px-3.5 py-[13px] text-body text-ink outline-none placeholder:text-muted/70 focus:border-leaf';
@endphp

@section('content')
    <x-app-bar title="Form Menu & Dummy Plate" :subtitle="$menu ? 'Edit menu' : 'Tambah menu'" :back="route('seller.katalog')" />

    <form action="{{ $menu ? route('seller.menu.store', $menu->id) : route('seller.menu.store') }}" method="post" enctype="multipart/form-data" class="flex flex-1 flex-col">
        @csrf

        <main class="flex-1 space-y-3.5 p-4">
            <p class="text-body font-bold text-ink">Foto menu</p>
            <div class="flex items-center gap-2.5">
                <x-photo :src="$menu?->foto" :label="$menu?->foto ? 'Utama' : 'Belum ada foto'" class="h-[92px] flex-1 rounded-[14px]" />
                <label class="grid h-[92px] flex-1 cursor-pointer place-items-center rounded-[14px] bg-linear-[132deg] from-mint-soft to-mint text-[11px] font-medium text-leaf-dark">
                    <input type="file" name="foto" accept="image/*" class="sr-only" />
                    + Tambah
                </label>
            </div>
            <p class="text-[11px] text-muted">Format gambar, maksimal 4 MB. Foto diunggah ke Supabase Storage.</p>

            <x-field label="Nama menu" name="nama" :value="old('nama', $menu?->nama)" placeholder="Contoh: Nasi Box Ayam Geprek" required />
            <div class="flex gap-2.5">
                <div class="flex-1"><x-field label="Harga / porsi" name="harga" :value="old('harga', $menu?->harga)" type="number" min="0" step="500" required /></div>
                <div class="flex-1"><x-field label="Total gramasi (g)" name="gramasi" :value="old('gramasi', $plate?->gramasi)" type="number" min="0" step="1" /></div>
            </div>
            <label class="block">
                <span class="mb-1.5 block text-caption font-medium text-muted">Deskripsi</span>
                <textarea name="deskripsi" rows="3" class="{{ $input }}">{{ old('deskripsi', $menu?->deskripsi) }}</textarea>
            </label>

            <p class="text-title font-bold text-ink">Variasi Paket</p>
            <p class="-mt-2 text-[11px] text-muted">Kosongkan nama jika tidak dipakai.</p>
            @for ($i = 0; $i < 3; $i++)
                <div class="flex gap-2">
                    <input type="text" name="paket_nama[]" value="{{ old('paket_nama.'.$i, $paket[$i]->nama ?? '') }}" placeholder="Nama paket {{ $i + 1 }}" class="{{ $input }} flex-[2]" />
                    <input type="number" name="paket_harga[]" value="{{ old('paket_harga.'.$i, $paket[$i]->harga ?? '') }}" min="0" step="500" placeholder="Harga" class="{{ $input }} flex-1" />
                </div>
            @endfor

            <p class="text-title font-bold text-ink">Opsi Tambahan</p>
            <p class="-mt-2 text-[11px] text-muted">Contoh: es teh, kemasan eco-friendly, sambal terpisah.</p>
            @for ($i = 0; $i < 3; $i++)
                <div class="flex gap-2">
                    <input type="text" name="opsi_nama[]" value="{{ old('opsi_nama.'.$i, $opsi[$i]->nama ?? '') }}" placeholder="Nama opsi {{ $i + 1 }}" class="{{ $input }} flex-[2]" />
                    <input type="number" name="opsi_harga[]" value="{{ old('opsi_harga.'.$i, $opsi[$i]->harga ?? '') }}" min="0" step="500" placeholder="Harga" class="{{ $input }} flex-1" />
                </div>
            @endfor

            <p class="text-title font-bold text-ink">Dummy Plate (standar porsi)</p>
            <label class="block">
                <span class="mb-1.5 block text-caption font-medium text-muted">Komposisi dummy plate</span>
                <textarea name="plate_deskripsi" rows="2" placeholder="Contoh: Nasi 150 g, ayam 180 g, sayur 80 g, sambal 30 g" class="{{ $input }}">{{ old('plate_deskripsi', $plate?->deskripsi) }}</textarea>
            </label>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button type="submit">Simpan Menu</x-button>
        </div>
    </form>
@endsection
