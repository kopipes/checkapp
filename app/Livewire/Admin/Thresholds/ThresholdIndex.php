<?php

namespace App\Livewire\Admin\Thresholds;

use App\Models\HealthThreshold;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ThresholdIndex extends Component
{
    public ?int $editingId = null;

    public string $abnormal_operator = '>';
    public string $abnormal_value    = '';
    public string $min_value         = '';
    public string $max_value         = '';

    public function startEdit(int $id): void
    {
        $threshold = HealthThreshold::findOrFail($id);
        $this->editingId         = $id;
        $this->abnormal_operator = $threshold->abnormal_operator ?? '>';
        $this->abnormal_value    = $threshold->abnormal_value !== null ? (string) $threshold->abnormal_value : '';
        $this->min_value         = $threshold->min_value !== null ? (string) $threshold->min_value : '';
        $this->max_value         = $threshold->max_value !== null ? (string) $threshold->max_value : '';
    }

    public function cancelEdit(): void
    {
        $this->editingId         = null;
        $this->abnormal_operator = '>';
        $this->abnormal_value    = '';
        $this->min_value         = '';
        $this->max_value         = '';
    }

    public function saveThreshold(): void
    {
        $this->validate([
            'abnormal_operator' => 'required|in:>,>=,<,<=',
            'abnormal_value'    => 'required|numeric',
            'min_value'         => 'nullable|numeric',
            'max_value'         => 'nullable|numeric',
        ]);

        HealthThreshold::findOrFail($this->editingId)->update([
            'abnormal_operator' => $this->abnormal_operator,
            'abnormal_value'    => $this->abnormal_value,
            'min_value'         => $this->min_value !== '' ? $this->min_value : null,
            'max_value'         => $this->max_value !== '' ? $this->max_value : null,
        ]);

        $this->cancelEdit();
        session()->flash('message', 'Ambang batas berhasil diperbarui.');
    }

    public function render()
    {
        $thresholds = HealthThreshold::orderBy('parameter_name')->orderBy('gender')->get();

        return view('livewire.admin.thresholds.index', compact('thresholds'))
            ->layout('layouts.app-admin');
    }
}
