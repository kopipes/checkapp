<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'check_date',
        'fasting_blood_sugar',
        'random_blood_sugar',
        'uric_acid',
        'cholesterol',
        'systolic',
        'diastolic',
        'fasting_blood_sugar_status',
        'random_blood_sugar_status',
        'uric_acid_status',
        'cholesterol_status',
        'blood_pressure_status',
        'overall_status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'check_date'           => 'date',
            'fasting_blood_sugar'  => 'decimal:2',
            'random_blood_sugar'   => 'decimal:2',
            'uric_acid'            => 'decimal:2',
            'cholesterol'          => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Status helpers
    public function isNormal(): bool
    {
        return $this->overall_status === 'normal';
    }

    public function getBloodPressureLabelAttribute(): string
    {
        if (is_null($this->systolic) && is_null($this->diastolic)) {
            return 'unmeasured';
        }

        $sys = $this->systolic;
        $dia = $this->diastolic;

        if ($sys < 120 && $dia < 80)  return 'optimal';
        if ($sys < 130 && $dia < 85)  return 'normal';
        if ($sys <= 139 || $dia <= 89) return 'normal_high';
        if ($sys <= 159 || $dia <= 99) return 'hypertension_1';
        if ($sys <= 179 || $dia <= 109) return 'hypertension_2';
        return 'hypertension_3';
    }
}
