<?php

namespace Database\Seeders;

use App\Models\HealthThreshold;
use Illuminate\Database\Seeder;

class HealthThresholdSeeder extends Seeder
{
    public function run(): void
    {
        $thresholds = [
            // Gula Darah Puasa - same for both genders
            [
                'parameter_name'    => 'fasting_blood_sugar',
                'gender'            => 'all',
                'min_value'         => 0,
                'max_value'         => 500,
                'abnormal_operator' => '>',
                'abnormal_value'    => 100,
                'label'             => 'Gula Darah Puasa',
                'unit'              => 'mg/dL',
            ],
            // Gula Darah Sewaktu - same for both genders
            [
                'parameter_name'    => 'random_blood_sugar',
                'gender'            => 'all',
                'min_value'         => 0,
                'max_value'         => 500,
                'abnormal_operator' => '>',
                'abnormal_value'    => 140,
                'label'             => 'Gula Darah Sewaktu',
                'unit'              => 'mg/dL',
            ],
            // Asam Urat - different by gender
            [
                'parameter_name'    => 'uric_acid',
                'gender'            => 'male',
                'min_value'         => 0,
                'max_value'         => 20,
                'abnormal_operator' => '>',
                'abnormal_value'    => 7.0,
                'label'             => 'Asam Urat',
                'unit'              => 'mg/dL',
            ],
            [
                'parameter_name'    => 'uric_acid',
                'gender'            => 'female',
                'min_value'         => 0,
                'max_value'         => 20,
                'abnormal_operator' => '>',
                'abnormal_value'    => 6.0,
                'label'             => 'Asam Urat',
                'unit'              => 'mg/dL',
            ],
            // Kolesterol - same for both genders
            [
                'parameter_name'    => 'cholesterol',
                'gender'            => 'all',
                'min_value'         => 0,
                'max_value'         => 500,
                'abnormal_operator' => '>',
                'abnormal_value'    => 200,
                'label'             => 'Kolesterol',
                'unit'              => 'mg/dL',
            ],
        ];

        foreach ($thresholds as $threshold) {
            HealthThreshold::updateOrCreate(
                ['parameter_name' => $threshold['parameter_name'], 'gender' => $threshold['gender']],
                $threshold
            );
        }
    }
}
