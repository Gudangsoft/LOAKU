<?php

namespace Database\Seeders;

use App\Models\LoaTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoaTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Indonesian Template
        LoaTemplate::create([
            'name' => 'Template Default Indonesia',
            'description' => 'Template default untuk LOA dalam bahasa Indonesia',
            'language' => 'id',
            'format' => 'html',
            'header_template' => $this->getDefaultHeaderID(),
            'body_template' => $this->getDefaultBodyID(),
            'footer_template' => $this->getDefaultFooterID(),
            'css_styles' => $this->getDefaultCSS(),
            'is_active' => true,
            'is_default' => true,
        ]);

        // Default English Template
        LoaTemplate::create([
            'name' => 'Default English Template',
            'description' => 'Default template for LOA in English language',
            'language' => 'en',
            'format' => 'html',
            'header_template' => $this->getDefaultHeaderEN(),
            'body_template' => $this->getDefaultBodyEN(),
            'footer_template' => $this->getDefaultFooterEN(),
            'css_styles' => $this->getDefaultCSS(),
            'is_active' => true,
            'is_default' => true,
        ]);

        // Bilingual Template
        LoaTemplate::create([
            'name' => 'Template Dwibahasa',
            'description' => 'Template untuk LOA dwibahasa (Indonesia dan Inggris)',
            'language' => 'both',
            'format' => 'html',
            'header_template' => $this->getBilingualHeader(),
            'body_template' => $this->getBilingualBody(),
            'footer_template' => $this->getBilingualFooter(),
            'css_styles' => $this->getDefaultCSS(),
            'is_active' => true,
            'is_default' => false,
        ]);
    }

    private function getDefaultHeaderID()
    {
        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penerimaan Naskah - {{loa_code}}</title>
    <style>{{css_styles}}</style>
</head>
<body>
<div class="header text-center mb-4">
    @if({{publisher_logo}})
        <img src="{{publisher_logo}}" alt="Logo" class="publisher-logo mb-3">
    @endif
    <h1 class="publisher-name">{{publisher_name}}</h1>
    <p class="publisher-address">{{publisher_address}}</p>
    <p class="publisher-contact">Email: {{publisher_email}} | Phone: {{publisher_phone}}</p>
    <h2 class="certificate-title mt-4">
        SURAT PERSETUJUAN NASKAH<br>
        <small>(LETTER OF ACCEPTANCE)</small>
    </h2>
</div>';
    }

    private function getDefaultBodyID()
    {
        return '<div class="loa-content">
    <div class="loa-code text-center mb-4">
        <h3>{{loa_code}}</h3>
    </div>
    
    <div class="introduction mb-4">
        <p>Dengan ini kami menyatakan bahwa naskah artikel ilmiah berikut:</p>
    </div>
    
    <div class="article-details mb-4">
        <table class="table">
            <tr><td><strong>Judul Artikel:</strong></td><td>{{article_title}}</td></tr>
            <tr><td><strong>Penulis:</strong></td><td>{{author_name}}</td></tr>
            <tr><td><strong>Email:</strong></td><td>{{author_email}}</td></tr>
            <tr><td><strong>Jurnal:</strong></td><td>{{journal_name}}</td></tr>
            <tr><td><strong>ISSN:</strong></td><td>E-ISSN: {{journal_issn_e}} | P-ISSN: {{journal_issn_p}}</td></tr>
            <tr><td><strong>Volume/Nomor:</strong></td><td>Vol. {{volume}}, No. {{number}}</td></tr>
            <tr><td><strong>Edisi:</strong></td><td>{{month}} {{year}}</td></tr>
            <tr><td><strong>No. Registrasi:</strong></td><td>{{registration_number}}</td></tr>
        </table>
    </div>
    
    <div class="acceptance-statement text-center mb-4">
        <div class="alert alert-success">
            <h3>TELAH DITERIMA UNTUK DIPUBLIKASIKAN</h3>
            <small>(HAS BEEN ACCEPTED FOR PUBLICATION)</small>
        </div>
    </div>
    
    <div class="description mb-4">
        <p>Naskah telah melalui proses review dan memenuhi persyaratan untuk dipublikasikan pada jurnal <strong>{{journal_name}}</strong>.</p>
        <p>Surat persetujuan ini merupakan bukti resmi penerimaan naskah untuk publikasi dan dapat digunakan untuk keperluan akademik dan profesional.</p>
    </div>
</div>';
    }

    private function getDefaultFooterID()
    {
        return '<div class="signature-section text-right mb-4">
    <p>{{approval_date}}</p>
    <p><strong>Pemimpin Redaksi</strong></p>
    
    @if({{signature_stamp}})
        <div class="signature-stamp my-3">
            <img src="{{signature_stamp}}" alt="Signature" class="signature-image">
        </div>
    @endif
    
    <div class="signature-name">
        <strong>{{chief_editor}}</strong>
    </div>
</div>

<div class="verification-info text-center">
    <hr>
    <p><strong>Verifikasi Dokumen:</strong></p>
    <p>Kunjungi: {{verification_url}}</p>
    <p>Kode LOA: {{loa_code}}</p>
    <p><small>Dicetak pada: {{current_date}}</small></p>
</div>
</body>
</html>';
    }

    private function getDefaultHeaderEN()
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter of Acceptance - {{loa_code}}</title>
    <style>{{css_styles}}</style>
