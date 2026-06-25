<div>
    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="stat-label">Total User</span>
                <div class="h-9 w-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                    <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Karyawan terdaftar</p>
            </div>
        </div>

        <div class="card p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="stat-label">Total Pemeriksaan</span>
                <div class="h-9 w-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ $totalChecks }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Semua waktu</p>
            </div>
        </div>

        <div class="card p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="stat-label">Bulan Ini</span>
                <div class="h-9 w-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #f0fdfa, #ccfbf1);">
                    <svg class="h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold" style="color: #0d9488;">{{ $checksThisMonth }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ now()->locale('id')->translatedFormat('F Y') }}</p>
            </div>
        </div>

        <div class="card p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="stat-label">Perlu Perhatian</span>
                <div class="h-9 w-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #fff1f2, #ffe4e6);">
                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-red-500">{{ $attentionCount }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Nilai abnormal</p>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        <div class="card p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="section-title">Pemeriksaan per Hari</h3>
                    <p class="text-xs text-gray-400 mt-0.5">30 hari terakhir</p>
                </div>
            </div>
            <canvas id="dailyChart" height="180"></canvas>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="section-title">Nilai Tinggi per Parameter</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Total semua waktu</p>
                </div>
            </div>
            <canvas id="abnormalChart" height="180"></canvas>
        </div>
    </div>

    {{-- Bottom section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Recent checks --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="section-title">Pemeriksaan Terbaru</h3>
                <a href="{{ route('admin.health-checks.index') }}" wire:navigate
                   class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">Lihat semua &rarr;</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($recentChecks as $check)
                    <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50/60 transition-colors">
                        <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                             style="background: linear-gradient(135deg, #0f2044, #162d58);">
                            {{ strtoupper(substr($check->user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $check->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $check->check_date->format('d M Y') }}</p>
                        </div>
                        <x-status-badge :status="$check->overall_status" />
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <p class="text-sm text-gray-400">Belum ada data pemeriksaan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Attention users — isolated Livewire component --}}
        @livewire('admin.attention-users')
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', initCharts)
document.addEventListener('livewire:navigated', initCharts)

function initCharts() {
    const dailyCtx = document.getElementById('dailyChart')
    if (dailyCtx) {
        if (dailyCtx._chart) dailyCtx._chart.destroy()
        const data = @json($filledDailyData);
        const labels = Object.keys(data).map(d => {
            const dt = new Date(d);
            return dt.getDate() + '/' + (dt.getMonth()+1);
        });
        dailyCtx._chart = new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Pemeriksaan',
                    data: Object.values(data),
                    backgroundColor: 'rgba(13,148,136,0.15)',
                    borderColor: '#0d9488',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#9ca3af' }, grid: { color: '#f3f4f6' } },
                    x: { ticks: { color: '#9ca3af', maxTicksLimit: 10, maxRotation: 0 }, grid: { display: false } }
                }
            }
        })
    }

    const abnCtx = document.getElementById('abnormalChart')
    if (abnCtx) {
        if (abnCtx._chart) abnCtx._chart.destroy()
        const abn = @json($abnormalStats);
        const labels = {
            fasting_blood_sugar: 'GDP', random_blood_sugar: 'GDS',
            uric_acid: 'Asam Urat', cholesterol: 'Kolesterol', blood_pressure: 'Tensi'
        };
        const colors = [
            { bg: 'rgba(239,68,68,0.12)', border: '#ef4444' },
            { bg: 'rgba(249,115,22,0.12)', border: '#f97316' },
            { bg: 'rgba(168,85,247,0.12)', border: '#a855f7' },
            { bg: 'rgba(59,130,246,0.12)', border: '#3b82f6' },
            { bg: 'rgba(20,184,166,0.12)', border: '#14b8a6' },
        ];
        abnCtx._chart = new Chart(abnCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(abn).map(k => labels[k] || k),
                datasets: [{
                    label: 'Nilai Tinggi',
                    data: Object.values(abn),
                    backgroundColor: colors.map(c => c.bg),
                    borderColor: colors.map(c => c.border),
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#9ca3af' }, grid: { color: '#f3f4f6' } },
                    x: { ticks: { color: '#9ca3af' }, grid: { display: false } }
                }
            }
        })
    }
}
</script>
@endpush
