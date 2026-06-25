<?php

namespace App\Livewire\Admin;

use App\Models\HealthCheck;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AttentionUsers extends Component
{
    use WithPagination;

    public string $parameter = '';
    public string $sortBy    = 'check_date';
    public string $sortDir   = 'desc';

    public function updatedParameter(): void { $this->resetPage(); }
    public function updatedSortBy(): void    { $this->resetPage(); }

    public function setSort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $field;
            $this->sortDir = 'desc';
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = HealthCheck::with('user')
            ->where('overall_status', 'attention')
            ->when($this->parameter, function ($q) {
                if ($this->parameter === 'blood_pressure') {
                    $q->whereNotIn('blood_pressure_status', ['optimal', 'normal', 'unmeasured']);
                } else {
                    $q->where("{$this->parameter}_status", 'high');
                }
            });

        // Sort
        if ($this->sortBy === 'name') {
            $query->join('users', 'health_checks.user_id', '=', 'users.id')
                  ->orderBy('users.name', $this->sortDir)
                  ->select('health_checks.*');
        } elseif ($this->sortBy === 'check_date') {
            $query->orderBy('health_checks.check_date', $this->sortDir);
        } elseif (in_array($this->sortBy, ['fasting_blood_sugar','random_blood_sugar','uric_acid','cholesterol','systolic'])) {
            $query->orderByRaw("CASE WHEN {$this->sortBy} IS NULL THEN 1 ELSE 0 END")
                  ->orderBy($this->sortBy, $this->sortDir);
        }

        $checks = $query->paginate(10);

        return view('livewire.admin.attention-users', compact('checks'));
    }
}
