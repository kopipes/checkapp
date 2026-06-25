<div>
    <div class="page-header">
        <div>
            <h2 class="page-title">Manajemen User</h2>
            <p class="page-subtitle">Kelola data karyawan yang terdaftar</p>
        </div>
        <a href="{{ route('admin.users.create') }}" wire:navigate class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User
        </a>
    </div>

    {{-- Filters --}}
    <div class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Cari nama, email, ID karyawan..."
                       class="input pl-9" />
            </div>
            <select wire:model.live="department" class="input">
                <option value="">Semua Divisi</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
            <select wire:model.live="status" class="input">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th class="th">Nama</th>
                        <th class="th hidden sm:table-cell">Email</th>
                        <th class="th hidden md:table-cell">Divisi</th>
                        <th class="th hidden lg:table-cell">ID Karyawan</th>
                        <th class="th text-center">Status</th>
                        <th class="th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="td">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                         style="background: linear-gradient(135deg, #0f2044, #1e3a6e);">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400 sm:hidden">{{ $user->email }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if ($user->role === 'admin')
                                                <span class="badge bg-purple-50 text-purple-700 ring-1 ring-purple-200/60">Admin</span>
                                            @else
                                                <span class="badge bg-blue-50 text-blue-700 ring-1 ring-blue-200/60">User</span>
                                            @endif
                                            @if($user->gender)
                                                <span class="text-xs text-gray-400">{{ $user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="td hidden sm:table-cell">
                                <span class="text-gray-600">{{ $user->email }}</span>
                            </td>
                            <td class="td hidden md:table-cell">
                                @if ($user->department)
                                    <span class="badge-navy">{{ $user->department }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="td hidden lg:table-cell text-gray-500">{{ $user->employee_id ?? '—' }}</td>
                            <td class="td text-center">
                                <button wire:click="toggleStatus({{ $user->id }})"
                                        class="badge transition-all cursor-pointer
                                               {{ $user->is_active ? 'badge-green hover:bg-emerald-100' : 'badge-gray hover:bg-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $user->is_active ? 'bg-emerald-400' : 'bg-gray-300' }}"></span>
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="td text-right">
                                <a href="{{ route('admin.users.edit', $user) }}" wire:navigate
                                   class="btn btn-sm btn-secondary">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="td py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-sm text-gray-400">Tidak ada user ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
