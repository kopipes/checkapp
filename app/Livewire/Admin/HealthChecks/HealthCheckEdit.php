<?php

namespace App\Livewire\Admin\HealthChecks;

use App\Models\HealthCheck;
use App\Models\User;
use App\Services\HealthStatusService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class HealthCheckEdit extends Component
{
    #[Locked]
    public int $healthCheckId;

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

    public function mount(HealthCheck $healthCheck): void
    {
        $this->healthCheckId       = $healthCheck->id;
        $this->user_id             = (string) $healthCheck->user_id;
        $this->check_date          = $healthCheck->check_date->format('Y-m-d');
        $this->fasting_blood_sugar = $healthCheck->fasting_blood_sugar !== null ? (string) $healthCheck->fasting_blood_sugar : '';
        $this->random_blood_sugar  = $healthCheck->random_blood_sugar !== null ? (string) $healthCheck->random_blood_sugar : '';
        $this->uric_acid           = $healthCheck->uric_acid !== null ? (string) $healthCheck->uric_acid : '';
        $this->cholesterol         = $healthCheck->cholesterol !== null ? (string) $healthCheck->cholesterol : '';
        $this->systolic            = $healthCheck->systolic !== null ? (string) $healthCheck->systolic : '';
        $this->diastolic           = $healthCheck->diastolic !== null ? (string) $healthCheck->diastolic : '';
        $this->notes               = $healthCheck->notes ?? '';

        // Pre-populate user search
        $user = $healthCheck->user;
        if ($user) {
            $this->userSearch   = $user->name;
            $this->selectedUser = [
                'id'         => $user->id,
                'name'       => $user->name,
                'department' => $user->department,
            ];
        }
    }

    public function updatedUserSearch(): void
    {
        $this->showUserDropdown = strlen($this->userSearch) >= 1;
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

        return User::where(function ($q) {
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

        $data = array_merge($validated, $statuses, [
            'user_id'             => (int) $this->user_id,
            'fasting_blood_sugar' => $this->fasting_blood_sugar !== '' ? $this->fasting_blood_sugar : null,
            'random_blood_sugar'  => $this->random_blood_sugar !== '' ? $this->random_blood_sugar : null,
            'uric_acid'           => $this->uric_acid !== '' ? $this->uric_acid : null,
            'cholesterol'         => $this->cholesterol !== '' ? $this->cholesterol : null,
            'systolic'            => $this->systolic !== '' ? $this->systolic : null,
            'diastolic'           => $this->diastolic !== '' ? $this->diastolic : null,
        ]);

        HealthCheck::findOrFail($this->healthCheckId)->update($data);

        session()->flash('message', 'Data pemeriksaan berhasil diperbarui.');
        $this->redirect(route('admin.health-checks.index'), navigate: true);
    }

    public function render()
    {
        $healthCheck = HealthCheck::with('user')->findOrFail($this->healthCheckId);

        return view('livewire.admin.health-checks.edit', compact('healthCheck'))
            ->layout('layouts.app-admin');
    }
}
