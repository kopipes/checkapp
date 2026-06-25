<?php

namespace App\Livewire\Admin\HealthChecks;

use App\Models\HealthCheck;
use App\Models\User;
use App\Services\HealthStatusService;
use Livewire\Component;

class HealthCheckCreate extends Component
{
    public string $user_id             = '';
    public string $userSearch          = '';
    public bool   $showUserDropdown    = false;
    public ?array $selectedUser        = null;

    public string $check_date          = '';
    public string $fasting_blood_sugar = '';
    public string $random_blood_sugar  = '';
    public string $uric_acid           = '';
    public string $cholesterol         = '';
    public string $systolic            = '';
    public string $diastolic           = '';
    public string $notes               = '';

    public function mount(): void
    {
        $this->check_date = now()->format('Y-m-d');
    }

    public function updatedUserSearch(): void
    {
        $this->showUserDropdown = strlen($this->userSearch) >= 1;
        // Clear selection if user changes the search text
        if ($this->selectedUser && $this->userSearch !== $this->selectedUser['name']) {
            $this->user_id      = '';
            $this->selectedUser = null;
        }
    }

    public function selectUser(int $id, string $name, ?string $department): void
    {
        $this->user_id          = (string) $id;
        $this->userSearch       = $name;
        $this->selectedUser     = ['id' => $id, 'name' => $name, 'department' => $department];
        $this->showUserDropdown = false;
    }

    public function clearUser(): void
    {
        $this->user_id          = '';
        $this->userSearch       = '';
        $this->selectedUser     = null;
        $this->showUserDropdown = false;
    }

    public function getUserResultsProperty()
    {
        if (strlen($this->userSearch) < 1) return collect();

        return User::where('role', 'user')
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->userSearch}%")
                  ->orWhere('employee_id', 'like', "%{$this->userSearch}%")
                  ->orWhere('department', 'like', "%{$this->userSearch}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'department', 'employee_id']);
    }

    protected function rules(): array
    {
        return [
            'user_id'             => 'required|exists:users,id',
            'check_date'          => 'required|date',
            'fasting_blood_sugar' => 'nullable|numeric|min:0|max:500',
            'random_blood_sugar'  => 'nullable|numeric|min:0|max:500',
            'uric_acid'           => 'nullable|numeric|min:0|max:20',
            'cholesterol'         => 'nullable|numeric|min:0|max:500',
            'systolic'            => 'nullable|integer|min:50|max:250',
            'diastolic'           => 'nullable|integer|min:30|max:150',
            'notes'               => 'nullable|string|max:1000',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $user     = User::findOrFail($this->user_id);
        $service  = app(HealthStatusService::class);
        $statuses = $service->calculateStatuses($validated, $user->gender ?? 'male');

        HealthCheck::create(array_merge($validated, $statuses, [
            'user_id'    => $this->user_id,
            'created_by' => auth()->id(),
        ]));

        session()->flash('message', 'Data pemeriksaan berhasil disimpan.');
        $this->redirect(route('admin.health-checks.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.health-checks.create')
            ->layout('layouts.app-admin');
    }
}
