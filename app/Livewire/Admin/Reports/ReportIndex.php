<?php

namespace App\Livewire\Admin\Reports;

use App\Models\HealthCheck;
use App\Models\User;
use App\Exports\HealthChecksExport;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ReportIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $reportType = 'date';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    #[Url]
    public string $userId = '';

    #[Url]
    public string $department = '';

    #[Url]
    public string $parameter = '';

    #[Url]
    public string $status = '';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo   = now()->format('Y-m-d');
    }

    public function setReportType(string $type): void
    {
        $this->reportType = $type;
        // Reset type-specific filters when switching tabs
        $this->userId    = '';
        $this->department = '';
        $this->parameter  = '';
        $this->status     = '';
        $this->resetPage();
    }

    public function updatedReportType(): void  { $this->resetPage(); }
    public function updatedDateFrom(): void    { $this->resetPage(); }
    public function updatedDateTo(): void      { $this->resetPage(); }
    public function updatedUserId(): void      { $this->resetPage(); }
    public function updatedDepartment(): void  { $this->resetPage(); }
    public function updatedParameter(): void   { $this->resetPage(); }
    public function updatedStatus(): void      { $this->resetPage(); }

    protected function buildQuery()
    {
        $query = HealthCheck::with(['user', 'creator'])
            ->when($this->dateFrom, fn ($q) => $q->where('check_date', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn ($q) => $q->where('check_date', '<=', $this->dateTo . ' 23:59:59'));

        // Apply filters based on report type
        switch ($this->reportType) {
            case 'user':
                $query->when($this->userId, fn ($q) => $q->where('user_id', $this->userId));
                break;

            case 'parameter':
                $query->when($this->parameter, fn ($q) => $q->where("{$this->parameter}_status", 'high'));
                break;

            case 'division':
                $query->when($this->department, fn ($q) => $q->whereHas('user',
                    fn ($u) => $u->where('department', $this->department)
                ));
                break;

            case 'abnormal':
                $query->where('overall_status', 'attention');
                break;

            case 'trend':
                $query->when($this->userId, fn ($q) => $q->where('user_id', $this->userId))
                      ->orderBy('check_date');
                return $query; // don't apply latest() for trend

            default: // 'date'
                break;
        }

        // Global status filter (applies to all except abnormal which already filters)
        if ($this->reportType !== 'abnormal') {
            $query->when($this->status, fn ($q) => $q->where('overall_status', $this->status));
        }

        return $query->latest('check_date');
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(
            new HealthChecksExport($this->buildQuery()),
            'health-checks-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(
            new HealthChecksExport($this->buildQuery()),
            'health-checks-' . now()->format('Y-m-d') . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    public function render()
    {
        $checks = $this->buildQuery()->paginate(20);

        $users = User::where('role', 'user')->orderBy('name')->get();

        $departments = User::where('role', 'user')
            ->whereNotNull('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        // Trend chart data
        $trendData = collect();
        if ($this->reportType === 'trend' && $this->userId) {
            $trendData = HealthCheck::where('user_id', $this->userId)
                ->when($this->dateFrom, fn ($q) => $q->where('check_date', '>=', $this->dateFrom))
                ->when($this->dateTo,   fn ($q) => $q->where('check_date', '<=', $this->dateTo . ' 23:59:59'))
                ->orderBy('check_date')
                ->get(['check_date', 'fasting_blood_sugar', 'random_blood_sugar',
                       'uric_acid', 'cholesterol', 'systolic', 'diastolic']);
        }

        return view('livewire.admin.reports.index',
            compact('checks', 'users', 'departments', 'trendData'))
            ->layout('layouts.app-admin');
    }
}
