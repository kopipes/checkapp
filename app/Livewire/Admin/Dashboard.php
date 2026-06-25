<?php

namespace App\Livewire\Admin;

use App\Models\HealthCheck;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalUsers      = User::where('role', 'user')->count();
        $totalChecks     = HealthCheck::count();
        $checksThisMonth = HealthCheck::whereMonth('check_date', now()->month)
                              ->whereYear('check_date', now()->year)->count();
        $attentionCount  = HealthCheck::where('overall_status', 'attention')->count();

        // Daily chart data (last 30 days)
        $dailyData = HealthCheck::selectRaw("date(check_date) as day, COUNT(*) as total")
            ->where('check_date', '>=', now()->subDays(29)->startOfDay())
            ->groupByRaw("date(check_date)")
            ->orderBy('day')
            ->pluck('total', 'day');

        // Fill missing days with 0
        $filledDailyData = collect();
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $filledDailyData[$day] = $dailyData[$day] ?? 0;
        }

        // Abnormal count per parameter
        $abnormalStats = [
            'fasting_blood_sugar' => HealthCheck::where('fasting_blood_sugar_status', 'high')->count(),
            'random_blood_sugar'  => HealthCheck::where('random_blood_sugar_status', 'high')->count(),
            'uric_acid'           => HealthCheck::where('uric_acid_status', 'high')->count(),
            'cholesterol'         => HealthCheck::where('cholesterol_status', 'high')->count(),
            'blood_pressure'      => HealthCheck::whereNotIn('blood_pressure_status', ['optimal', 'normal', 'unmeasured'])->count(),
        ];

        // Recent checks
        $recentChecks = HealthCheck::with('user')
            ->latest('check_date')
            ->limit(8)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'totalUsers', 'totalChecks', 'checksThisMonth',
            'attentionCount', 'filledDailyData',
            'abnormalStats', 'recentChecks'
        ))->layout('layouts.app-admin');
    }
}
