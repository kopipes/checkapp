<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.health-checks.index') }}" wire:navigate class="back-link">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h2 class="page-title mt-1">Input Hasil Pemeriksaan</h2>
        <p class="page-subtitle">Field yang tidak diukur boleh dikosongkan.</p>
    </div>

    <form wire:submit="save" class="space-y-4">
        {{-- User & Date card --}}
        <div class="card p-5">
            <h3 class="section-title mb-4">Data Pasien</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <x-user-search
                        :userSearch="$userSearch"
                        :selectedUser="$selectedUser"
                        :showUserDropdown="$showUserDropdown"
                        :userResults="$this->userResults"
                    />
                </div>
                <div>
                    <label class="label">Tanggal Pemeriksaan <span class="text-red-400">*</span></label>
                    <input wire:model="check_date" type="date" class="input @error('check_date') input-error @enderror" />
                    @error('check_date') <p class="error-msg">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Parameters card --}}
        <div class="card p-5">
            <h3 class="section-title mb-1">Parameter Pemeriksaan</h3>
            <p class="text-xs text-gray-400 mb-5">Kosongkan field yang tidak diukur.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @php
                    $fields = [
                        ['model' => 'fasting_blood_sugar', 'label' => 'Gula Darah Puasa',   'unit' => 'mg/dL', 'step' => '0.01', 'min' => '0',  'max' => '500'],
                        ['model' => 'random_blood_sugar',  'label' => 'Gula Darah Sewaktu', 'unit' => 'mg/dL', 'step' => '0.01', 'min' => '0',  'max' => '500'],
                        ['model' => 'uric_acid',           'label' => 'Asam Urat',           'unit' => 'mg/dL', 'step' => '0.01', 'min' => '0',  'max' => '20'],
                        ['model' => 'cholesterol',         'label' => 'Kolesterol',          'unit' => 'mg/dL', 'step' => '0.01', 'min' => '0',  'max' => '500'],
                        ['model' => 'systolic',            'label' => 'Sistolik',            'unit' => 'mmHg',  'step' => '1',    'min' => '50', 'max' => '250'],
                        ['model' => 'diastolic',           'label' => 'Diastolik',           'unit' => 'mmHg',  'step' => '1',    'min' => '30', 'max' => '150'],
                    ];
                @endphp

                @foreach ($fields as $f)
                    <div>
                        <label class="label">
                            {{ $f['label'] }}
                            <span class="text-gray-400 font-normal text-xs ml-1">{{ $f['unit'] }}</span>
                        </label>
                        <input wire:model="{{ $f['model'] }}"
                               type="number" step="{{ $f['step'] }}" min="{{ $f['min'] }}" max="{{ $f['max'] }}"
                               placeholder="Tidak diukur"
                               class="input @error($f['model']) input-error @enderror" />
                        @error($f['model']) <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                @endforeach

                <div class="sm:col-span-2">
                    <label class="label">Catatan Admin <span class="text-gray-400 font-normal text-xs">(opsional)</span></label>
                    <textarea wire:model="notes" rows="3" placeholder="Catatan atau informasi tambahan..."
                              class="input resize-none"></textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.health-checks.index') }}" wire:navigate class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Pemeriksaan
            </button>
        </div>
    </form>
</div>
