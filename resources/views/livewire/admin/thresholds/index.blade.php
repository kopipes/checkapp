<div>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Pengaturan Ambang Batas</h2>
        <p class="text-sm text-gray-500">Atur nilai batas normal untuk setiap parameter pemeriksaan.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Parameter</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jenis Kelamin</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Satuan</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Operator</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Nilai Abnormal</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($thresholds as $threshold)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <p class="text-sm font-medium text-gray-800">{{ $threshold->label }}</p>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">
                                {{ $threshold->gender === 'male' ? 'Laki-laki' : ($threshold->gender === 'female' ? 'Perempuan' : 'Semua') }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $threshold->unit ?? '-' }}</td>

                            @if ($editingId === $threshold->id)
                                <td class="px-5 py-3">
                                    <select wire:model="abnormal_operator"
                                            class="rounded-lg border-gray-200 text-sm focus:ring-2 focus:ring-teal-500">
                                        <option value=">">></option>
                                        <option value=">=">>=</option>
                                        <option value="<"><</option>
                                        <option value="<="><=</option>
                                    </select>
                                    @error('abnormal_operator') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </td>
                                <td class="px-5 py-3">
                                    <input wire:model="abnormal_value" type="number" step="0.01"
                                           class="w-24 rounded-lg border-gray-200 text-sm focus:ring-2 focus:ring-teal-500" />
                                    @error('abnormal_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="saveThreshold"
                                                class="text-xs px-3 py-1.5 rounded-lg text-white font-medium"
                                                style="background-color: #0d9488;">Simpan</button>
                                        <button wire:click="cancelEdit"
                                                class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 font-medium hover:bg-gray-200">Batal</button>
                                    </div>
                                </td>
                            @else
                                <td class="px-5 py-3 text-center text-sm text-gray-600">{{ $threshold->abnormal_operator }}</td>
                                <td class="px-5 py-3 text-center text-sm font-semibold text-gray-800">{{ $threshold->abnormal_value }}</td>
                                <td class="px-5 py-3 text-right">
                                    <button wire:click="startEdit({{ $threshold->id }})"
                                            class="text-sm font-medium" style="color: #0d9488;">Edit</button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Blood pressure info card --}}
    <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-5">
        <h3 class="text-sm font-semibold text-blue-800 mb-3">Kategori Tensi Darah (tidak dapat diubah)</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            @php
                $bpCategories = [
                    ['label' => 'Optimal', 'range' => 'Sistolik < 120 & Diastolik < 80', 'color' => 'text-green-700'],
                    ['label' => 'Normal', 'range' => 'Sistolik < 130 & Diastolik < 85', 'color' => 'text-green-600'],
                    ['label' => 'Normal Tinggi', 'range' => 'Sistolik 130–139 / Diastolik 85–89', 'color' => 'text-yellow-700'],
                    ['label' => 'Hipertensi 1', 'range' => 'Sistolik 140–159 / Diastolik 90–99', 'color' => 'text-orange-700'],
                    ['label' => 'Hipertensi 2', 'range' => 'Sistolik 160–179 / Diastolik 100–109', 'color' => 'text-red-600'],
                    ['label' => 'Hipertensi 3', 'range' => 'Sistolik ≥ 180 / Diastolik ≥ 110', 'color' => 'text-red-800'],
                ];
            @endphp
            @foreach ($bpCategories as $cat)
                <div class="bg-white rounded-lg p-3">
                    <p class="text-xs font-semibold {{ $cat['color'] }}">{{ $cat['label'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $cat['range'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
