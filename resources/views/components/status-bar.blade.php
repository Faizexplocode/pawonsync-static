@props(['tone' => 'dark'])

<div @class([
    'flex items-center justify-between px-6 py-3 text-[13px] font-semibold',
    'text-ink' => $tone !== 'light',
    'text-white' => $tone === 'light',
])>
    <span>9:41</span>
    <span @class(['text-[11px] font-medium', 'text-muted' => $tone !== 'light', 'text-mint' => $tone === 'light'])>5G&nbsp; 100%</span>
</div>
