<div>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Riwayat Pemeriksaan</h2>
        <p class="text-sm text-gray-500">Seluruh riwayat pemeriksaan kesehatan Anda.</p>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                <input wire:model.live="dateFrom" type="date"
                       class="rounded-lg border-gray-200 text-sm px-3 py-2 focus:ring-2 focus:ring-teal-500" />
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                <input wire:model.live="dateTo" type="date"
                       class="rounded-lg border-gray-200 text-sm px-3 py-2 focus:ring-2 focus:ring-teal-500" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">GDP</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">GDS</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Asam Urat</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Kolesterol</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Tensi</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($checks as $check)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ $check->check_date->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-center hidden sm:table-cell">
                                <span class="text-sm {{ $check->fasting_blood_sugar_status === 'high' ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ $check->fasting_blood_sugar ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center hidden sm:table-cell">
                                <span class="text-sm {{ $check->random_blood_sugar_status === 'high' ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ $check->random_blood_sugar ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center hidden md:table-cell">
                                <span class="text-sm {{ $check->uric_acid_status === 'high' ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ $check->uric_acid ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center hidden md:table-cell">
                                <span class="text-sm {{ $check->cholesterol_status === 'high' ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ $check->cholesterol ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center hidden lg:table-cell">
                                <span class="text-sm {{ !in_array($check->blood_pressure_status, ['optimal','normal','unmeasured']) ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ ($check->systolic && $check->diastolic) ? $check->systolic.'/'.$check->diastolic : '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <x-status-badge :status="$check->overall_status" />
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('user.health-check.detail', $check) }}" wire:navigate
                                   class="text-sm font-medium" style="color: #0d9488;">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada data pemeriksaan.</td>
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
