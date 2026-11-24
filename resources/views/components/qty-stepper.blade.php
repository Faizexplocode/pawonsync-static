@props(['name' => 'qty', 'value' => 1, 'min' => 1, 'max' => null, 'submitOnChange' => false])

<div {{ $attributes->merge(['class' => 'flex items-center gap-3.5 rounded-full border border-line bg-white px-3 py-1.5']) }}>
    <button type="button" class="text-[16px] font-bold text-forest" aria-label="Kurangi" onclick="this.nextElementSibling.stepDown(); @if ($submitOnChange) this.form.requestSubmit(); @endif">−</button>
    <input type="number" name="{{ $name }}" min="{{ $min }}" @if ($max) max="{{ $max }}" @endif value="{{ $value }}" required class="w-12 bg-transparent text-center text-body font-semibold text-ink outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none" @if ($submitOnChange) onchange="this.form.requestSubmit()" @endif />
    <button type="button" class="text-[16px] font-bold text-forest" aria-label="Tambah" onclick="this.previousElementSibling.stepUp(); @if ($submitOnChange) this.form.requestSubmit(); @endif">+</button>
</div>
