<?php

namespace App\Livewire\User;

use App\Models\HealthCheck;
use Livewire\Component;
use Livewire\WithPagination;

class HealthCheckHistory extends Component
{
    use WithPagination;

    public string $dateFrom = '';
    public string $dateTo   = '';

    protected $queryString = ['dateFrom', 'dateTo'];

    public function render()
    {
        $checks = HealthCheck::where('user_id', auth()->id())
            ->when($this->dateFrom, fn ($q) => $q->where('check_date', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn ($q) => $q->where('check_date', '<=', $this->dateTo . ' 23:59:59'))
            ->latest('check_date')
            ->paginate(10);

        return view('livewire.user.history', compact('checks'))
            ->layout('layouts.app-user');
    }
}
