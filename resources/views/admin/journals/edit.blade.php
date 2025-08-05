@extends('layouts.app')

@section('title', 'Edit Jurnal - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Jurnal
                    </h4>
                    <p class="mb-0 mt-2">Perbarui informasi jurnal: <strong>{{ $journal->name }}</strong></p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.journals.update', $journal) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Nama Jurnal -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-book me-1"></i>
                                Nama Jurnal <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $journal->name) }}" 
                                   placeholder="Contoh: Jurnal Teknologi Informasi dan Komunikasi"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Publisher -->
                        <div class="mb-3">
                            <label for="publisher_id" class="form-label">
                                <i class="fas fa-building me-1"></i>
                                Penerbit/Institusi <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('publisher_id') is-invalid @enderror" id="publisher_id" name="publisher_id" required>
                                <option value="">-- Pilih Penerbit --</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}" {{ old('publisher_id', $journal->publisher_id) == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                                @endforeach
                            </select>
                            @error('publisher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- E- ISSN -->
                        <div class="mb-3">
                            <label for="e_issn" class="form-label">
                                <i class="fas fa-barcode me-1"></i>
                               E-  ISSN
                            </label>
                            <input type="text" class="form-control @error('e_issn') is-invalid @enderror" id="e_issn" name="e_issn" value="{{ old('e_issn', $journal->e_issn) }}" placeholder="Contoh: 1234-5678">
                            @error('e_issn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       <!-- P-ISSN -->
                        <div class="mb-3">
                            <label for="p_issn" class="form-label">
                                <i class="fas fa-barcode me-1"></i>
                               P-ISSN
                            </label>
                            <input type="text" class="form-control @error('p_issn') is-invalid @enderror" id="p_issn" name="p_issn" value="{{ old('p_issn', $journal->p_issn) }}" placeholder="Contoh: 1234-5678">
                            @error('p_issn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div class="mb-3">
                            <label for="website" class="form-label">
                                <i class="fas fa-globe me-1"></i>
                                Website
                            </label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $journal->website) }}" placeholder="https://jurnal.example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


 <!-- Chief Edito-->
                        <div class="mb-3">
                            <label for="chief_editor" class="form-label">
                                <i class="fas fa-user-tie me-1"></i>
                                Editor-in-Chief <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('chief_editor') is-invalid @enderror" id="chief_editor" name="chief_editor" value="{{ old('chief_editor', $journal->chief_editor) }}" placeholder="Eko Siswanto">
                            @error('chief_editor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



 

<div class="row">
                            <!-- Logo -->
                            <div class="col-md-6 mb-3">
                                <label for="logo" class="form-label">
                                    <i class="fas fa-image me-1"></i>
                                    Logo Jurnal
                                </label>
                                <input type="file" 
                                       class="form-control @error('logo') is-invalid @enderror" 
                                       id="logo" 
                                       name="logo" 
                                       accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Format: JPG, PNG. Maksimal 2MB</div>
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- TTD & Stempel -->
                            <div class="col-md-6 mb-3">
                                <label for="ttd_stample" class="form-label">
                                    <i class="fas fa-stamp me-1"></i>
                                    TTD & Stempel
                                </label>
                                <input type="file" 
                                       class="form-control @error('ttd_stample') is-invalid @enderror" 
                                       id="ttd_stample" 
                                       name="ttd_stample" 
                                       accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Format: JPG, PNG. Maksimal 2MB</div>
                                @error('ttd_stample')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>








                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.journals.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
