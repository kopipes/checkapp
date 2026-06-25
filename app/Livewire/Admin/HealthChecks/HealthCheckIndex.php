<?php

namespace App\Livewire\Admin\HealthChecks;

use App\Models\HealthCheck;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class HealthCheckIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    #[Url]
    public string $department = '';

    #[Url]
    public string $status = '';

    public function updatedSearch(): void     { $this->resetPage(); }
    public function updatedDateFrom(): void   { $this->resetPage(); }
    public function updatedDateTo(): void     { $this->resetPage(); }
    public function updatedDepartment(): void { $this->resetPage(); }
    public function updatedStatus(): void     { $this->resetPage(); }

    public function render()
    {
        $checks = HealthCheck::with(['user', 'creator'])
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$this->search}%")
                  ->orWhere('employee_id', 'like', "%{$this->search}%")
            ))
            ->when($this->dateFrom, fn ($q) => $q->where('check_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->where('check_date', '<=', $this->dateTo . ' 23:59:59'))
            ->when($this->department, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('department', $this->department)
            ))
            ->when($this->status, fn ($q) => $q->where('overall_status', $this->status))
            ->latest('check_date')
            ->paginate(15);

        $departments = User::where('role', 'user')
            ->whereNotNull('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('livewire.admin.health-checks.index', compact('checks', 'departments'))
            ->layout('layouts.app-admin');
    }
}
