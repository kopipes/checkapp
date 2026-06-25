<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_value',
        'new_value',
        'ip_address',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_value'  => 'array',
            'new_value'  => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, string $table, int $recordId, ?array $oldValue = null, ?array $newValue = null): void
    {
        static::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'table_name' => $table,
            'record_id'  => $recordId,
            'old_value'  => $oldValue,
            'new_value'  => $newValue,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
