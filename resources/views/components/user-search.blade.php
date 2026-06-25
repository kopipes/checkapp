@props([
    'userSearch'       => '',
    'selectedUser'     => null,
    'showUserDropdown' => false,
    'userResults'      => [],
    'error'            => null,
])

<div class="relative">
    <label class="label">Pilih User <span class="text-red-400">*</span></label>

    @if ($selectedUser)
        {{-- Selected state --}}
        <div class="flex items-center gap-3 p-3 rounded-xl border-2 border-teal-500 bg-teal-50">
            <div class="h-9 w-9 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                 style="background: linear-gradient(135deg, #0f2044, #1e3a6e);">
                {{ strtoupper(substr($selectedUser['name'], 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900">{{ $selectedUser['name'] }}</p>
                @if ($selectedUser['department'])
                    <p class="text-xs text-teal-600">{{ $selectedUser['department'] }}</p>
                @endif
            </div>
            <button type="button" wire:click="clearUser"
                    class="text-gray-400 hover:text-red-500 transition-colors p-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @else
        {{-- Search input --}}
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
            </svg>
            <input
                wire:model.live.debounce.200ms="userSearch"
                type="text"
                placeholder="Ketik nama, ID karyawan, atau divisi..."
                autocomplete="off"
                class="input pl-9 @error('user_id') input-error @enderror"
            />
        </div>

        {{-- Dropdown results - rendered by Livewire state directly --}}
        @if ($showUserDropdown)
            <div class="absolute z-50 mt-1 w-full bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                @if (count($userResults) > 0)
                    @foreach ($userResults as $u)
                        <button type="button"
                                wire:click="selectUser({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->department ?? '') }}')"
                                class="flex items-center gap-3 w-full px-4 py-3 hover:bg-teal-50 transition-colors text-left border-b border-gray-50 last:border-0">
                            <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                 style="background: linear-gradient(135deg, #0f2044, #1e3a6e);">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400">
                                    @if ($u->department) {{ $u->department }} @endif
                                    @if ($u->employee_id) &bull; {{ $u->employee_id }} @endif
                                </p>
                            </div>
                        </button>
                    @endforeach
                @else
                    <div class="px-4 py-4 text-sm text-gray-400 text-center">
                        <svg class="h-5 w-5 mx-auto text-gray-300 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                        </svg>
                        Tidak ada user ditemukan
                    </div>
                @endif
            </div>
        @endif
    @endif

    @error('user_id')
        <p class="error-msg mt-1.5">{{ $message }}</p>
    @enderror
</div>
