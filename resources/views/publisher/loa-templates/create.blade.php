@extends('publisher.layout')

@section('title', 'Buat Template LOA')

@push('styles')
<style>
.preset-pill {
    display: inline-flex; align-items: center; gap: 6px;
    border: 2px solid #e9ecef; border-radius: 30px;
    padding: 6px 16px; cursor: pointer; transition: all .2s;
    background: #fff; font-size: .85rem; font-weight: 500;
    white-space: nowrap;
}
.preset-pill:hover { border-color: #0d6efd; color: #0d6efd; background: #f0f4ff; }
.preset-pill.active { border-color: #0d6efd; background: #0d6efd; color: #fff; }
.var-chip {
    display: inline-block; background: #e7f3ff; color: #0856c6;
    border-radius: 4px; padding: 1px 7px; font-size: .72rem;
    font-family: monospace; margin: 2px 2px 2px 0; cursor: pointer;
    transition: background .15s;
}
.var-chip:hover { background: #0d6efd; color: #fff; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-plus me-2 text-primary"></i>Buat Template LOA</h4>
        <p class="text-muted mb-0 small">Buat dari template populer atau mulai dari kosong</p>
    </div>
    <a href="{{ route('publisher.loa-templates.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ===== PRESET SELECTOR ===== --}}
<div class="card mb-4 border-primary border-opacity-25" style="background:#f8f9ff;border-radius:14px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-magic text-primary fs-5"></i>
            <div>
                <strong>Mulai dari Template Populer</strong>
                <span class="text-muted small ms-2">— klik untuk mengisi form otomatis, lalu edit sesuai kebutuhan</span>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2" id="presetPills">
            <span class="preset-pill" data-preset="formal_id" onclick="applyPreset('formal_id')">
                <i class="fas fa-scroll text-primary"></i> Surat Resmi Indonesia
            </span>
            <span class="preset-pill" data-preset="academic_en" onclick="applyPreset('academic_en')">
                <i class="fas fa-graduation-cap text-success"></i> Academic English
            </span>
            <span class="preset-pill" data-preset="bilingual" onclick="applyPreset('bilingual')">
                <i class="fas fa-language" style="color:#6f42c1;"></i> Bilingual ID + EN
            </span>
            <span class="preset-pill" data-preset="letterhead" onclick="applyPreset('letterhead')">
                <i class="fas fa-building text-danger"></i> Kop Surat Institusi
            </span>
            <span class="preset-pill" data-preset="modern_en" onclick="applyPreset('modern_en')">
                <i class="fas fa-star text-info"></i> Modern Minimalist
            </span>
            <span class="preset-pill text-muted" onclick="clearForm()">
                <i class="fas fa-eraser"></i> Kosongkan Form
            </span>
        </div>
        <div id="presetDesc" class="mt-3 small text-muted" style="display:none;">
            <i class="fas fa-info-circle me-1 text-primary"></i>
            <span id="presetDescText"></span>
        </div>
    </div>
</div>

{{-- ===== MAIN FORM ===== --}}
<form action="{{ route('publisher.loa-templates.store') }}" method="POST">
    @csrf
    <div class="row g-4">

        {{-- Left column --}}
        <div class="col-lg-8">

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2 text-muted"></i>Informasi Dasar</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label for="name" class="form-label fw-semibold">Nama Template <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   placeholder="Contoh: Template Formal Jurnal Saya" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Publisher</label>
                            <div class="form-control bg-light text-muted">
                                <i class="fas fa-building me-1"></i>{{ $publisher->name ?? '—' }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Deskripsi</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror"
                                   id="description" name="description" value="{{ old('description') }}"
                                   placeholder="Deskripsi singkat template ini (opsional)">
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="language" class="form-label fw-semibold">Bahasa <span class="text-danger">*</span></label>
                            <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                <option value="">— Pilih Bahasa —</option>
                                <option value="id" {{ old('language') == 'id' ? 'selected' : '' }}>Indonesia</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="both" {{ old('language') == 'both' ? 'selected' : '' }}>Bilingual (ID + EN)</option>
                            </select>
                            @error('language')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="format" class="form-label fw-semibold">Format <span class="text-danger">*</span></label>
                            <select class="form-select @error('format') is-invalid @enderror" id="format" name="format" required>
                                <option value="">— Pilih Format —</option>
                                <option value="html" {{ old('format') == 'html' ? 'selected' : '' }}>HTML</option>
                                <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            </select>
                            @error('format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0"><i class="fas fa-code me-2 text-muted"></i>Konten Template</h6>
                    <small class="text-muted">Tulis dalam HTML · Gunakan variabel <code>{{variable}}</code></small>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="header_template" class="form-label fw-semibold">Header <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea class="form-control font-monospace @error('header_template') is-invalid @enderror"
                                  id="header_template" name="header_template" rows="4"
                                  placeholder="HTML untuk bagian header LOA (kop surat, logo, dsb.)">{{ old('header_template') }}</textarea>
                        @error('header_template')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="body_template" class="form-label fw-semibold">Body Template <span class="text-danger">*</span></label>
                        <textarea class="form-control font-monospace @error('body_template') is-invalid @enderror"
                                  id="body_template" name="body_template" rows="18"
                                  placeholder="HTML untuk isi utama LOA..." required>{{ old('body_template') }}</textarea>
                        @error('body_template')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="footer_template" class="form-label fw-semibold">Footer <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea class="form-control font-monospace @error('footer_template') is-invalid @enderror"
                                  id="footer_template" name="footer_template" rows="4"
                                  placeholder="HTML untuk bagian footer LOA (verifikasi, catatan, dsb.)">{{ old('footer_template') }}</textarea>
                        @error('footer_template')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="css_styles" class="form-label fw-semibold">CSS Styles <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea class="form-control font-monospace @error('css_styles') is-invalid @enderror"
                                  id="css_styles" name="css_styles" rows="6"
                                  placeholder="CSS custom untuk template ini...">{{ old('css_styles') }}</textarea>
                        @error('css_styles')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="col-lg-4">

            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title mb-0"><i class="fas fa-cogs me-2 text-muted"></i>Pengaturan</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Template Aktif</label>
                        <div class="form-text">Template aktif dapat dipakai generate LOA</div>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_default" name="is_default" value="1"
                               {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_default">Set Sebagai Default</label>
                        <div class="form-text">Template default dipakai otomatis untuk bahasa yang dipilih</div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title mb-0"><i class="fas fa-tags me-2 text-muted"></i>Variabel Tersedia</h6></div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Klik variabel untuk menyalin ke clipboard:</p>
                    <div id="varChips">
                        @php
                        $vars = [
                            'article_title'     => 'Judul artikel',
                            'author_name'       => 'Nama penulis',
                            'author_email'      => 'Email penulis',
                            'registration_number'=> 'No. registrasi',
                            'loa_code'          => 'Kode LOA',
                            'journal_name'      => 'Nama jurnal',
                            'journal_issn_p'    => 'P-ISSN',
                            'journal_issn_e'    => 'E-ISSN',
                            'publisher_name'    => 'Nama publisher',
                            'publisher_address' => 'Alamat publisher',
                            'publisher_email'   => 'Email publisher',
                            'chief_editor'      => 'Nama editor kepala',
                            'volume'            => 'Volume',
                            'number'            => 'Nomor',
                            'month'             => 'Bulan terbit',
                            'year'              => 'Tahun terbit',
                            'approval_date'     => 'Tanggal disetujui',
                            'current_date'      => 'Tanggal cetak',
                            'verification_url'  => 'URL verifikasi',
                            'qr_code_url'       => 'URL QR Code',
                        ];
                        @endphp
                        @foreach($vars as $key => $label)
                            <span class="var-chip" title="{{ $label }}" onclick="copyVar('{{{{$key}}}}')">&#123;&#123;{{ $key }}&#125;&#125;</span>
                        @endforeach
                    </div>
                    <div id="varCopied" class="text-success small mt-2" style="display:none;">
                        <i class="fas fa-check me-1"></i>Disalin ke clipboard!
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Template
                        </button>
                        <a href="{{ route('publisher.loa-templates.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// ===== PRESET DATA =====
const PRESETS = {

    formal_id: {
        name: 'Surat Resmi Formal (Indonesia)',
        description: 'Template surat resmi formal dalam Bahasa Indonesia sesuai kaidah surat akademik',
        language: 'id', format: 'html',
        desc_text: 'Surat resmi formal berbahasa Indonesia dengan tabel data artikel, pernyataan penerimaan, dan blok tanda tangan editor.',
        header: '',
        body: `<div style="font-family:'Times New Roman',Georgia,serif;padding:30px 50px;font-size:12pt;line-height:1.8;color:#1a1a1a;max-width:750px;margin:0 auto;">

  <div style="text-align:center;margin-bottom:25px;">
    <h2 style="font-size:14pt;font-weight:bold;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:5px;">SURAT PENERIMAAN NASKAH</h2>
    <h3 style="font-size:12pt;font-weight:normal;font-style:italic;color:#555;margin-top:0;">(Letter of Acceptance)</h3>
    <div style="width:80px;height:3px;background:#1e3c72;margin:10px auto;"></div>
    <p style="font-size:10pt;color:#777;margin:0;">No. Ref: {{loa_code}}</p>
  </div>

  <p style="text-align:justify;margin-bottom:15px;">Yang bertanda tangan di bawah ini, Pemimpin Redaksi Jurnal <strong>{{journal_name}}</strong> (P-ISSN: {{journal_issn_p}} | E-ISSN: {{journal_issn_e}}), yang diterbitkan oleh <strong>{{publisher_name}}</strong>, dengan ini menyatakan bahwa naskah artikel ilmiah berikut:</p>

  <table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:12pt;">
    <tr><td style="width:38%;padding:7px 10px;font-weight:600;border-bottom:1px solid #eee;">Judul Artikel</td><td style="padding:7px 10px;border-left:3px solid #1e3c72;border-bottom:1px solid #eee;"><strong>{{article_title}}</strong></td></tr>
    <tr style="background:#f5f7fa;"><td style="padding:7px 10px;font-weight:600;border-bottom:1px solid #eee;">Nama Penulis</td><td style="padding:7px 10px;border-left:3px solid #1e3c72;border-bottom:1px solid #eee;">{{author_name}}</td></tr>
    <tr><td style="padding:7px 10px;font-weight:600;border-bottom:1px solid #eee;">Email Penulis</td><td style="padding:7px 10px;border-left:3px solid #1e3c72;border-bottom:1px solid #eee;">{{author_email}}</td></tr>
    <tr style="background:#f5f7fa;"><td style="padding:7px 10px;font-weight:600;border-bottom:1px solid #eee;">No. Registrasi</td><td style="padding:7px 10px;border-left:3px solid #1e3c72;border-bottom:1px solid #eee;">{{registration_number}}</td></tr>
    <tr><td style="padding:7px 10px;font-weight:600;border-bottom:1px solid #eee;">Volume / Nomor</td><td style="padding:7px 10px;border-left:3px solid #1e3c72;border-bottom:1px solid #eee;">Vol. {{volume}}, No. {{number}}</td></tr>
    <tr style="background:#f5f7fa;"><td style="padding:7px 10px;font-weight:600;">Periode Terbit</td><td style="padding:7px 10px;border-left:3px solid #1e3c72;">{{month}} {{year}}</td></tr>
  </table>

  <p style="text-align:justify;margin-bottom:15px;"><strong>TELAH DITERIMA DAN AKAN DIPUBLIKASIKAN</strong> setelah memenuhi persyaratan dan melalui proses review oleh tim reviewer yang kompeten di bidangnya.</p>

  <p style="text-align:justify;margin-bottom:20px;">Surat penerimaan ini diterbitkan sebagai bukti resmi bahwa naskah tersebut telah dinyatakan layak untuk dipublikasikan, dan dapat digunakan sebagai pendukung laporan penelitian, pengajuan kenaikan pangkat, atau keperluan akademik lainnya.</p>

  <p style="margin-bottom:5px;">Tanggal Persetujuan: <strong>{{approval_date}}</strong></p>
  <p style="color:#666;font-size:10pt;margin-bottom:30px;">Verifikasi dokumen: {{verification_url}} · Kode: {{loa_code}}</p>

  <div style="text-align:right;">
    <p style="margin-bottom:5px;">Hormat kami,</p>
    <p style="color:#555;margin-bottom:50px;">Pemimpin Redaksi {{journal_name}}</p>
    <p style="font-weight:bold;margin-bottom:2px;border-top:1.5px solid #333;padding-top:5px;display:inline-block;min-width:220px;text-align:center;">{{chief_editor}}</p><br>
    <span style="font-size:10pt;color:#555;">Pemimpin Redaksi</span>
  </div>

</div>`,
        footer: '',
        css: ''
    },

    academic_en: {
        name: 'Academic English Standard',
        description: 'Standard international academic LOA in English, suitable for Scopus/WoS indexed journals',
        language: 'en', format: 'html',
        desc_text: 'International academic style with "Dear Author" salutation, article info block, and formal acceptance statement — suitable for Scopus/WoS indexed journals.',
        header: '',
        body: `<div style="font-family:'Times New Roman',Georgia,serif;padding:30px 50px;font-size:12pt;line-height:1.8;color:#1a1a1a;max-width:750px;margin:0 auto;">

  <div style="text-align:center;margin-bottom:28px;">
    <h2 style="font-size:15pt;font-weight:bold;text-transform:uppercase;letter-spacing:2px;color:#1e3c72;margin-bottom:5px;">Letter of Acceptance</h2>
    <p style="font-size:10pt;color:#888;margin:0;">Reference No.: {{loa_code}}</p>
    <div style="width:60px;height:3px;background:#198754;margin:10px auto;"></div>
  </div>

  <p>Dear <strong>{{author_name}}</strong>,</p>

  <p style="text-align:justify;">On behalf of the Editorial Board of <strong>{{journal_name}}</strong> (E-ISSN: {{journal_issn_e}} | P-ISSN: {{journal_issn_p}}), published by <strong>{{publisher_name}}</strong>, we are pleased to inform you that your manuscript has been accepted for publication:</p>

  <div style="background:#f0f7f0;border-left:4px solid #198754;padding:14px 18px;margin:20px 0;border-radius:0 6px 6px 0;">
    <p style="font-weight:bold;font-style:italic;margin:0;font-size:12.5pt;">"{{article_title}}"</p>
  </div>

  <table style="width:100%;border-collapse:collapse;margin:18px 0;font-size:11.5pt;">
    <tr><td style="width:38%;padding:6px 8px;color:#555;font-weight:600;border-bottom:1px solid #eee;">Author(s)</td><td style="padding:6px 8px;border-bottom:1px solid #eee;">{{author_name}}</td></tr>
    <tr style="background:#f8fafb;"><td style="padding:6px 8px;color:#555;font-weight:600;border-bottom:1px solid #eee;">Email</td><td style="padding:6px 8px;border-bottom:1px solid #eee;">{{author_email}}</td></tr>
    <tr><td style="padding:6px 8px;color:#555;font-weight:600;border-bottom:1px solid #eee;">Registration No.</td><td style="padding:6px 8px;border-bottom:1px solid #eee;">{{registration_number}}</td></tr>
    <tr style="background:#f8fafb;"><td style="padding:6px 8px;color:#555;font-weight:600;border-bottom:1px solid #eee;">Journal</td><td style="padding:6px 8px;border-bottom:1px solid #eee;">{{journal_name}}</td></tr>
    <tr><td style="padding:6px 8px;color:#555;font-weight:600;border-bottom:1px solid #eee;">Volume / Issue</td><td style="padding:6px 8px;border-bottom:1px solid #eee;">Vol. {{volume}}, No. {{number}}</td></tr>
    <tr style="background:#f8fafb;"><td style="padding:6px 8px;color:#555;font-weight:600;">Publication Period</td><td style="padding:6px 8px;">{{month}} {{year}}</td></tr>
  </table>

  <p style="text-align:justify;">Your manuscript has undergone a thorough peer-review process by qualified reviewers in the relevant field. The Editorial Board has evaluated the reviewers' reports and has determined that your manuscript meets the standards for publication.</p>

  <p style="text-align:justify;">This letter serves as official confirmation of acceptance and may be used for academic reporting, funding applications, or institutional requirements.</p>

  <p style="margin-bottom:5px;">Date of Acceptance: <strong>{{approval_date}}</strong></p>
  <p style="font-size:10pt;color:#777;margin-bottom:30px;">Document verification: {{verification_url}} | Code: {{loa_code}}</p>

  <p>Yours sincerely,</p>
  <div style="margin-top:40px;display:inline-block;min-width:220px;text-align:center;">
    <div style="border-top:1.5px solid #333;padding-top:6px;">
      <strong>{{chief_editor}}</strong><br>
      <span style="font-size:10pt;color:#555;">Editor-in-Chief, {{journal_name}}</span>
    </div>
  </div>

</div>`,
        footer: '',
        css: ''
    },

    bilingual: {
        name: 'Template Bilingual Indonesia – English',
        description: 'Template bilingual dua bahasa Indonesia dan Inggris dalam satu dokumen',
        language: 'both', format: 'html',
        desc_text: 'Template dua bahasa (Indonesia dan Inggris) dalam satu dokumen — cocok untuk jurnal bertaraf internasional yang memiliki penulis lokal dan mancanegara.',
        header: '',
        body: `<div style="font-family:'Times New Roman',Georgia,serif;padding:30px 50px;font-size:11.5pt;line-height:1.8;color:#1a1a1a;max-width:750px;margin:0 auto;">

  <div style="text-align:center;margin-bottom:24px;border-bottom:2px solid #1e3c72;padding-bottom:16px;">
    <h2 style="font-size:13.5pt;font-weight:bold;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">SURAT PENERIMAAN NASKAH</h2>
    <h3 style="font-size:12.5pt;font-weight:bold;text-transform:uppercase;letter-spacing:1px;margin-top:0;color:#333;">LETTER OF ACCEPTANCE</h3>
    <p style="font-size:10pt;color:#888;margin:0;">Ref: {{loa_code}}</p>
  </div>

  <table style="width:100%;border-collapse:collapse;margin:16px 0;background:#f8f9fb;border:1px solid #e9ecef;">
    <thead><tr style="background:#1e3c72;color:#fff;"><th style="padding:9px 12px;text-align:left;width:40%;">Keterangan / Detail</th><th style="padding:9px 12px;text-align:left;">Data</th></tr></thead>
    <tbody>
      <tr><td style="padding:8px 12px;font-weight:600;border-bottom:1px solid #e9ecef;">Judul / Title</td><td style="padding:8px 12px;border-bottom:1px solid #e9ecef;"><strong>{{article_title}}</strong></td></tr>
      <tr style="background:#fff;"><td style="padding:8px 12px;font-weight:600;border-bottom:1px solid #e9ecef;">Penulis / Author(s)</td><td style="padding:8px 12px;border-bottom:1px solid #e9ecef;">{{author_name}}</td></tr>
      <tr><td style="padding:8px 12px;font-weight:600;border-bottom:1px solid #e9ecef;">No. Registrasi / Registration No.</td><td style="padding:8px 12px;border-bottom:1px solid #e9ecef;">{{registration_number}}</td></tr>
      <tr style="background:#fff;"><td style="padding:8px 12px;font-weight:600;border-bottom:1px solid #e9ecef;">Jurnal / Journal</td><td style="padding:8px 12px;border-bottom:1px solid #e9ecef;">{{journal_name}}</td></tr>
      <tr><td style="padding:8px 12px;font-weight:600;">Volume / Nomor — Edisi</td><td style="padding:8px 12px;">Vol. {{volume}}, No. {{number}} ({{month}} {{year}})</td></tr>
    </tbody>
  </table>

  <div style="border-left:4px solid #1e3c72;padding:10px 16px;margin:18px 0;background:#f0f4ff;">
    <p style="margin:0 0 8px;"><strong>[Indonesia]</strong> Dengan ini kami menyatakan bahwa naskah artikel di atas telah <strong>DITERIMA</strong> dan akan dipublikasikan dalam Jurnal <strong>{{journal_name}}</strong> yang diterbitkan oleh <strong>{{publisher_name}}</strong>, setelah melalui proses review oleh mitra bestari yang kompeten.</p>
    <p style="margin:0;"><strong>[English]</strong> We hereby confirm that the above manuscript has been <strong>ACCEPTED</strong> for publication in <strong>{{journal_name}}</strong>, published by <strong>{{publisher_name}}</strong>, following a thorough peer-review process.</p>
  </div>

  <p style="font-size:10.5pt;color:#666;text-align:justify;">Surat ini dapat digunakan sebagai bukti resmi penerimaan naskah. / This letter may be used as official proof of manuscript acceptance.</p>

  <div style="margin-top:25px;">
    <p style="margin-bottom:3px;"><strong>Tanggal / Date:</strong> {{approval_date}}</p>
    <p style="font-size:10pt;color:#888;margin-bottom:30px;">Verifikasi / Verify: {{verification_url}}</p>
    <div style="text-align:right;">
      <div style="display:inline-block;min-width:220px;text-align:center;border-top:1.5px solid #333;padding-top:6px;">
        <strong>{{chief_editor}}</strong><br>
        <span style="font-size:10pt;color:#555;">Pemimpin Redaksi / Editor-in-Chief</span>
      </div>
    </div>
  </div>

</div>`,
        footer: '',
        css: ''
    },

    letterhead: {
        name: 'Kop Surat Institusi (Formal)',
        description: 'Template bergaya kop surat institusi dengan header jurnal di bagian atas',
        language: 'id', format: 'html',
        desc_text: 'Bergaya surat resmi dengan kop surat berisi nama dan info jurnal, garis pemisah tebal, dan format surat formal "Kepada Yth." — paling umum dipakai di instansi pendidikan Indonesia.',
        header: `<div style="font-family:Arial,Helvetica,sans-serif;padding:20px 50px 0;max-width:750px;margin:0 auto;">
  <table style="width:100%;border-collapse:collapse;">
    <tr>
      <td style="padding:0 15px 0 0;width:80px;text-align:center;">
        <div style="width:70px;height:70px;border-radius:50%;background:#1e3c72;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22pt;font-weight:bold;">J</div>
      </td>
      <td>
        <div style="font-size:14pt;font-weight:bold;color:#1e3c72;">{{journal_name}}</div>
        <div style="font-size:9pt;color:#555;">Diterbitkan oleh: {{publisher_name}}</div>
        <div style="font-size:9pt;color:#555;">P-ISSN: {{journal_issn_p}} | E-ISSN: {{journal_issn_e}}</div>
      </td>
    </tr>
  </table>
  <div style="border-top:4px solid #1e3c72;border-bottom:1px solid #aab;margin:12px 0;"></div>
</div>`,
        body: `<div style="font-family:Arial,Helvetica,sans-serif;padding:10px 50px 30px;font-size:11pt;line-height:1.8;color:#1a1a1a;max-width:750px;margin:0 auto;">

  <p style="margin-bottom:4px;"><strong>Kepada Yth.</strong></p>
  <p style="margin-bottom:4px;">{{author_name}}</p>
  <p style="margin-bottom:20px;">{{author_email}}</p>

  <table style="width:100%;margin-bottom:16px;font-size:11pt;">
    <tr><td style="width:35%;padding:2px;">Nomor</td><td style="padding:2px;">: {{loa_code}}</td></tr>
    <tr><td style="padding:2px;">Tanggal</td><td style="padding:2px;">: {{approval_date}}</td></tr>
    <tr><td style="padding:2px;">Perihal</td><td style="padding:2px;">: <strong>Penerimaan Naskah Artikel Ilmiah</strong></td></tr>
  </table>

  <p>Dengan hormat,</p>

  <p style="text-align:justify;">Dengan ini kami memberitahukan bahwa naskah Anda yang berjudul:</p>

  <div style="background:#eef2f7;border-left:4px solid #1e3c72;padding:12px 16px;font-weight:bold;font-style:italic;margin:15px 0;border-radius:0 6px 6px 0;">
    "{{article_title}}"
  </div>

  <p style="text-align:justify;">Telah <strong>DITERIMA (ACCEPTED)</strong> untuk dipublikasikan pada:</p>

  <table style="width:100%;border-collapse:collapse;margin:12px 0;font-size:11pt;">
    <tr><td style="padding:5px 8px;width:35%;border-bottom:1px solid #ddd;">Jurnal</td><td style="padding:5px 8px;border-bottom:1px solid #ddd;"><strong>{{journal_name}}</strong></td></tr>
    <tr style="background:#f9f9f9;"><td style="padding:5px 8px;border-bottom:1px solid #ddd;">Volume / Nomor</td><td style="padding:5px 8px;border-bottom:1px solid #ddd;">Vol. {{volume}}, No. {{number}}</td></tr>
    <tr><td style="padding:5px 8px;border-bottom:1px solid #ddd;">Periode Terbit</td><td style="padding:5px 8px;border-bottom:1px solid #ddd;">{{month}} {{year}}</td></tr>
    <tr style="background:#f9f9f9;"><td style="padding:5px 8px;">No. Registrasi</td><td style="padding:5px 8px;">{{registration_number}}</td></tr>
  </table>

  <p style="text-align:justify;">Keputusan ini diambil berdasarkan hasil review oleh Mitra Bestari yang berkompeten di bidangnya dan telah memenuhi persyaratan yang ditetapkan oleh dewan redaksi.</p>

  <p style="text-align:justify;">Surat ini dapat digunakan sebagai bukti resmi penerimaan naskah untuk keperluan akademik, pelaporan penelitian, maupun pengajuan kenaikan jabatan fungsional.</p>

  <p>Demikian surat ini kami sampaikan. Atas perhatian dan kepercayaan Bapak/Ibu, kami ucapkan terima kasih.</p>

  <div style="margin-top:30px;text-align:right;">
    <p style="margin-bottom:60px;">Hormat kami,<br><em>Pemimpin Redaksi</em></p>
    <div style="display:inline-block;min-width:220px;text-align:center;border-top:1.5px solid #333;padding-top:6px;">
      <strong>{{chief_editor}}</strong><br>
      <span style="font-size:10pt;color:#555;">Pemimpin Redaksi</span>
    </div>
  </div>

  <p style="font-size:9pt;color:#888;margin-top:20px;">Dokumen ini dapat diverifikasi di: {{verification_url}} dengan kode <strong>{{loa_code}}</strong></p>

</div>`,
        footer: '',
        css: ''
    },

    modern_en: {
        name: 'Modern Minimalist (English)',
        description: 'Clean contemporary design for internationally oriented journals',
        language: 'en', format: 'html',
        desc_text: 'Modern and clean design with pill badge title, highlighted quote block, and elegant right-aligned signature — ideal for contemporary international journals.',
        header: '',
        body: `<div style="font-family:'Helvetica Neue',Arial,sans-serif;max-width:700px;margin:0 auto;padding:50px;font-size:11pt;line-height:1.9;color:#2d3748;">

  <div style="text-align:center;margin-bottom:40px;">
    <span style="display:inline-block;background:#1e3c72;color:#fff;padding:10px 30px;border-radius:30px;font-weight:700;font-size:10pt;letter-spacing:2px;text-transform:uppercase;">
      Letter of Acceptance
    </span>
    <p style="font-size:10pt;color:#a0aec0;margin-top:10px;margin-bottom:0;">{{loa_code}}</p>
  </div>

  <p>Dear <strong>{{author_name}}</strong>,</p>

  <p>We are delighted to inform you that your submission to <strong>{{journal_name}}</strong> has been accepted for publication following a rigorous peer-review process.</p>

  <div style="background:#f7fafc;border-radius:10px;padding:20px 25px;margin:24px 0;border:1px solid #e2e8f0;">
    <div style="font-size:10pt;text-transform:uppercase;letter-spacing:1px;color:#718096;margin-bottom:8px;font-weight:700;">Accepted Manuscript</div>
    <p style="font-size:12.5pt;font-style:italic;font-weight:600;margin:0;color:#2d3748;">"{{article_title}}"</p>
  </div>

  <table style="width:100%;border-collapse:collapse;margin:18px 0;font-size:10.5pt;">
    <tr><td style="padding:7px 0;color:#718096;width:40%;border-bottom:1px solid #edf2f7;">Author(s)</td><td style="padding:7px 0;border-bottom:1px solid #edf2f7;">{{author_name}}</td></tr>
    <tr><td style="padding:7px 0;color:#718096;border-bottom:1px solid #edf2f7;">Email</td><td style="padding:7px 0;border-bottom:1px solid #edf2f7;">{{author_email}}</td></tr>
    <tr><td style="padding:7px 0;color:#718096;border-bottom:1px solid #edf2f7;">Registration No.</td><td style="padding:7px 0;border-bottom:1px solid #edf2f7;">{{registration_number}}</td></tr>
    <tr><td style="padding:7px 0;color:#718096;border-bottom:1px solid #edf2f7;">Issue</td><td style="padding:7px 0;border-bottom:1px solid #edf2f7;">Vol. {{volume}}, No. {{number}} · {{month}} {{year}}</td></tr>
    <tr><td style="padding:7px 0;color:#718096;">Date of Acceptance</td><td style="padding:7px 0;"><strong>{{approval_date}}</strong></td></tr>
  </table>

  <p style="text-align:justify;">This acceptance is subject to final copy-editing and layout. The editorial team will contact you regarding the publication schedule and any further requirements.</p>

  <p style="font-size:9.5pt;color:#a0aec0;margin-top:24px;">This document can be verified at <strong>{{verification_url}}</strong> using code <strong>{{loa_code}}</strong>.</p>

  <div style="border-top:1px solid #e2e8f0;margin-top:32px;padding-top:20px;display:flex;justify-content:space-between;align-items:flex-end;">
    <div>
      <p style="margin:0;font-weight:700;">{{chief_editor}}</p>
      <p style="margin:0;font-size:9.5pt;color:#718096;">Editor-in-Chief</p>
      <p style="margin:0;font-size:9.5pt;color:#718096;">{{journal_name}}</p>
    </div>
    <div style="text-align:right;">
      <p style="margin:0;font-size:9pt;color:#a0aec0;">Published by</p>
      <p style="margin:0;font-size:10pt;font-weight:600;color:#4a5568;">{{publisher_name}}</p>
    </div>
  </div>

</div>`,
        footer: '',
        css: ''
    }
};

function applyPreset(key) {
    const p = PRESETS[key];
    if (!p) return;

    // Fill form fields
    document.getElementById('name').value         = p.name;
    document.getElementById('description').value  = p.description;
    document.getElementById('language').value      = p.language;
    document.getElementById('format').value        = p.format;
    document.getElementById('header_template').value = p.header;
    document.getElementById('body_template').value   = p.body;
    document.getElementById('footer_template').value = p.footer;
    document.getElementById('css_styles').value      = p.css;

    // Highlight active pill
    document.querySelectorAll('.preset-pill').forEach(el => el.classList.remove('active'));
    document.querySelector(`[data-preset="${key}"]`)?.classList.add('active');

    // Show description
    document.getElementById('presetDescText').textContent = p.desc_text;
    document.getElementById('presetDesc').style.display = 'block';

    // Scroll to form
    document.getElementById('name').scrollIntoView({behavior:'smooth', block:'center'});
    document.getElementById('name').focus();

    Swal.fire({
        icon: 'success',
        title: 'Template diterapkan!',
        text: 'Form sudah diisi dengan template ' + p.name + '. Silakan edit nama dan konten sesuai kebutuhan.',
        timer: 2800, showConfirmButton: false, toast: true, position: 'top-end'
    });
}

function clearForm() {
    ['name','description','header_template','body_template','footer_template','css_styles'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('language').value = '';
    document.getElementById('format').value = '';
    document.querySelectorAll('.preset-pill').forEach(el => el.classList.remove('active'));
    document.getElementById('presetDesc').style.display = 'none';
}

function copyVar(varStr) {
    navigator.clipboard?.writeText(varStr).then(() => {
        const el = document.getElementById('varCopied');
        el.style.display = 'block';
        setTimeout(() => el.style.display = 'none', 2000);
    });
}

// Auto-apply preset from URL param
document.addEventListener('DOMContentLoaded', function() {
    const preset = new URLSearchParams(window.location.search).get('preset');
    if (preset && PRESETS[preset]) applyPreset(preset);
});
</script>
@endsection
