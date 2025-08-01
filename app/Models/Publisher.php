<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $table = 'publishers';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'whatsapp',
        'email',
        'logo',
    ];

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
}
