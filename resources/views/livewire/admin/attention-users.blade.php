<div class="card overflow-hidden">
    {{-- Header --}}
    <div class="px-5 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="section-title">Perlu Perhatian</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $checks->total() }} pemeriksaan</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Parameter filter --}}
                <select wire:model.live="parameter"
                        class="text-xs rounded-xl border-gray-200 py-1.5 px-2.5 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">Semua Parameter</option>
                    <option value="fasting_blood_sugar">Gula Darah Puasa</option>
                    <option value="random_blood_sugar">Gula Darah Sewaktu</option>
                    <option value="uric_acid">Asam Urat</option>
                    <option value="cholesterol">Kolesterol</option>
                    <option value="blood_pressure">Tensi</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Sort bar --}}
    <div class="px-5 py-2 bg-gray-50/70 border-b border-gray-100 flex items-center gap-1">
        <span class="text-xs text-gray-400 mr-2">Urutkan:</span>
        @php
            $sorts = [
                'check_date'          => 'Tanggal',
                'name'                => 'Nama',
                'fasting_blood_sugar' => 'GDP',
                'random_blood_sugar'  => 'GDS',
                'uric_acid'           => 'Asam Urat',
                'cholesterol'         => 'Kolesterol',
                'systolic'            => 'Tensi',
            ];
        @endphp
        @foreach ($sorts as $field => $label)
            <button wire:click="setSort('{{ $field }}')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-all
                           {{ $sortBy === $field ? 'bg-teal-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-teal-400' }}">
                {{ $label }}
                @if ($sortBy === $field)
                    @if ($sortDir === 'desc')
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    @else
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                        </svg>
                    @endif
                @endif
            </button>
        @endforeach
    </div>

    {{-- List --}}
    <div class="divide-y divide-gray-50">
        @forelse ($checks as $i => $check)
            <div class="px-5 py-3.5 hover:bg-slate-50/60 transition-colors">
                <div class="flex items-start gap-3">
                    {{-- Row number --}}
                    <span class="text-xs font-bold text-gray-400 w-5 flex-shrink-0 mt-1.5 text-right">
                        {{ $checks->firstItem() + $i }}
                    </span>
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                         style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                        {{ strtoupper(substr($check->user->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $check->user->name }}</p>
                            <span class="text-xs text-gray-400 flex-shrink-0">{{ $check->check_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @if ($check->fasting_blood_sugar_status === 'high')
                                <span class="badge-red">GDP {{ number_format($check->fasting_blood_sugar, 0) }}</span>
                            @endif
                            @if ($check->random_blood_sugar_status === 'high')
                                <span class="badge-red">GDS {{ number_format($check->random_blood_sugar, 0) }}</span>
                            @endif
                            @if ($check->uric_acid_status === 'high')
                                <span class="badge-orange">AU {{ number_format($check->uric_acid, 1) }}</span>
                            @endif
                            @if ($check->cholesterol_status === 'high')
                                <span class="badge-orange">Kol {{ number_format($check->cholesterol, 0) }}</span>
                            @endif
                            @if (!in_array($check->blood_pressure_status, ['optimal','normal','unmeasured']))
                                @php
                                    $bpBadge = in_array($check->blood_pressure_status, ['hypertension_2','hypertension_3']) ? 'badge-red' : 'badge-orange';
                                @endphp
                                <span class="{{ $bpBadge }}">{{ $check->systolic }}/{{ $check->diastolic }} mmHg</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-5 py-10 text-center">
                <div class="h-10 w-10 rounded-2xl bg-emerald-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm text-gray-400">Tidak ada nilai abnormal.</p>
            </div>
        @endforelse
    </div>

    @if ($checks->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $checks->links() }}
        </div>
    @endif
</div>