</head>
<body>
<div class="header text-center mb-4">
    @if({{publisher_logo}})
        <img src="{{publisher_logo}}" alt="Logo" class="publisher-logo mb-3">
    @endif
    <h1 class="publisher-name">{{publisher_name}}</h1>
    <p class="publisher-address">{{publisher_address}}</p>
    <p class="publisher-contact">Email: {{publisher_email}} | Phone: {{publisher_phone}}</p>
    <h2 class="certificate-title mt-4">
        LETTER OF ACCEPTANCE<br>
        <small>(SURAT PERSETUJUAN NASKAH)</small>
    </h2>
</div>';
    }

    private function getDefaultBodyEN()
    {
        return '<div class="loa-content">
    <div class="loa-code text-center mb-4">
        <h3>{{loa_code}}</h3>
    </div>
    
    <div class="introduction mb-4">
        <p>We hereby declare that the following scientific article manuscript:</p>
    </div>
    
    <div class="article-details mb-4">
        <table class="table">
            <tr><td><strong>Article Title:</strong></td><td>{{article_title}}</td></tr>
            <tr><td><strong>Author:</strong></td><td>{{author_name}}</td></tr>
            <tr><td><strong>Email:</strong></td><td>{{author_email}}</td></tr>
            <tr><td><strong>Journal:</strong></td><td>{{journal_name}}</td></tr>
            <tr><td><strong>ISSN:</strong></td><td>E-ISSN: {{journal_issn_e}} | P-ISSN: {{journal_issn_p}}</td></tr>
            <tr><td><strong>Volume/Number:</strong></td><td>Vol. {{volume}}, No. {{number}}</td></tr>
            <tr><td><strong>Issue:</strong></td><td>{{month}} {{year}}</td></tr>
            <tr><td><strong>Registration No.:</strong></td><td>{{registration_number}}</td></tr>
        </table>
    </div>
    
    <div class="acceptance-statement text-center mb-4">
        <div class="alert alert-success">
            <h3>HAS BEEN ACCEPTED FOR PUBLICATION</h3>
            <small>(TELAH DITERIMA UNTUK DIPUBLIKASIKAN)</small>
        </div>
    </div>
    
    <div class="description mb-4">
        <p>The manuscript has undergone review process and meets the requirements for publication in <strong>{{journal_name}}</strong>.</p>
        <p>This letter of acceptance serves as official proof of manuscript acceptance for publication and can be used for academic and professional purposes.</p>
    </div>
</div>';
    }

    private function getDefaultFooterEN()
    {
        return '<div class="signature-section text-right mb-4">
    <p>{{approval_date}}</p>
    <p><strong>Editor-in-Chief</strong></p>
    
    @if({{signature_stamp}})
        <div class="signature-stamp my-3">
            <img src="{{signature_stamp}}" alt="Signature" class="signature-image">
        </div>
    @endif
    
    <div class="signature-name">
        <strong>{{chief_editor}}</strong>
    </div>
</div>

<div class="verification-info text-center">
    <hr>
    <p><strong>Document Verification:</strong></p>
    <p>Visit: {{verification_url}}</p>
    <p>LOA Code: {{loa_code}}</p>
    <p><small>Generated on: {{current_date}}</small></p>
</div>
</body>
</html>';
    }

    private function getBilingualHeader()
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter of Acceptance - {{loa_code}}</title>
    <style>{{css_styles}}</style>
</head>
<body>
<div class="header text-center mb-4">
    @if({{publisher_logo}})
        <img src="{{publisher_logo}}" alt="Logo" class="publisher-logo mb-3">
    @endif
    <h1 class="publisher-name">{{publisher_name}}</h1>
    <p class="publisher-address">{{publisher_address}}</p>
    <p class="publisher-contact">Email: {{publisher_email}} | Phone: {{publisher_phone}}</p>
    <h2 class="certificate-title mt-4">
        SURAT PERSETUJUAN NASKAH<br>
        LETTER OF ACCEPTANCE
    </h2>
