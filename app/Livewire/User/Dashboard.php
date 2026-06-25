<?php

namespace App\Livewire\User;

use App\Models\HealthCheck;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $latest = HealthCheck::where('user_id', $user->id)
            ->latest('check_date')
            ->first();

        $history = HealthCheck::where('user_id', $user->id)
            ->latest('check_date')
            ->limit(5)
            ->get();

        // Chart data - last 10 checks
        $chartData = HealthCheck::where('user_id', $user->id)
            ->orderBy('check_date')
            ->limit(10)
            ->get(['check_date', 'fasting_blood_sugar', 'random_blood_sugar',
                   'uric_acid', 'cholesterol', 'systolic', 'diastolic']);

        return view('livewire.user.dashboard', compact('latest', 'history', 'chartData'))
            ->layout('layouts.app-user');
    }
}
