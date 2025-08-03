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
use App\Http\Controllers\Admin\LoaTemplateController;
use App\Http\Controllers\Admin\AuthController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Test route for publisher form
Route::get('/test-publisher-form', function() {
    return view('test-publisher-form');
});

// Test route for publisher API
Route::get('/test-publisher-api', [App\Http\Controllers\TestController::class, 'testPublisher']);

// Database check route
Route::get('/db-check', [App\Http\Controllers\DatabaseCheckController::class, 'checkPublishersTable']);

// Publisher test route
Route::get('/publisher-test', [App\Http\Controllers\PublisherTestController::class, 'testPublisherData']);

// Journal test route
Route::get('/journal-test', [App\Http\Controllers\JournalTestController::class, 'testJournalData']);

// Test new LOA PDF format
Route::get('/test-new-loa-pdf', function() {
    try {
        // Create fake data for testing
        $fakeData = [
            'loa' => (object)[
                'loa_code' => '711/Menulis-Vol.1/No.8/2025',
                'qr_code' => null
            ],
            'request' => (object)[
                'article_title' => 'Perancangan Media Informasi Desa Wisata Partungko Naginjang Kabupaten Samosir Melalui Media Website',
                'author' => 'Andraal Celvin, Ruth Deby Sarvalistia, Khairunisa',
                'author_email' => 'author@example.com',
                'volume' => '1',
                'number' => '8',
                'month' => 'Agustus',
                'year' => '2025',
                'no_reg' => 'LOA320580821323411',
                'approved_at' => now()
            ],
            'journal' => (object)[
                'name' => 'Menulis: Jurnal Penelitian Nusantara',
                'e_issn' => '3018-683X',
                'p_issn' => '1234-5678',
                'chief_editor' => 'Mardalius, M.Kom',
                'logo' => null,
                'signature_image' => null
            ],
            'publisher' => (object)[
                'name' => 'PT. PADANG TEKNO CORP',
                'address' => 'Jl. Bandar Purus Nauli, Sumatera Utara.',
                'email' => 'padang.tekno.corp@gmail.com',
                'phone' => '+62 851-5862-9831',
                'logo' => null
            ],
            'lang' => 'id',
            'qrCode' => base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate('https://loa.siptenan.org/verify/711-Menulis-Vol1-No8-2025'))
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.loa-new-format', $fakeData);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('test_new_format_LOA.pdf');
        
    } catch (\Exception $e) {
        return response('<h1>Error</h1><p>' . $e->getMessage() . '</p>');
    }
});

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
Route::get('/qr/{loaCode}', [QrCodeController::class, 'generateLoaQr'])->name('qr.code');
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
    
    // Debug route to check users
    Route::get('/debug-users', function() {
        $users = \App\Models\User::all();
        return response()->json([
            'total_users' => $users->count(),
            'users' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin ?? false,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at
                ];
            })
        ]);
    })->name('debug-users');
    
    // Test login route
    Route::get('/test-auth', function() {
        try {
            // Test login directly
            $user = \App\Models\User::where('email', 'admin@admin.com')->first();
            if (!$user) {
                return 'User not found';
            }
            
            $passwordCheck = \Hash::check('admin', $user->password);
            
            return response()->json([
                'user_found' => !!$user,
                'user_data' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin,
                    'password_hash' => $user->password
                ] : null,
                'password_check' => $passwordCheck,
                'auth_attempt' => \Auth::attempt(['email' => 'admin@admin.com', 'password' => 'admin']),
                'current_user' => \Auth::user() ? \Auth::user()->email : 'not logged in'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    })->name('test-auth');
    
    // Route untuk membuat LOA untuk request yang sudah approved tapi belum ada LOA-nya
    Route::get('/generate-missing-loa', function() {
        try {
            $approvedRequests = \App\Models\LoaRequest::where('status', 'approved')
                ->whereDoesntHave('loaValidated')
                ->with('journal.publisher')
                ->get();
            
            $generated = [];
            foreach ($approvedRequests as $request) {
                $loaCode = \App\Models\LoaValidated::generateLoaCodeWithArticleId($request->article_id);
                
                \App\Models\LoaValidated::create([
                    'loa_request_id' => $request->id,
                    'loa_code' => $loaCode,
                    'verification_url' => route('loa.verify')
                ]);
                
                $generated[] = [
                    'request_id' => $request->id,
                    'article_title' => $request->article_title,
                    'loa_code' => $loaCode
                ];
            }
            
            return response()->json([
                'message' => 'LOA codes generated successfully',
                'count' => count($generated),
                'generated_loa' => $generated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    })->name('generate-missing-loa');
    
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
    
    // LOA Templates Management
    Route::resource('loa-templates', LoaTemplateController::class);
    Route::get('/loa-templates/{loaTemplate}/preview', [LoaTemplateController::class, 'preview'])->name('loa-templates.preview');
});

// Test route for template system
Route::get('/test-template', function() {
    $template = \App\Models\LoaTemplate::first();
    if (!$template) {
        return 'No templates found. Please run the seeder first.';
    }
    
    $sampleData = [
        'title' => 'Test Article Title',
        'author_name' => 'John Doe',
        'author_email' => 'john@example.com',
        'publisher_name' => 'Test Publisher',
        'publication_date' => '2025-01-02',
        'verification_date' => '2025-01-02',
        'verification_code' => 'TEST123',
        'qr_code_url' => route('qr.code', 'TEST123'),
        'current_date' => date('d F Y'),
        'document_type' => 'Letter of Acceptance'
    ];
    
    return $template->renderTemplate($sampleData, 'id');
})->name('test.template');

// Test LOA creation and view
Route::get('/test-loa-create', function() {
    // Create sample publisher if not exists
    $publisher = \App\Models\Publisher::firstOrCreate([
        'name' => 'Test Publisher',
        'email' => 'test@publisher.com'
    ]);
    
    // Create sample journal if not exists
    $journal = \App\Models\Journal::firstOrCreate([
        'name' => 'Test Journal',
        'publisher_id' => $publisher->id,
        'issn' => '1234-5678'
    ]);
    
    // Create sample LOA request
    $loaRequest = \App\Models\LoaRequest::create([
        'author_name' => 'Test Author',
        'author_email' => 'author@test.com',
        'title' => 'Sample Research Article',
        'journal_id' => $journal->id,
        'status' => 'approved'
    ]);
    
    // Create validated LOA
    $loaValidated = \App\Models\LoaValidated::create([
        'loa_request_id' => $loaRequest->id,
        'loa_code' => 'TEST' . time(),
        'verification_url' => url('/verify-loa')
    ]);
    
    return redirect()->route('loa.view', $loaValidated->loa_code);
})->name('test.loa.create');