</div>';
    }

    private function getBilingualBody()
    {
        return '<div class="loa-content">
    <div class="loa-code text-center mb-4">
        <h3>{{loa_code}}</h3>
    </div>
    
    <div class="introduction mb-4">
        <p><strong>Indonesia:</strong> Dengan ini kami menyatakan bahwa naskah artikel ilmiah berikut:</p>
        <p><strong>English:</strong> We hereby declare that the following scientific article manuscript:</p>
    </div>
    
    <div class="article-details mb-4">
        <table class="table">
            <tr><td><strong>Judul Artikel / Article Title:</strong></td><td>{{article_title}}</td></tr>
            <tr><td><strong>Penulis / Author:</strong></td><td>{{author_name}}</td></tr>
            <tr><td><strong>Email:</strong></td><td>{{author_email}}</td></tr>
            <tr><td><strong>Jurnal / Journal:</strong></td><td>{{journal_name}}</td></tr>
            <tr><td><strong>ISSN:</strong></td><td>E-ISSN: {{journal_issn_e}} | P-ISSN: {{journal_issn_p}}</td></tr>
            <tr><td><strong>Volume/Nomor / Volume/Number:</strong></td><td>Vol. {{volume}}, No. {{number}}</td></tr>
            <tr><td><strong>Edisi / Issue:</strong></td><td>{{month}} {{year}}</td></tr>
            <tr><td><strong>No. Registrasi / Registration No.:</strong></td><td>{{registration_number}}</td></tr>
        </table>
    </div>
    
    <div class="acceptance-statement text-center mb-4">
        <div class="alert alert-success">
            <h3>TELAH DITERIMA UNTUK DIPUBLIKASIKAN<br>HAS BEEN ACCEPTED FOR PUBLICATION</h3>
        </div>
    </div>
    
    <div class="description mb-4">
        <p><strong>Indonesia:</strong> Naskah telah melalui proses review dan memenuhi persyaratan untuk dipublikasikan pada jurnal <strong>{{journal_name}}</strong>. Surat persetujuan ini merupakan bukti resmi penerimaan naskah untuk publikasi.</p>
        <p><strong>English:</strong> The manuscript has undergone review process and meets the requirements for publication in <strong>{{journal_name}}</strong>. This letter of acceptance serves as official proof of manuscript acceptance for publication.</p>
    </div>
</div>';
    }

    private function getBilingualFooter()
    {
        return '<div class="signature-section text-right mb-4">
    <p>{{approval_date}}</p>
    <p><strong>Pemimpin Redaksi / Editor-in-Chief</strong></p>
    
    @if({{signature_stamp}})
        <div class="signature-stamp my-3">
            <img src="{{signature_stamp}}" alt="Signature" class="signature-image">
        </div>
    @endif
    
    <div class="signature-name">
        <strong>{{chief_editor}}</strong>
    </div>
</div>

<div class="verification-info text-center">
    <hr>
    <p><strong>Verifikasi Dokumen / Document Verification:</strong></p>
    <p>Kunjungi / Visit: {{verification_url}}</p>
    <p>Kode LOA / LOA Code: {{loa_code}}</p>
    <p><small>Dicetak pada / Generated on: {{current_date}}</small></p>
</div>
</body>
</html>';
    }

    private function getDefaultCSS()
    {
        return 'body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
.header { margin-bottom: 30px; }
.publisher-logo { max-height: 80px; }
.publisher-name { color: #2c5aa0; font-size: 24px; margin: 10px 0; }
.certificate-title { color: #2c5aa0; font-size: 20px; font-weight: bold; }
.loa-code { background: #f8f9fa; padding: 15px; border: 2px solid #2c5aa0; }
.article-details table { width: 100%; border-collapse: collapse; }
.article-details td { padding: 8px; border-bottom: 1px solid #ddd; }
.acceptance-statement { margin: 30px 0; }
.alert-success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; color: #155724; }
.signature-section { margin-top: 50px; }
.signature-image { max-height: 100px; }
.verification-info { font-size: 12px; color: #666; margin-top: 40px; }
.text-center { text-align: center; }
.text-right { text-align: right; }
.mb-4 { margin-bottom: 1.5rem; }
.my-3 { margin: 1rem 0; }
.mt-4 { margin-top: 1.5rem; }';
    }
}
