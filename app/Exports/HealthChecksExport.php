<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HealthChecksExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama User',
            'Divisi',
            'Tanggal Pemeriksaan',
            'Gula Darah Puasa',
            'Status GDP',
            'Gula Darah Sewaktu',
            'Status GDS',
            'Asam Urat',
            'Status Asam Urat',
            'Kolesterol',
            'Status Kolesterol',
            'Sistolik',
            'Diastolik',
            'Status Tensi',
            'Status Keseluruhan',
            'Catatan',
            'Dicatat Oleh',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user->name ?? '-',
            $row->user->department ?? '-',
            $row->check_date->format('d/m/Y'),
            $row->fasting_blood_sugar ?? 'Tidak Diukur',
            $this->statusLabel($row->fasting_blood_sugar_status),
            $row->random_blood_sugar ?? 'Tidak Diukur',
            $this->statusLabel($row->random_blood_sugar_status),
            $row->uric_acid ?? 'Tidak Diukur',
            $this->statusLabel($row->uric_acid_status),
            $row->cholesterol ?? 'Tidak Diukur',
            $this->statusLabel($row->cholesterol_status),
            $row->systolic ?? 'Tidak Diukur',
            $row->diastolic ?? 'Tidak Diukur',
            $this->bpStatusLabel($row->blood_pressure_status),
            $row->overall_status === 'normal' ? 'Normal' : 'Perlu Perhatian',
            $row->notes ?? '',
            $row->creator->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'normal'     => 'Normal',
            'high'       => 'Tinggi',
            'unmeasured' => 'Tidak Diukur',
            default      => '-',
        };
    }

    private function bpStatusLabel(?string $status): string
    {
        return match ($status) {
            'optimal'        => 'Optimal',
            'normal'         => 'Normal',
            'normal_high'    => 'Normal Tinggi',
            'hypertension_1' => 'Hipertensi Derajat 1',
            'hypertension_2' => 'Hipertensi Derajat 2',
            'hypertension_3' => 'Hipertensi Derajat 3',
            'unmeasured'     => 'Tidak Diukur',
            default          => '-',
        };
    }
}
