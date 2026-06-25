<?php

namespace App\Services;

use App\Models\HealthThreshold;

class HealthStatusService
{
    /**
     * Calculate statuses for all parameters and return array
     * of status fields + overall_status.
     */
    public function calculateStatuses(array $data, string $gender): array
    {
        $parameters = [
            'fasting_blood_sugar',
            'random_blood_sugar',
            'uric_acid',
            'cholesterol',
        ];

        $statuses = [];
        $hasAbnormal = false;

        foreach ($parameters as $param) {
            $value = $data[$param] ?? null;

            if (is_null($value) || $value === '') {
                $statuses["{$param}_status"] = 'unmeasured';
            } elseif (HealthThreshold::isAbnormal($param, (float) $value, $gender)) {
                $statuses["{$param}_status"] = 'high';
                $hasAbnormal = true;
            } else {
                $statuses["{$param}_status"] = 'normal';
            }
        }

        // Blood pressure status
        $systolic  = $data['systolic'] ?? null;
        $diastolic = $data['diastolic'] ?? null;

        if (is_null($systolic) || $systolic === '' || is_null($diastolic) || $diastolic === '') {
            $statuses['blood_pressure_status'] = 'unmeasured';
        } else {
            $bpStatus = $this->bloodPressureStatus((int) $systolic, (int) $diastolic);
            $statuses['blood_pressure_status'] = $bpStatus;
            if (! in_array($bpStatus, ['optimal', 'normal'])) {
                $hasAbnormal = true;
            }
        }

        $statuses['overall_status'] = $hasAbnormal ? 'attention' : 'normal';

        return $statuses;
    }

    public function bloodPressureStatus(int $systolic, int $diastolic): string
    {
        if ($systolic < 120 && $diastolic < 80)   return 'optimal';
        if ($systolic < 130 && $diastolic < 85)   return 'normal';
        if ($systolic <= 139 || $diastolic <= 89) return 'normal_high';
        if ($systolic <= 159 || $diastolic <= 99) return 'hypertension_1';
        if ($systolic <= 179 || $diastolic <= 109) return 'hypertension_2';
        return 'hypertension_3';
    }

    public function bloodPressureLabel(string $status): string
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
