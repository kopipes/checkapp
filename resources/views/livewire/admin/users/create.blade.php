<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" wire:navigate class="back-link">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar User
        </a>
        <h2 class="page-title mt-1">Tambah User Baru</h2>
    </div>

    <form wire:submit="save" class="card p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="sm:col-span-2">
                <label class="label">Nama Lengkap <span class="text-red-400">*</span></label>
                <input wire:model="name" type="text" class="input @error('name') input-error @enderror" placeholder="Nama lengkap" />
                @error('name') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Email <span class="text-red-400">*</span></label>
                <input wire:model="email" type="email" class="input @error('email') input-error @enderror" placeholder="email@example.com" />
                @error('email') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Username</label>
                <input wire:model="username" type="text" class="input @error('username') input-error @enderror" placeholder="username" />
                @error('username') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Password <span class="text-red-400">*</span></label>
                <input wire:model="password" type="password" class="input @error('password') input-error @enderror" placeholder="Min. 6 karakter" />
                @error('password') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Role <span class="text-red-400">*</span></label>
                <select wire:model="role" class="input">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div>
                <label class="label">Jenis Kelamin</label>
                <select wire:model="gender" class="input">
                    <option value="">-- Pilih --</option>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                </select>
            </div>

            <div>
                <label class="label">Tanggal Lahir</label>
                <input wire:model="birth_date" type="date" class="input" />
            </div>

            <div>
                <label class="label">Divisi / Departemen</label>
                <input wire:model="department" type="text" class="input" placeholder="Nama divisi" />
            </div>

            <div>
                <label class="label">ID Karyawan</label>
                <input wire:model="employee_id" type="text" class="input" placeholder="EMP001" />
            </div>

            <div class="sm:col-span-2 pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input wire:model="is_active" type="checkbox"
                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 focus:ring-offset-0 w-4 h-4" />
                    <div>
                        <span class="text-sm font-medium text-gray-700">Akun Aktif</span>
                        <p class="text-xs text-gray-400">User dapat login ke aplikasi</p>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.users.index') }}" wire:navigate class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan User
            </button>
        </div>
    </form>
</div>
