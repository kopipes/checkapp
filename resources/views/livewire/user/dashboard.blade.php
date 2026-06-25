<div>
    <div class="mb-6">
        <h2 class="page-title">Halo, {{ auth()->user()->name }} 👋</h2>
        <p class="page-subtitle">Berikut ringkasan hasil pemeriksaan kesehatan Anda.</p>
    </div>

    @if ($latest)
        {{-- Latest check banner --}}
        <div class="rounded-2xl p-5 mb-5 text-white"
             style="background: linear-gradient(135deg, #0f2044 0%, #162d58 60%, #0d9488 140%);">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-white/60 text-xs font-medium uppercase tracking-wider">Pemeriksaan Terakhir</p>
                    <p class="text-white font-bold text-lg mt-0.5">{{ $latest->check_date->format('d M Y') }}</p>
                </div>
                @php
                    $overallBadge = $latest->overall_status === 'normal'
                        ? ['bg' => 'rgba(16,185,129,0.2)', 'border' => 'rgba(16,185,129,0.4)', 'color' => '#6ee7b7', 'text' => 'Semua Normal']
                        : ['bg' => 'rgba(239,68,68,0.2)', 'border' => 'rgba(239,68,68,0.4)', 'color' => '#fca5a5', 'text' => 'Perlu Perhatian'];
                @endphp
                <div class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5"
                     style="background: {{ $overallBadge['bg'] }}; border: 1px solid {{ $overallBadge['border'] }}; color: {{ $overallBadge['color'] }};">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $overallBadge['color'] }};"></span>
                    {{ $overallBadge['text'] }}
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                @php
                    $params = [
                        ['label' => 'Gula Darah Puasa', 'short' => 'GDP', 'value' => $latest->fasting_blood_sugar, 'unit' => 'mg/dL', 'status' => $latest->fasting_blood_sugar_status],
                        ['label' => 'Gula Darah Sewaktu', 'short' => 'GDS', 'value' => $latest->random_blood_sugar, 'unit' => 'mg/dL', 'status' => $latest->random_blood_sugar_status],
                        ['label' => 'Asam Urat', 'short' => 'Asam Urat', 'value' => $latest->uric_acid, 'unit' => 'mg/dL', 'status' => $latest->uric_acid_status],
                        ['label' => 'Kolesterol', 'short' => 'Kolesterol', 'value' => $latest->cholesterol, 'unit' => 'mg/dL', 'status' => $latest->cholesterol_status],
                        ['label' => 'Tekanan Darah', 'short' => 'Tensi', 'value' => ($latest->systolic && $latest->diastolic) ? $latest->systolic.'/'.$latest->diastolic : null, 'unit' => 'mmHg', 'status' => $latest->blood_pressure_status],
                    ];
                @endphp

                @foreach ($params as $p)
                    @php
                        $isHigh = in_array($p['status'], ['high','hypertension_1','hypertension_2','hypertension_3']);
                        $isWarn = $p['status'] === 'normal_high';
                    @endphp
                    <div class="rounded-xl p-3"
                         style="background: {{ $isHigh ? 'rgba(239,68,68,0.15)' : ($isWarn ? 'rgba(251,191,36,0.15)' : 'rgba(255,255,255,0.08)') }};
                                border: 1px solid {{ $isHigh ? 'rgba(239,68,68,0.3)' : ($isWarn ? 'rgba(251,191,36,0.3)' : 'rgba(255,255,255,0.1)') }};">
                        <p class="text-white/50 text-xs mb-1">{{ $p['short'] }}</p>
                        @if ($p['value'])
                            <p class="font-bold text-lg {{ $isHigh ? 'text-red-300' : ($isWarn ? 'text-amber-300' : 'text-white') }}">
                                {{ $p['value'] }}
                            </p>
                            <p class="text-white/30 text-xs">{{ $p['unit'] }}</p>
                        @else
                            <p class="text-white/30 text-sm font-medium">Tdk Diukur</p>
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($latest->notes)
                <div class="mt-4 p-3 rounded-xl" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1);">
                    <p class="text-white/50 text-xs font-medium mb-1">Catatan dari Admin:</p>
                    <p class="text-white/80 text-sm">{{ $latest->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Trend chart --}}
        @if ($chartData->count() > 1)
            <div class="card p-5 mb-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="section-title">Grafik Perkembangan</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $chartData->count() }} pemeriksaan terakhir</p>
                    </div>
                </div>
                <canvas id="userTrendChart" height="120"></canvas>
            </div>
        @endif

        {{-- History list --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="section-title">Riwayat Pemeriksaan</h3>
                <a href="{{ route('user.history') }}" wire:navigate
                   class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">Lihat semua &rarr;</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach ($history as $check)
                    <a href="{{ route('user.health-check.detail', $check) }}" wire:navigate
                       class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50/60 transition-colors group">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center text-xs font-bold
                                        {{ $check->overall_status === 'normal' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                {{ $check->check_date->format('d') }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">{{ $check->check_date->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">Oleh: {{ $check->creator->name ?? 'Admin' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-status-badge :status="$check->overall_status" />
                            <svg class="h-4 w-4 text-gray-300 group-hover:text-gray-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    @else
        {{-- Empty state --}}
        <div class="card p-12 text-center">
            <div class="h-16 w-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe);">
                <svg class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-700 mb-1">Belum ada data pemeriksaan</h3>
            <p class="text-sm text-gray-400">Data pemeriksaan Anda akan muncul di sini setelah admin melakukan input.</p>
        </div>
    @endif
</div>

@if (isset($chartData) && $chartData->count() > 1)
@push('scripts')
<script>
(function() {
    const data = @json($chartData);
    const ctx = document.getElementById('userTrendChart');
    if (!ctx) return;
    if (ctx._chart) ctx._chart.destroy();
    ctx._chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.check_date),
            datasets: [
                { label: 'GDP', data: data.map(d => d.fasting_blood_sugar), borderColor: '#f87171', backgroundColor: 'rgba(248,113,113,0.06)', tension: 0.4, fill: true, spanGaps: true, pointRadius: 4, pointHoverRadius: 6 },
                { label: 'GDS', data: data.map(d => d.random_blood_sugar), borderColor: '#fb923c', backgroundColor: 'transparent', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4, pointHoverRadius: 6 },
                { label: 'Asam Urat', data: data.map(d => d.uric_acid), borderColor: '#a855f7', backgroundColor: 'transparent', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4, pointHoverRadius: 6 },
                { label: 'Kolesterol', data: data.map(d => d.cholesterol), borderColor: '#3b82f6', backgroundColor: 'transparent', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4, pointHoverRadius: 6 },
                { label: 'Sistolik', data: data.map(d => d.systolic), borderColor: '#0d9488', backgroundColor: 'transparent', tension: 0.4, fill: false, spanGaps: true, pointRadius: 4, pointHoverRadius: 6 },
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16, color: '#6b7280', font: { size: 11 } } }
            },
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
