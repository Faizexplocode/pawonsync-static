@props(['title', 'action' => null, 'href' => null])

<div class="mb-3 flex items-end justify-between gap-3">
    <h2 class="text-[15px] font-extrabold tracking-tight text-forest-deep">{{ $title }}</h2>
    @if ($action)
        <a href="{{ $href ?? '#' }}" class="inline-flex items-center gap-1 text-[11px] font-semibold text-leaf">
            {{ $action }}
            <x-icon name="chevron-right" class="size-3.5" />
        </a>
    @endif
</div>
