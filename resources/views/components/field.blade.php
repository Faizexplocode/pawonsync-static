@props(['label' => null, 'value' => '', 'placeholder' => '', 'type' => 'text', 'icon' => null, 'hint' => null, 'error' => null])

<label class="block">
    @if ($label)
        <span @class(['mb-1.5 block text-caption font-medium', 'text-muted' => ! $error, 'text-danger' => $error])>{{ $label }}</span>
    @endif
    <span @class([
        'flex items-center gap-2 rounded-xl border bg-white px-3.5 py-[13px]',
        'border-line focus-within:border-2 focus-within:border-leaf focus-within:px-[13px] focus-within:py-[12px] focus-within:shadow-[0_0_6px_2px_rgba(34,163,106,0.22)]' => ! $error,
        'border-[1.5px] border-danger bg-danger-faint' => $error,
    ])>
        @if ($icon)
            <x-icon :name="$icon" class="size-4 shrink-0 text-muted" />
        @endif
        <input type="{{ $type }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="w-full bg-transparent text-body text-ink outline-none placeholder:text-muted/70" {{ $attributes }} />
    </span>
    @if ($error)
        <span class="mt-1.5 block text-[11px] font-medium text-danger">{{ $error }}</span>
    @elseif ($hint)
        <span class="mt-1.5 block text-[11px] font-medium text-muted">{{ $hint }}</span>
    @endif
</label>
