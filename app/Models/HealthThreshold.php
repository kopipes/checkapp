<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthThreshold extends Model
{
    protected $fillable = [
        'parameter_name',
        'gender',
        'min_value',
        'max_value',
        'abnormal_operator',
        'abnormal_value',
        'label',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'min_value'      => 'decimal:2',
            'max_value'      => 'decimal:2',
            'abnormal_value' => 'decimal:2',
        ];
    }

    /**
     * Evaluate whether a value is abnormal given gender.
     */
    public static function isAbnormal(string $parameter, float $value, string $gender = 'male'): bool
    {
        $threshold = static::where('parameter_name', $parameter)
            ->where(function ($q) use ($gender) {
                $q->where('gender', $gender)->orWhere('gender', 'all');
            })
            ->orderByRaw("CASE WHEN gender = ? THEN 0 ELSE 1 END", [$gender])
            ->first();

        if (! $threshold || is_null($threshold->abnormal_value)) {
            return false;
        }

        return match ($threshold->abnormal_operator) {
            '>'  => $value > $threshold->abnormal_value,
            '>=' => $value >= $threshold->abnormal_value,
            '<'  => $value < $threshold->abnormal_value,
            '<=' => $value <= $threshold->abnormal_value,
            default => false,
        };
    }
}
