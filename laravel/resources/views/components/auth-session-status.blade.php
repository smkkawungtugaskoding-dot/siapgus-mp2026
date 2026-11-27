@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 p-3 rounded-xl mb-4']) }}>
        ✓ {{ $status }}
    </div>
@endif
