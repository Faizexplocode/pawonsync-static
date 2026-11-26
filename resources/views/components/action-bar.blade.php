{{-- Bar aksi yang menempel di bawah layar --}}
<div {{ $attributes->merge(['class' => 'sticky bottom-0 z-20 mt-auto flex items-center gap-2.5 bg-cream/95 px-5 pb-[max(1rem,env(safe-area-inset-bottom))] pt-3 backdrop-blur']) }}>
    {{ $slot }}
</div>
