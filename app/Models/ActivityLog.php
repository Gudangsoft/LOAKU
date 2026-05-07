<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'user_name', 'action', 'description',
        'model_type', 'model_id', 'properties', 'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        string $action,
        string $description,
        ?Model $model = null,
        array $properties = []
    ): void {
        $user = auth()->user();

        static::create([
            'user_id'    => $user?->id,
            'user_name'  => $user?->name ?? 'System',
            'action'     => $action,
            'description'=> $description,
            'model_type' => $model ? class_basename($model) : null,
            'model_id'   => $model?->id,
            'properties' => $properties ?: null,
            'ip_address' => request()->ip(),
        ]);
    }
}
