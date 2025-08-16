<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Support extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'website',
        'description',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope untuk support aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Accessor untuk URL logo
    public function getLogoUrlAttribute()
    {
        if ($this->logo && Storage::exists('public/supports/' . $this->logo)) {
            return Storage::url('public/supports/' . $this->logo);
        }
        return asset('images/no-logo.png'); // Default image
    }
}
