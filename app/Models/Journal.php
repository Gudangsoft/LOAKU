<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $table = 'journals';

    protected $fillable = [
        'user_id',
        'name',
        'e_issn',
        'p_issn',
        'chief_editor',
        'email',
        'description',
        'logo',
        'signature_stamp',
        'website',
        'publisher_id',
        'sinta_id',
        'doi_prefix',
        'garuda_id',
        'accreditation_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function loaRequests()
    {
        return $this->hasMany(LoaRequest::class);
    }
}
