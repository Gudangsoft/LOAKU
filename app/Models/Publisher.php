<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $table = 'publishers';

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'whatsapp',
        'email',
        'website',
        'logo',
        'status',
        'validation_token',
        'validated_at',
        'validated_by',
        'validation_notes',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    /**
     * Validator relationship
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Check if publisher is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if publisher is pending validation
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if publisher is suspended
     */
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    /**
     * Generate validation token
     */
    public function generateValidationToken()
    {
        $this->validation_token = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        $this->save();
        return $this->validation_token;
    }

    /**
     * Activate publisher
     */
    public function activate($validatorId = null, $notes = null)
    {
        $this->status = 'active';
        $this->validated_at = now();
        $this->validated_by = $validatorId;
        $this->validation_notes = $notes;
        $this->save();
    }

    /**
     * Suspend publisher
     */
    public function suspend($validatorId = null, $reason = null)
    {
        $this->status = 'suspended';
        $this->validated_at = now();
        $this->validated_by = $validatorId;
        $this->validation_notes = $reason;
        $this->save();
    }
}
