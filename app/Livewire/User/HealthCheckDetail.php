<?php

namespace App\Livewire\User;

use App\Models\HealthCheck;
use App\Services\HealthStatusService;
use Livewire\Component;

class HealthCheckDetail extends Component
{
    public HealthCheck $healthCheck;

    public function mount(HealthCheck $healthCheck): void
    {
        // Ensure user can only see their own data
        abort_if($healthCheck->user_id !== auth()->id(), 403);
        $this->healthCheck = $healthCheck;
    }

    public function render()
    {
        $service = app(HealthStatusService::class);

        return view('livewire.user.detail', [
            'check'   => $this->healthCheck,
            'service' => $service,
        ])->layout('layouts.app-user');
    }
}
