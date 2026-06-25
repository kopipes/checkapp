<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('user.history') }}" wire:navigate
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Detail Pemeriksaan</h2>
        <p class="text-sm text-gray-500">{{ $check->check_date->format('d M Y') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <p class="text-sm text-gray-500">Status Keseluruhan</p>
            </div>
            <x-status-badge :status="$check->overall_status" class="text-sm px-3 py-1" />
        </div>

        @php
            $params = [
                ['label' => 'Gula Darah Puasa', 'value' => $check->fasting_blood_sugar, 'unit' => 'mg/dL', 'status' => $check->fasting_blood_sugar_status, 'normal' => '≤ 100'],
                ['label' => 'Gula Darah Sewaktu', 'value' => $check->random_blood_sugar, 'unit' => 'mg/dL', 'status' => $check->random_blood_sugar_status, 'normal' => '≤ 140'],
                ['label' => 'Asam Urat', 'value' => $check->uric_acid, 'unit' => 'mg/dL', 'status' => $check->uric_acid_status, 'normal' => '≤ 7 (L) / ≤ 6 (P)'],
                ['label' => 'Kolesterol', 'value' => $check->cholesterol, 'unit' => 'mg/dL', 'status' => $check->cholesterol_status, 'normal' => '≤ 200'],
            ];
        @endphp

        @foreach ($params as $param)
            <div class="flex items-center justify-between py-3 border-b border-gray-50">
                <div>
                    <p class="text-sm font-medium text-gray-700">{{ $param['label'] }}</p>
                    <p class="text-xs text-gray-400">Normal: {{ $param['normal'] }} {{ $param['unit'] }}</p>
                </div>
                <div class="text-right">
                    @if ($param['value'])
                        <p class="text-lg font-bold {{ $param['status'] === 'high' ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $param['value'] }} <span class="text-xs font-normal text-gray-400">{{ $param['unit'] }}</span>
                        </p>
                        <x-status-badge :status="$param['status']" />
                    @else
                        <x-status-badge status="unmeasured" />
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Blood pressure --}}
        <div class="flex items-center justify-between py-3 border-b border-gray-50">
            <div>
                <p class="text-sm font-medium text-gray-700">Tekanan Darah (Tensi)</p>
                <p class="text-xs text-gray-400">Normal: < 130/85 mmHg</p>
            </div>
            <div class="text-right">
                @if ($check->systolic && $check->diastolic)
                    <p class="text-lg font-bold {{ !in_array($check->blood_pressure_status, ['optimal','normal']) ? 'text-red-600' : 'text-gray-800' }}">
                        {{ $check->systolic }}/{{ $check->diastolic }} <span class="text-xs font-normal text-gray-400">mmHg</span>
                    </p>
                    <x-status-badge :status="$check->blood_pressure_status" />
                @else
                    <x-status-badge status="unmeasured" />
                @endif
            </div>
        </div>

        @if ($check->notes)
            <div class="p-3 bg-blue-50 rounded-lg">
                <p class="text-xs font-medium text-blue-700">Catatan dari Admin:</p>
                <p class="text-sm text-blue-600 mt-1">{{ $check->notes }}</p>
            </div>
        @endif

        <div class="pt-2 text-xs text-gray-400">
            Dicatat oleh: {{ $check->creator->name ?? '-' }}
        </div>
    </div>
</div>
