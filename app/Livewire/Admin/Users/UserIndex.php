<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $department = '';

    #[Url]
    public string $status = '';

    public function updatedSearch(): void     { $this->resetPage(); }
    public function updatedDepartment(): void { $this->resetPage(); }
    public function updatedStatus(): void     { $this->resetPage(); }

    public function toggleStatus(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => ! $user->is_active]);
        session()->flash('message', 'Status user berhasil diubah.');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('employee_id', 'like', "%{$this->search}%");
            }))
            ->when($this->department, fn ($q) => $q->where('department', $this->department))
            ->when($this->status !== '', fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->orderBy('name')
            ->paginate(15);

        $departments = User::whereNotNull('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('livewire.admin.users.index', compact('users', 'departments'))
            ->layout('layouts.app-admin');
    }
}
