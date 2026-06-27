<div>
    {{-- Flash message --}}
    @if (session()->has('message'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h2 class="page-title">Data Pemeriksaan</h2>
            <p class="page-subtitle">Seluruh data hasil pemeriksaan kesehatan</p>
        </div>
        <a href="{{ route('admin.health-checks.create') }}" wire:navigate class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Input Pemeriksaan
        </a>
    </div>

    {{-- Filters --}}
    <div class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Cari nama / ID..."
                       class="input pl-9" />
            </div>
            <div class="grid grid-cols-2 gap-2 sm:col-span-1 lg:col-span-1">
                <input wire:model.live="dateFrom" type="date" class="input text-xs" placeholder="Dari" />
                <input wire:model.live="dateTo" type="date" class="input text-xs" placeholder="Sampai" />
            </div>
            <select wire:model.live="department" class="input">
                <option value="">Semua Divisi</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
            <select wire:model.live="status" class="input">
                <option value="">Semua Status</option>
                <option value="normal">Normal</option>
                <option value="attention">Perlu Perhatian</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th class="th">User</th>
                        <th class="th">Tanggal</th>
                        <th class="th text-center hidden md:table-cell">GDP</th>
                        <th class="th text-center hidden md:table-cell">GDS</th>
                        <th class="th text-center hidden lg:table-cell">Asam Urat</th>
                        <th class="th text-center hidden lg:table-cell">Kolesterol</th>
                        <th class="th text-center hidden lg:table-cell">Tensi</th>
                        <th class="th text-center">Status</th>
                        <th class="th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($checks as $check)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="td">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                         style="background: linear-gradient(135deg, #0f2044, #1e3a6e);">
                                        {{ strtoupper(substr($check->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $check->user->name }}</p>
                                        @if ($check->user->department)
                                            <p class="text-xs text-gray-400">{{ $check->user->department }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="td">
                                <p class="text-sm font-medium text-gray-700">{{ $check->check_date->format('d M Y') }}</p>
                            </td>
                            <td class="td text-center hidden md:table-cell">
                                @if ($check->fasting_blood_sugar)
                                    <span class="text-sm font-medium {{ $check->fasting_blood_sugar_status === 'high' ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $check->fasting_blood_sugar }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-sm">&mdash;</span>
                                @endif
                            </td>
                            <td class="td text-center hidden md:table-cell">
                                @if ($check->random_blood_sugar)
                                    <span class="text-sm font-medium {{ $check->random_blood_sugar_status === 'high' ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $check->random_blood_sugar }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-sm">&mdash;</span>
                                @endif
                            </td>
                            <td class="td text-center hidden lg:table-cell">
                                @if ($check->uric_acid)
                                    <span class="text-sm font-medium {{ $check->uric_acid_status === 'high' ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $check->uric_acid }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-sm">&mdash;</span>
                                @endif
                            </td>
                            <td class="td text-center hidden lg:table-cell">
                                @if ($check->cholesterol)
                                    <span class="text-sm font-medium {{ $check->cholesterol_status === 'high' ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $check->cholesterol }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-sm">&mdash;</span>
                                @endif
                            </td>
                            <td class="td text-center hidden lg:table-cell">
                                @if ($check->systolic && $check->diastolic)
                                    <span class="text-sm font-medium {{ !in_array($check->blood_pressure_status, ['optimal','normal','unmeasured']) ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $check->systolic }}/{{ $check->diastolic }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-sm">&mdash;</span>
                                @endif
                            </td>
                            <td class="td text-center">
                                <x-status-badge :status="$check->overall_status" />
                            </td>
                            <td class="td text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.health-checks.edit', $check) }}" wire:navigate
                                       class="btn btn-sm btn-secondary">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <button wire:click="confirmDelete({{ $check->id }})"
                                            class="btn btn-sm bg-red-50 text-red-600 hover:bg-red-100 border border-red-200">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="td py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-sm text-gray-400">Tidak ada data pemeriksaan.</p>
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

    {{-- Delete Confirmation Modal --}}
    @if ($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40" wire:click="cancelDelete"></div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Hapus Data Pemeriksaan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-5">
                    Yakin ingin menghapus data pemeriksaan
                    <span class="font-semibold text-gray-800">{{ $confirmingDeleteName }}</span>?
                </p>

                <div class="flex gap-2 justify-end">
                    <button wire:click="cancelDelete"
                            class="btn btn-sm btn-secondary">
                        Batal
                    </button>
                    <button wire:click="delete"
                            class="btn btn-sm bg-red-600 text-white hover:bg-red-700">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
