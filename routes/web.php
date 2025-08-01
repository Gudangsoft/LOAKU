<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoaRequestController;
use App\Http\Controllers\LoaController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoaRequestController as AdminLoaRequestController;
use App\Http\Controllers\Admin\JournalController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\AuthController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// LOA Request Routes
Route::get('/request-loa', [LoaRequestController::class, 'create'])->name('loa.create');
Route::post('/request-loa', [LoaRequestController::class, 'store'])->name('loa.store');

// LOA Search & Verification Routes (Legacy - redirects to validated-loas)
Route::get('/search-loa', function() {
    return redirect()->route('loa.validated')->with('info', 'Pencarian LOA telah dipindahkan ke halaman ini. Gunakan form pencarian di atas.');
})->name('loa.search');
Route::post('/search-loa', [LoaController::class, 'find'])->name('loa.find');
Route::get('/verify-loa', [LoaController::class, 'verify'])->name('loa.verify');
Route::post('/verify-loa', [LoaController::class, 'checkVerification'])->name('loa.check');

// QR Code Routes
Route::get('/qr-scanner', [QrCodeController::class, 'showScanner'])->name('qr.scanner');
Route::get('/verify-qr/{loaCode}', [QrCodeController::class, 'verifyFromQr'])->name('qr.verify');
Route::get('/loa/{loaCode}/qr', [QrCodeController::class, 'generateLoaQr'])->name('loa.qr');
Route::get('/loa/{loaCode}/qr/download', [QrCodeController::class, 'downloadQr'])->name('loa.qr.download');
Route::get('/qr/download/{loaCode}', [QrCodeController::class, 'downloadQr'])->name('qr.download');
Route::get('/api/qr/download/{loaCode}', [QrCodeController::class, 'downloadQrApi'])->name('api.qr.download');

// Test QR Route
Route::get('/test-qr/{code}', function($code) {
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="300" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
    <rect width="300" height="300" fill="white"/>
    <rect x="20" y="20" width="60" height="60" fill="black"/>
    <rect x="30" y="30" width="40" height="40" fill="white"/>
    <rect x="40" y="40" width="20" height="20" fill="black"/>
    <rect x="220" y="20" width="60" height="60" fill="black"/>
    <rect x="230" y="30" width="40" height="40" fill="white"/>
    <rect x="240" y="40" width="20" height="20" fill="black"/>
    <rect x="20" y="220" width="60" height="60" fill="black"/>
    <rect x="30" y="230" width="40" height="40" fill="white"/>
    <rect x="40" y="240" width="20" height="20" fill="black"/>
    <rect x="120" y="120" width="60" height="60" fill="white" stroke="black" stroke-width="2" rx="8"/>
    <circle cx="150" cy="150" r="20" fill="#007bff"/>
    <text x="150" y="155" text-anchor="middle" font-family="Arial" font-size="10" fill="white" font-weight="bold">TEST</text>
    <text x="150" y="280" text-anchor="middle" font-family="Arial" font-size="12" fill="gray">' . $code . '</text>
</svg>';
    return response($svg)->header("Content-Type", "image/svg+xml");
});

// LOA Validated List
Route::get('/validated-loas', [LoaController::class, 'validatedLoas'])->name('loa.validated');

// Test pages
Route::get('/test-verify', function() { return view('loa.test-verify'); })->name('loa.test-verify');

// Debug route for verify-loa
Route::get('/debug-verify', function() {
    try {
        return view('loa.verify');
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('debug.verify');

// LOA Print & View Routes
Route::get('/loa/print/{loaCode}/{lang?}', [LoaController::class, 'print'])->name('loa.print');
Route::get('/loa/view/{loaCode}/{lang?}', [LoaController::class, 'view'])->name('loa.view');

// Test PDF Route
Route::get('/test-pdf', function() {
    try {
        $data = [
            'title' => 'Test PDF',
            'content' => 'This is a test PDF generation'
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('test-pdf', $data);
        return $pdf->download('test.pdf');
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Debug LOA Route
Route::get('/debug-loa/{loaCode}', function($loaCode) {
    try {
        $loa = \App\Models\LoaValidated::where('loa_code', $loaCode)->with(['loaRequest.journal.publisher'])->first();
        
        if (!$loa) {
            return response()->json(['error' => 'LOA not found', 'code' => $loaCode]);
        }
        
        return response()->json([
            'loa' => $loa,
            'request' => $loa->loaRequest,
            'journal' => $loa->loaRequest ? $loa->loaRequest->journal : null,
            'publisher' => $loa->loaRequest && $loa->loaRequest->journal ? $loa->loaRequest->journal->publisher : null
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Simple PDF Test Route
Route::get('/test-loa-pdf', function() {
    try {
        // Create fake data for testing
        $fakeData = [
            'loa' => (object)['loa_code' => 'LOA20250801030918'],
            'request' => (object)[
                'article_title' => 'Test Article: Advanced Studies in Testing',
                'author' => 'John Doe, Jane Smith',
                'author_email' => 'john.doe@example.com',
                'volume' => '10',
                'number' => '2',
                'month' => 'August',
                'year' => '2025',
                'no_reg' => 'REG001',
                'approved_at' => (object)['format' => function($format) { return date($format); }]
            ],
            'journal' => (object)[
                'name' => 'Test Journal of Science',
                'e_issn' => '2345-6789',
                'p_issn' => '1234-5678',
                'chief_editor' => 'Dr. Test Editor'
            ],
            'publisher' => (object)[
                'name' => 'Test Scientific Publisher',
                'address' => 'Jl. Ilmu Pengetahuan No. 123, Jakarta',
                'email' => 'info@testpublisher.com',
                'phone' => '+62-21-12345678'
            ],
            'lang' => 'id'
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.loa-certificate', $fakeData);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('test_LOA20250801030918_id.pdf');
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/test-login', function() { return view('admin.auth.test-login'); })->name('test-login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Route for creating admin user (development only)
    Route::get('/create-admin', [AuthController::class, 'createAdmin'])->name('create-admin');
    
    // Public admin dashboard (accessible without login for testing)
    Route::get('/', [DashboardController::class, 'index']);
});

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // LOA Requests Management
    Route::get('/loa-requests', [AdminLoaRequestController::class, 'index'])->name('loa-requests.index');
    Route::get('/loa-requests/{loaRequest}', [AdminLoaRequestController::class, 'show'])->name('loa-requests.show');
    Route::post('/loa-requests/{loaRequest}/approve', [AdminLoaRequestController::class, 'approve'])->name('loa-requests.approve');
    Route::post('/loa-requests/{loaRequest}/reject', [AdminLoaRequestController::class, 'reject'])->name('loa-requests.reject');
    
    // LOA Validated Management
    Route::get('/loa-validated', function() {
        return redirect()->route('admin.loa-requests.index');
    })->name('loa.validated');
    
    // Journals Management
    Route::resource('journals', JournalController::class);
    
    // Publishers Management
    Route::resource('publishers', PublisherController::class);
});
