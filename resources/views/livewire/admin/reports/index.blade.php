<div>
    <div class="page-header">
        <div>
            <h2 class="page-title">Report Pemeriksaan</h2>
            <p class="page-subtitle">Lihat dan export data berdasarkan berbagai filter.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="export" class="btn-secondary btn-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Excel
            </button>
            <button wire:click="exportCsv" class="btn-secondary btn-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                CSV
            </button>
        </div>
    </div>

    {{-- Report type tabs --}}
    <div class="flex flex-wrap gap-2 mb-4">
        @php
            $types = [
                'date'      => 'Per Tanggal',
                'user'      => 'Per User',
                'parameter' => 'Per Parameter',
                'division'  => 'Per Divisi',
                'abnormal'  => 'Nilai Abnormal',
                'trend'     => 'Trend',
            ];
        @endphp
        @foreach ($types as $key => $label)
            <button wire:click="setReportType('{{ $key }}')"
                    class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150
                           {{ $reportType === $key ? 'text-white shadow-sm' : 'card text-gray-600 hover:text-gray-800' }}"
                    style="{{ $reportType === $key ? 'background: linear-gradient(135deg, #0d9488, #0f766e);' : '' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="label">Dari Tanggal</label>
                <input wire:model.live="dateFrom" type="date" class="input" />
            </div>
            <div>
                <label class="label">Sampai Tanggal</label>
                <input wire:model.live="dateTo" type="date" class="input" />
            </div>

            @if (in_array($reportType, ['date', 'user', 'trend']))
            <div>
                <label class="label">User</label>
                <select wire:model.live="userId" class="input">
                    <option value="">Semua User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            @if (in_array($reportType, ['date', 'division']))
            <div>
                <label class="label">Divisi</label>
                <select wire:model.live="department" class="input">
                    <option value="">Semua Divisi</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            @if ($reportType === 'parameter')
            <div>
                <label class="label">Parameter</label>
                <select wire:model.live="parameter" class="input">
                    <option value="">Semua Parameter</option>
                    <option value="fasting_blood_sugar">Gula Darah Puasa</option>
                    <option value="random_blood_sugar">Gula Darah Sewaktu</option>
                    <option value="uric_acid">Asam Urat</option>
                    <option value="cholesterol">Kolesterol</option>
                </select>
            </div>
            @endif

            @if (!in_array($reportType, ['abnormal']))
            <div>
                <label class="label">Status</label>
                <select wire:model.live="status" class="input">
                    <option value="">Semua Status</option>
                    <option value="normal">Normal</option>
                    <option value="attention">Perlu Perhatian</option>
                </select>
            </div>
            @endif
        </div>
    </div>

    {{-- Trend hint --}}
    @if ($reportType === 'trend' && !$userId)
        <div class="card p-4 mb-4 flex items-center gap-3">
            <svg class="h-5 w-5 text-teal-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-gray-600">Pilih <strong>User</strong> di atas untuk melihat grafik trend perkembangan nilai.</p>
        </div>
    @endif

    {{-- Trend chart --}}
    @if ($reportType === 'trend' && $userId && $trendData->count() > 0)
        <div class="card p-5 mb-5">
            <h3 class="section-title mb-4">Grafik Trend</h3>
            <canvas id="trendChart" height="120"></canvas>
        </div>
    @endif

    {{-- Results table --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-700">
                {{ $checks->total() }} data ditemukan
            </span>
            <span class="text-xs text-gray-400">{{ ucfirst($types[$reportType] ?? '') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th class="th">User</th>
                        <th class="th">Tanggal</th>
                        <th class="th text-center">GDP</th>
                        <th class="th text-center">GDS</th>
                        <th class="th text-center hidden lg:table-cell">Asam Urat</th>
                        <th class="th text-center hidden lg:table-cell">Kolesterol</th>
                        <th class="th text-center hidden lg:table-cell">Tensi</th>
                        <th class="th text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($checks as $check)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="td">
                                <p class="text-sm font-semibold text-gray-800">{{ $check->user->name }}</p>
                                @if($check->user->department)
                                    <p class="text-xs text-gray-400">{{ $check->user->department }}</p>
                                @endif
                            </td>
                            <td class="td text-sm text-gray-600">{{ $check->check_date->format('d M Y') }}</td>
                            <td class="td text-center">
                                <span class="text-sm font-medium {{ $check->fasting_blood_sugar_status === 'high' ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ $check->fasting_blood_sugar ?? '—' }}
                                </span>
                            </td>
                            <td class="td text-center">
                                <span class="text-sm font-medium {{ $check->random_blood_sugar_status === 'high' ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ $check->random_blood_sugar ?? '—' }}
                                </span>
                            </td>
                            <td class="td text-center hidden lg:table-cell">
                                <span class="text-sm font-medium {{ $check->uric_acid_status === 'high' ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ $check->uric_acid ?? '—' }}
                                </span>
                            </td>
                            <td class="td text-center hidden lg:table-cell">
                                <span class="text-sm font-medium {{ $check->cholesterol_status === 'high' ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ $check->cholesterol ?? '—' }}
                                </span>
                            </td>
                            <td class="td text-center hidden lg:table-cell">
                                <span class="text-sm font-medium {{ !in_array($check->blood_pressure_status, ['optimal','normal','unmeasured']) ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ ($check->systolic && $check->diastolic) ? $check->systolic.'/'.$check->diastolic : '—' }}
                                </span>
                            </td>
                            <td class="td text-center">
                                <x-status-badge :status="$check->overall_status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="td py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-400">Tidak ada data untuk filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($checks->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $checks->links() }}
            </div>
        @endif
    </div>
</div>

@if ($reportType === 'trend' && $userId && $trendData->count() > 0)
@push('scripts')
<script>
(function() {
    const data = @json($trendData);
    const ctx = document.getElementById('trendChart');
    if (!ctx) return;
    if (ctx._chart) ctx._chart.destroy();
    ctx._chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.check_date),
            datasets: [
                { label: 'GDP', data: data.map(d => d.fasting_blood_sugar), borderColor: '#f87171', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4 },
                { label: 'GDS', data: data.map(d => d.random_blood_sugar), borderColor: '#fb923c', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4 },
                { label: 'Asam Urat', data: data.map(d => d.uric_acid), borderColor: '#a855f7', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4 },
                { label: 'Kolesterol', data: data.map(d => d.cholesterol), borderColor: '#3b82f6', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4 },
                { label: 'Sistolik', data: data.map(d => d.systolic), borderColor: '#0d9488', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4 },
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16, color: '#6b7280', font: { size: 11 } } } },
            scales: {
                y: { beginAtZero: false, ticks: { color: '#9ca3af' }, grid: { color: '#f3f4f6' } },
                x: { ticks: { color: '#9ca3af' }, grid: { display: false } }
            }
        }
    });
})();
</script>
@endpush
@endif
