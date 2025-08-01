<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $table = 'journals';

    protected $fillable = [
        'name',
        'e_issn',
        'p_issn',
        'chief_editor',
        'logo',
        'ttd_stample',
        'website',
        'publisher_id',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function loaRequests()
    {
        return $this->hasMany(LoaRequest::class);
    }
}
