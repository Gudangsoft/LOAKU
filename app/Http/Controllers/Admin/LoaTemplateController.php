<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaTemplate;
use App\Models\Publisher;
use Illuminate\Http\Request;

class LoaTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = LoaTemplate::with('publisher')
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.loa-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $publishers = Publisher::all();
        $defaultTemplate = $this->getDefaultTemplate();
        
        return view('admin.loa-templates.create', compact('publishers', 'defaultTemplate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'required|in:id,en,both',
            'format' => 'required|in:html,pdf',
            'header_template' => 'required|string',
            'body_template' => 'required|string',
            'footer_template' => 'required|string',
            'css_styles' => 'nullable|string',
            'publisher_id' => 'nullable|exists:publishers,id',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['is_default'] = $request->has('is_default');

        // If this is set as default, unset other defaults for the same language
        if ($data['is_default']) {
            LoaTemplate::where('language', $data['language'])
                ->orWhere('language', 'both')
                ->update(['is_default' => false]);
        }

        LoaTemplate::create($data);

        return redirect()->route('admin.loa-templates.index')
            ->with('success', 'Template LOA berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoaTemplate $loaTemplate)
    {
        return view('admin.loa-templates.show', compact('loaTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoaTemplate $loaTemplate)
    {
        $publishers = Publisher::all();
        
        return view('admin.loa-templates.edit', compact('loaTemplate', 'publishers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoaTemplate $loaTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'required|in:id,en,both',
            'format' => 'required|in:html,pdf',
            'header_template' => 'required|string',
            'body_template' => 'required|string',
            'footer_template' => 'required|string',
            'css_styles' => 'nullable|string',
            'publisher_id' => 'nullable|exists:publishers,id',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['is_default'] = $request->has('is_default');

        // If this is set as default, unset other defaults for the same language
        if ($data['is_default'] && !$loaTemplate->is_default) {
            LoaTemplate::where('language', $data['language'])
                ->where('id', '!=', $loaTemplate->id)
                ->orWhere('language', 'both')
                ->update(['is_default' => false]);
        }

        $loaTemplate->update($data);

        return redirect()->route('admin.loa-templates.index')
            ->with('success', 'Template LOA berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoaTemplate $loaTemplate)
    {
        if ($loaTemplate->is_default) {
            return redirect()->route('admin.loa-templates.index')
                ->with('error', 'Template default tidak dapat dihapus.');
        }

        $loaTemplate->delete();

        return redirect()->route('admin.loa-templates.index')
            ->with('success', 'Template LOA berhasil dihapus.');
    }

    /**
     * Preview template with sample data.
     */
    public function preview(Request $request, LoaTemplate $loaTemplate)
    {
        $sampleData = $this->getSampleData();
        $rendered = $this->renderTemplate($loaTemplate, $sampleData);
        
        return view('admin.loa-templates.preview', compact('loaTemplate', 'rendered', 'sampleData'));
    }

    /**
     * Get default template structure.
     */
    private function getDefaultTemplate()
    {
        return [
            'header_template' => $this->getDefaultHeader(),
            'body_template' => $this->getDefaultBody(),
            'footer_template' => $this->getDefaultFooter(),
            'css_styles' => $this->getDefaultCSS(),
        ];
    }

    private function getDefaultHeader()
    {
        return '<div class="header text-center mb-4">
    @if({{publisher_logo}})
        <img src="{{publisher_logo}}" alt="Logo" class="publisher-logo mb-3">
    @endif
    <h1 class="publisher-name">{{publisher_name}}</h1>
    <p class="publisher-address">{{publisher_address}}</p>
    <p class="publisher-contact">
        Email: {{publisher_email}} | Phone: {{publisher_phone}}
    </p>
    <h2 class="certificate-title mt-4">
        @if($lang == "id")
            SURAT PERSETUJUAN NASKAH<br>
            <small>(LETTER OF ACCEPTANCE)</small>
        @else
            LETTER OF ACCEPTANCE<br>
            <small>(SURAT PERSETUJUAN NASKAH)</small>
        @endif
    </h2>
</div>';
    }

    private function getDefaultBody()
    {
        return '<div class="loa-content">
    <div class="loa-code text-center mb-4">
        <h3>{{loa_code}}</h3>
    </div>
    
    <div class="introduction mb-4">
        @if($lang == "id")
            <p>Dengan ini kami menyatakan bahwa naskah artikel ilmiah berikut:</p>
        @else
            <p>We hereby declare that the following scientific article manuscript:</p>
        @endif
    </div>
    
    <div class="article-details mb-4">
        <table class="table">
            <tr>
                <td><strong>{{$lang == "id" ? "Judul Artikel" : "Article Title"}}:</strong></td>
                <td>{{article_title}}</td>
            </tr>
            <tr>
                <td><strong>{{$lang == "id" ? "Penulis" : "Author"}}:</strong></td>
                <td>{{author_name}}</td>
            </tr>
            <tr>
                <td><strong>{{$lang == "id" ? "Email" : "Email"}}:</strong></td>
                <td>{{author_email}}</td>
            </tr>
            <tr>
                <td><strong>{{$lang == "id" ? "Jurnal" : "Journal"}}:</strong></td>
                <td>{{journal_name}}</td>
            </tr>
            <tr>
                <td><strong>ISSN:</strong></td>
                <td>E-ISSN: {{journal_issn_e}} | P-ISSN: {{journal_issn_p}}</td>
            </tr>
            <tr>
                <td><strong>{{$lang == "id" ? "Volume/Nomor" : "Volume/Number"}}:</strong></td>
                <td>Vol. {{volume}}, No. {{number}}</td>
            </tr>
            <tr>
                <td><strong>{{$lang == "id" ? "Edisi" : "Issue"}}:</strong></td>
                <td>{{month}} {{year}}</td>
            </tr>
            <tr>
                <td><strong>{{$lang == "id" ? "No. Registrasi" : "Registration No."}}:</strong></td>
                <td>{{registration_number}}</td>
            </tr>
        </table>
    </div>
    
    <div class="acceptance-statement text-center mb-4">
        <div class="alert alert-success">
            @if($lang == "id")
                <h3>TELAH DITERIMA UNTUK DIPUBLIKASIKAN</h3>
                <small>(HAS BEEN ACCEPTED FOR PUBLICATION)</small>
            @else
                <h3>HAS BEEN ACCEPTED FOR PUBLICATION</h3>
                <small>(TELAH DITERIMA UNTUK DIPUBLIKASIKAN)</small>
            @endif
        </div>
    </div>
    
    <div class="description mb-4">
        @if($lang == "id")
            <p>Naskah telah melalui proses review dan memenuhi persyaratan untuk dipublikasikan pada jurnal <strong>{{journal_name}}</strong>.</p>
        @else
            <p>The manuscript has undergone review process and meets the requirements for publication in <strong>{{journal_name}}</strong>.</p>
        @endif
    </div>
</div>';
    }

    private function getDefaultFooter()
    {
        return '<div class="signature-section text-right mb-4">
    <p>{{approval_date}}</p>
    <p><strong>{{$lang == "id" ? "Pemimpin Redaksi" : "Editor-in-Chief"}}</strong></p>
    
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
    <p><strong>{{$lang == "id" ? "Verifikasi Dokumen" : "Document Verification"}}:</strong></p>
    <p>{{$lang == "id" ? "Kunjungi" : "Visit"}}: {{verification_url}}</p>
    <p>{{$lang == "id" ? "Kode LOA" : "LOA Code"}}: {{loa_code}}</p>
    <p><small>{{$lang == "id" ? "Dicetak pada" : "Generated on"}}: {{current_date}}</small></p>
</div>';
    }

    private function getDefaultCSS()
    {
        return '.header { margin-bottom: 30px; }
.publisher-logo { max-height: 80px; }
.publisher-name { color: #2c5aa0; font-size: 24px; margin: 10px 0; }
.certificate-title { color: #2c5aa0; font-size: 20px; font-weight: bold; }
.loa-code { background: #f8f9fa; padding: 15px; border: 2px solid #2c5aa0; }
.article-details table { width: 100%; border-collapse: collapse; }
.article-details td { padding: 8px; border-bottom: 1px solid #ddd; }
.acceptance-statement { margin: 30px 0; }
.signature-section { margin-top: 50px; }
.signature-image { max-height: 100px; }
.verification-info { font-size: 12px; color: #666; }';
    }

    private function getSampleData()
    {
        return [
            'publisher_name' => 'Sample Publisher',
            'publisher_address' => 'Jl. Contoh No. 123, Jakarta',
            'publisher_email' => 'publisher@example.com',
            'publisher_phone' => '+62-21-1234567',
            'journal_name' => 'International Journal of Sample Research',
            'journal_issn_e' => '2345-6789',
            'journal_issn_p' => '1234-5678',
            'chief_editor' => 'Dr. Jane Doe',
            'loa_code' => 'LOA20250802001',
            'article_title' => 'Sample Article Title for Testing Template',
            'author_name' => 'John Smith, Ph.D.',
            'author_email' => 'john.smith@university.edu',
            'volume' => '5',
            'number' => '2',
            'month' => 'August',
            'year' => '2025',
            'registration_number' => 'REG-2025-001',
            'approval_date' => '2 August 2025',
            'current_date' => date('d F Y, H:i:s'),
            'verification_url' => url('/verify-loa'),
        ];
    }

    private function renderTemplate($template, $data)
    {
        $content = $template->header_template . $template->body_template . $template->footer_template;
        
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }
}
