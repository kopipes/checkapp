@props(['status'])

@php
    [$cls, $dot, $label] = match($status) {
        'normal'         => ['badge-green',  'bg-emerald-400', 'Normal'],
        'attention'      => ['badge-red',    'bg-red-400',     'Perlu Perhatian'],
        'high'           => ['badge-red',    'bg-red-400',     'Tinggi'],
        'unmeasured'     => ['badge-gray',   'bg-gray-300',    'Tidak Diukur'],
        'optimal'        => ['badge-green',  'bg-emerald-400', 'Optimal'],
        'normal_high'    => ['badge-yellow', 'bg-amber-400',   'Normal Tinggi'],
        'hypertension_1' => ['badge-orange', 'bg-orange-400',  'Hipertensi 1'],
        'hypertension_2' => ['badge-red',    'bg-red-400',     'Hipertensi 2'],
        'hypertension_3' => ['badge-red',    'bg-red-500',     'Hipertensi 3'],
        default          => ['badge-gray',   'bg-gray-300',    '-'],
    };
@endphp

<span {{ $attributes->merge(['class' => $cls]) }}>
    <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $dot }}"></span>
    {{ $label }}
</span>
