<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaTemplate extends Model
{
    use HasFactory;

    protected $table = 'loa_templates';

    protected $fillable = [
        'name',
        'description',
        'language',
        'format',
        'header_template',
        'body_template',
        'footer_template',
        'variables',
        'css_styles',
        'is_active',
        'is_default',
        'publisher_id',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    // Get default template for a language
    public static function getDefault($language = 'id', $publisherId = null)
    {
        $query = static::where('is_active', true)
            ->where('language', $language)
            ->orWhere('language', 'both');

        if ($publisherId) {
            $query->where(function($q) use ($publisherId) {
                $q->where('publisher_id', $publisherId)
                  ->orWhereNull('publisher_id');
            });
        }

        return $query->where('is_default', true)->first() 
            ?: $query->first();
    }

    // Get available template variables
    public function getAvailableVariables()
    {
        $defaultVars = [
            '{{publisher_name}}' => 'Nama Penerbit',
            '{{publisher_address}}' => 'Alamat Penerbit',
            '{{publisher_email}}' => 'Email Penerbit',
            '{{publisher_phone}}' => 'Telepon Penerbit',
            '{{journal_name}}' => 'Nama Jurnal',
            '{{journal_issn_e}}' => 'E-ISSN Jurnal',
            '{{journal_issn_p}}' => 'P-ISSN Jurnal',
            '{{chief_editor}}' => 'Pemimpin Redaksi',
            '{{loa_code}}' => 'Kode LOA',
            '{{article_title}}' => 'Judul Artikel',
            '{{author_name}}' => 'Nama Penulis',
            '{{author_email}}' => 'Email Penulis',
            '{{volume}}' => 'Volume',
            '{{number}}' => 'Nomor',
            '{{month}}' => 'Bulan',
            '{{year}}' => 'Tahun',
            '{{registration_number}}' => 'Nomor Registrasi',
            '{{approval_date}}' => 'Tanggal Persetujuan',
            '{{current_date}}' => 'Tanggal Sekarang',
            '{{verification_url}}' => 'URL Verifikasi',
            '{{qr_code_url}}' => 'URL QR Code',
        ];

        return array_merge($defaultVars, $this->variables ?? []);
    }
}
