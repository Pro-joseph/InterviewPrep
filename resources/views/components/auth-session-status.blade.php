@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#F29B1F] bg-[#F29B1F]/10 px-4 py-3 rounded-lg']) }}>
        {{ $status }}
    </div>
@endif