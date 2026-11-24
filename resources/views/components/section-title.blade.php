@props(['title', 'action' => null, 'href' => null])

<div class="mb-3 flex items-center justify-between gap-3">
    <h2 class="text-title font-bold text-ink">{{ $title }}</h2>
    @if ($action)
        <a href="{{ $href ?? '#' }}" class="text-caption font-semibold text-leaf-dark">{{ $action }}</a>
    @endif
</div>
