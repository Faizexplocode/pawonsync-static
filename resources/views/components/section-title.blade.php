@props(['title', 'action' => null, 'href' => null])

<div class="mb-3 flex items-center justify-between gap-3">
    <h2 class="text-[15px] font-semibold text-ink">{{ $title }}</h2>
    @if ($action)
        <a href="{{ $href ?? '#' }}" class="text-[12px] font-medium text-leaf-dark">{{ $action }}</a>
    @endif
</div>
