<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoaRequestController;
use App\Http\Controllers\LoaController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoaRequestController as AdminLoaRequestController;
use App\Http\Controllers\Admin\JournalController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\LoaTemplateController as AdminLoaTemplateController;
use App\Http\Controllers\LoaTemplateController;
use App\Http\Controllers\Admin\AuthController;

// Test route untuk debug publisher LOA templates
Route::get('/test-publisher-loa-templates', function() {
    try {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated']);
        }
        
        if ($user->role !== 'publisher') {
            return response()->json(['error' => 'User role is: ' . $user->role . ', not publisher']);
        }
        
        // Test controller instantiation
        $controller = new \App\Http\Controllers\LoaTemplateController();
        
        // Test basic data retrieval
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        $templatesCount = \App\Models\LoaTemplate::count();
        
        return response()->json([
            'success' => true,
            'message' => 'Controller and data access working',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'publisher' => $publisher ? [
                'id' => $publisher->id,
                'name' => $publisher->name
            ] : null,
            'templates_total' => $templatesCount,
            'controller_class' => get_class($controller)
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->middleware(['auth']);

// Test route simple untuk LOA templates
Route::get('/test-simple-loa-templates', function() {
    return response()->json([
        'message' => 'Simple route working',
        'templates_count' => \App\Models\LoaTemplate::count(),
        'publishers_count' => \App\Models\Publisher::count()
    ]);
});

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

        return $pdf->stream('test_new_format_LOA.pdf');

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
Route::get('/verify-loa/{loaCode}', [LoaController::class, 'verifyResult'])->name('loa.verify.result');

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
    
    // Admin Registration Routes
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Test route for admin functionality
    Route::get('/test-routes', function() { 
        return view('test-admin-routes'); 
    })->name('test-routes');

    // Test role system
    Route::get('/test-roles', function() {
        try {
            $data = [
                'roles' => \App\Models\Role::with('users')->get(),
                'permissions' => \App\Models\Permission::all(),
                'users' => \App\Models\User::with('roles')->get(),
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Role system not available',
                'message' => $e->getMessage(),
                'roles' => [],
                'permissions' => [],
                'users' => \App\Models\User::all()
            ]);
        }
    })->name('test-roles');

    // Setup role system endpoint
    Route::post('/setup-roles', function() {
        try {
            // Run the role permission seeder
            \Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder']);
            $output = \Artisan::output();
            
            // Get counts for verification
            $rolesCount = \App\Models\Role::count();
            $permissionsCount = \App\Models\Permission::count();
            $usersCount = \App\Models\User::count();
            
            return response()->json([
                'success' => true,
                'message' => 'Role system has been set up successfully!',
                'setup_info' => [
                    'roles' => \App\Models\Role::pluck('display_name')->toArray(),
                    'roles_count' => $rolesCount,
                    'permissions_count' => $permissionsCount,
                    'users_count' => $usersCount
                ],
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to setup role system',
                'message' => $e->getMessage(),
                'exception' => $e->getTraceAsString()
            ], 500);
        }
    })->name('setup-roles');
    
    // Database fix route
    Route::get('/fix-database', function() {
        try {
            // Check and create role_users table
            if (!Schema::hasTable('role_users')) {
                DB::statement("
                    CREATE TABLE `role_users` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `user_id` bigint(20) unsigned NOT NULL,
                        `role_id` bigint(20) unsigned NOT NULL,
                        `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `expires_at` timestamp NULL DEFAULT NULL,
                        `additional_permissions` json,
                        `is_active` tinyint(1) NOT NULL DEFAULT 1,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `role_users_user_id_role_id_unique` (`user_id`,`role_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
            }
            
            // Check and create roles table
            if (!Schema::hasTable('roles')) {
                DB::statement("
                    CREATE TABLE `roles` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `name` varchar(255) NOT NULL UNIQUE,
                        `display_name` varchar(255) NOT NULL,
                        `description` text,
                        `permissions` json,
                        `is_active` tinyint(1) NOT NULL DEFAULT 1,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Database tables created successfully!',
                'tables' => [
                    'roles' => Schema::hasTable('roles'),
                    'role_users' => Schema::hasTable('role_users'),
                    'users' => Schema::hasTable('users')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    })->name('fix-database');

    // Route for creating admin user (development only)
    Route::get('/create-admin', [AuthController::class, 'showCreateAdminForm'])->name('create-admin.form');
    Route::get('/create-admin-api', [AuthController::class, 'createAdmin'])->name('create-admin');
    
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

// Member Routes (untuk users yang bukan admin)
Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\MemberController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\MemberController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\MemberController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [App\Http\Controllers\MemberController::class, 'updatePassword'])->name('password.update');
    Route::get('/requests', [App\Http\Controllers\MemberController::class, 'requests'])->name('requests');
    Route::get('/requests/{id}', [App\Http\Controllers\MemberController::class, 'getRequest'])->name('requests.show');
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
    
    // User Management - dengan permission khusus
    Route::middleware(['permission:manage_users'])->group(function() {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::patch('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::post('/users/{user}/toggle-role', [\App\Http\Controllers\Admin\UserController::class, 'toggleRole'])->name('users.toggle-role');
        Route::patch('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/{user}/verify-email', [\App\Http\Controllers\Admin\UserController::class, 'verifyEmail'])->name('users.verify-email');
        Route::post('/users/{user}/unverify-email', [\App\Http\Controllers\Admin\UserController::class, 'unverifyEmail'])->name('users.unverify-email');
        Route::post('/users/{user}/resend-verification', [\App\Http\Controllers\Admin\UserController::class, 'resendVerification'])->name('users.resend-verification');
    });
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

// Debug User Management
Route::get('/debug-users', function() {
    try {
        $users = \App\Models\User::all();
        return response()->json([
            'success' => true,
            'total_users' => $users->count(),
            'users' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin ?? false,
                    'role' => $user->role ?? 'member'
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('debug.users');

// Create test user for delete testing
Route::get('/create-test-user', function() {
    try {
        $user = \App\Models\User::create([
            'name' => 'Test User Member',
            'email' => 'test-member@example.com',
            'password' => \Hash::make('password'),
            'is_admin' => false,
            'role' => 'member',
            'email_verified_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test member user created',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_admin' => $user->is_admin
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
})->name('create.test.user');

// Test admin user creation
Route::get('/create-admin-test', function() {
    try {
        $user = \App\Models\User::create([
            'name' => 'Test Admin User',
            'email' => 'test-admin@example.com',
            'password' => \Hash::make('admin123'),
            'is_admin' => true,
            'role' => 'administrator',
            'email_verified_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test admin user created',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_admin' => $user->is_admin
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
})->name('create.admin.test');

// Debug hasRole method
Route::get('/debug-hasrole', function() {
    try {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Not authenticated']);
        }
        
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'has_method' => method_exists($user, 'hasRole'),
            'reflection' => [
                'method_params' => (new ReflectionMethod($user, 'hasRole'))->getNumberOfParameters(),
                'method_required_params' => (new ReflectionMethod($user, 'hasRole'))->getNumberOfRequiredParameters()
            ],
            'test_calls' => [
                'super_admin' => method_exists($user, 'hasRole') ? $user->hasRole('super_admin') : 'method not exists',
                'administrator' => method_exists($user, 'hasRole') ? $user->hasRole('administrator') : 'method not exists'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->middleware('auth')->name('debug.hasrole');

// Publisher Dashboard Route
Route::get('/publisher/dashboard', function() {
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $user = Auth::user();
    if ($user->role !== 'publisher') {
        return redirect()->route('home')->with('error', 'Akses ditolak. Halaman ini khusus untuk publisher.');
    }
    
    // Get user's publishers and journals with safe queries
    $userPublishers = App\Models\Publisher::where('user_id', $user->id)->get();
    $userJournals = App\Models\Journal::where('user_id', $user->id)->get();
    $journalIds = $userJournals->pluck('id');
    
    // Statistics
    $stats = [
        'publishers' => $userPublishers->count(),
        'journals' => $userJournals->count(),
        'loa_requests' => [
            'total' => App\Models\LoaRequest::whereIn('journal_id', $journalIds)->count(),
            'pending' => App\Models\LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'pending')->count(),
            'approved' => App\Models\LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'approved')->count(),
            'rejected' => App\Models\LoaRequest::whereIn('journal_id', $journalIds)->where('status', 'rejected')->count(),
        ],
        'validated' => App\Models\LoaValidated::whereIn('journal_id', $journalIds)->count()
    ];

    // Recent LOA requests
    $recentRequests = App\Models\LoaRequest::whereIn('journal_id', $journalIds)
        ->with(['journal', 'journal.publisher'])
        ->latest()
        ->take(10)
        ->get();
    
    return view('publisher.dashboard', compact('stats', 'recentRequests'));
})->name('publisher.dashboard');

// Publisher Routes (for publisher role)
Route::prefix('publisher')->name('publisher.')->middleware(['auth', 'publisher'])->group(function () {
    // Dashboard - Commented out to use quick test above
    // Route::get('/dashboard', [App\Http\Controllers\PublisherController::class, 'dashboard'])->name('dashboard');
    
    // Simple publisher management routes
    Route::get('/publishers', function() {
        if (!Auth::check() || Auth::user()->role !== 'publisher') {
            abort(403);
        }
        $publishers = App\Models\Publisher::where('user_id', Auth::id())->paginate(10);
        return view('publisher.publishers.index', compact('publishers'));
    })->name('publishers');
    
    Route::get('/journals', function() {
        if (!Auth::check() || Auth::user()->role !== 'publisher') {
            abort(403);
        }
        $journals = App\Models\Journal::where('user_id', Auth::id())->with('publisher')->paginate(10);
        return view('publisher.journals.index', compact('journals'));
    })->name('journals');
    
    Route::get('/loa-requests', function() {
        if (!Auth::check() || Auth::user()->role !== 'publisher') {
            abort(403);
        }
        $journalIds = App\Models\Journal::where('user_id', Auth::id())->pluck('id');
        $loaRequests = App\Models\LoaRequest::whereIn('journal_id', $journalIds)
            ->with(['journal', 'journal.publisher'])
            ->latest()
            ->paginate(15);
        return view('publisher.loa-requests.index', compact('loaRequests'));
    })->name('loa-requests');
    
    Route::get('/profile', function() {
        if (!Auth::check() || Auth::user()->role !== 'publisher') {
            abort(403);
        }
        return view('publisher.profile');
    })->name('profile');
    
    // Profile Management
    Route::get('/profile', [App\Http\Controllers\PublisherController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\PublisherController::class, 'updateProfile'])->name('profile.update');
    
    // Publisher Management
    Route::get('/publishers', [App\Http\Controllers\PublisherController::class, 'publishers'])->name('publishers');
    Route::get('/publishers/create', [App\Http\Controllers\PublisherController::class, 'createPublisher'])->name('publishers.create');
    Route::post('/publishers', [App\Http\Controllers\PublisherController::class, 'storePublisher'])->name('publishers.store');
    
    // Journal Management
    Route::get('/journals', [App\Http\Controllers\PublisherController::class, 'journals'])->name('journals');
    Route::get('/journals/create', [App\Http\Controllers\PublisherController::class, 'createJournal'])->name('journals.create');
    Route::post('/journals', [App\Http\Controllers\PublisherController::class, 'storeJournal'])->name('journals.store');
    
    // LOA Request Management
    Route::get('/loa-requests', [App\Http\Controllers\PublisherController::class, 'loaRequests'])->name('loa-requests');
    Route::get('/loa-requests/{loaRequest}', [App\Http\Controllers\PublisherController::class, 'showLoaRequest'])->name('loa-requests.show');
    Route::post('/loa-requests/{loaRequest}/approve', [App\Http\Controllers\PublisherController::class, 'approveLoaRequest'])->name('loa-requests.approve');
    Route::post('/loa-requests/{loaRequest}/reject', [App\Http\Controllers\PublisherController::class, 'rejectLoaRequest'])->name('loa-requests.reject');
    
    // LOA Template Management - Complete implementation
    Route::get('/loa-templates', function() {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        $templates = \App\Models\LoaTemplate::where(function($query) use ($publisher) {
            if ($publisher) {
                $query->where('publisher_id', $publisher->id)
                      ->orWhereNull('publisher_id');
            } else {
                $query->whereNull('publisher_id');
            }
        })->with('publisher')->orderBy('is_default', 'desc')->orderBy('name')->paginate(10);
        
        return view('publisher.loa-templates.index', compact('templates'));
    })->name('loa-templates');
    
    Route::get('/loa-templates/create', function() {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        if (!$publisher) {
            return redirect()->route('publisher.loa-templates.index')
                ->with('error', 'Anda harus terdaftar sebagai publisher untuk membuat template LOA.');
        }
        
        return view('publisher.loa-templates.create', compact('publisher'));
    })->name('loa-templates.create');
    
    Route::post('/loa-templates', function(\Illuminate\Http\Request $request) {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        if (!$publisher) {
            return redirect()->route('publisher.loa-templates.index')
                ->with('error', 'Anda harus terdaftar sebagai publisher.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'language' => 'required|in:id,en,both',
            'format' => 'required|in:html,pdf',
            'header_template' => 'required|string',
            'body_template' => 'required|string',
            'footer_template' => 'required|string',
            'variables' => 'nullable|string',
            'css_styles' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        \App\Models\LoaTemplate::create([
            'name' => $request->name,
            'description' => $request->description,
            'language' => $request->language,
            'format' => $request->format,
            'header_template' => $request->header_template,
            'body_template' => $request->body_template,
            'footer_template' => $request->footer_template,
            'variables' => $request->variables ? json_decode($request->variables, true) : null,
            'css_styles' => $request->css_styles,
            'is_active' => $request->has('is_active'),
            'is_default' => false,
            'publisher_id' => $publisher->id
        ]);

        return redirect()->route('publisher.loa-templates.index')
            ->with('success', 'Template LOA berhasil dibuat.');
    })->name('loa-templates.store');
    
    Route::get('/loa-templates/{id}', function($id) {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        $template = \App\Models\LoaTemplate::where('id', $id)
            ->where(function($query) use ($publisher) {
                if ($publisher) {
                    $query->where('publisher_id', $publisher->id)
                          ->orWhereNull('publisher_id');
                } else {
                    $query->whereNull('publisher_id');
                }
            })->firstOrFail();
            
        return view('publisher.loa-templates.show', compact('template'));
    })->name('loa-templates.show');
    
    Route::get('/loa-templates/{id}/edit', function($id) {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        $template = \App\Models\LoaTemplate::where('id', $id)
            ->where('publisher_id', $publisher->id)
            ->firstOrFail();
            
        return view('publisher.loa-templates.edit', compact('template', 'publisher'));
    })->name('loa-templates.edit');
    
    Route::put('/loa-templates/{id}', function(\Illuminate\Http\Request $request, $id) {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        $template = \App\Models\LoaTemplate::where('id', $id)
            ->where('publisher_id', $publisher->id)
            ->firstOrFail();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'language' => 'required|in:id,en,both',
            'format' => 'required|in:html,pdf',
            'header_template' => 'required|string',
            'body_template' => 'required|string',
            'footer_template' => 'required|string',
            'variables' => 'nullable|string',
            'css_styles' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $template->update([
            'name' => $request->name,
            'description' => $request->description,
            'language' => $request->language,
            'format' => $request->format,
            'header_template' => $request->header_template,
            'body_template' => $request->body_template,
            'footer_template' => $request->footer_template,
            'variables' => $request->variables ? json_decode($request->variables, true) : null,
            'css_styles' => $request->css_styles,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('publisher.loa-templates.index')
            ->with('success', 'Template LOA berhasil diperbarui.');
    })->name('loa-templates.update');
    
    Route::delete('/loa-templates/{id}', function($id) {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        $template = \App\Models\LoaTemplate::where('id', $id)
            ->where('publisher_id', $publisher->id)
            ->firstOrFail();
            
        $template->delete();

        return redirect()->route('publisher.loa-templates.index')
            ->with('success', 'Template LOA berhasil dihapus.');
    })->name('loa-templates.destroy');
    
    Route::get('/loa-templates/{id}/preview', function($id) {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        $template = \App\Models\LoaTemplate::where('id', $id)
            ->where(function($query) use ($publisher) {
                if ($publisher) {
                    $query->where('publisher_id', $publisher->id)
                          ->orWhereNull('publisher_id');
                } else {
                    $query->whereNull('publisher_id');
                }
            })->firstOrFail();
            
        return view('publisher.loa-templates.preview', compact('template'));
    })->name('loa-templates.preview');
    
    Route::post('/loa-templates/{id}/toggle', function($id) {
        $user = Auth::user();
        $publisher = \App\Models\Publisher::where('user_id', $user->id)->first();
        
        $template = \App\Models\LoaTemplate::where('id', $id)
            ->where('publisher_id', $publisher->id)
            ->firstOrFail();
            
        $template->update([
            'is_active' => !$template->is_active
        ]);

        $status = $template->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Template LOA berhasil {$status}.");
    })->name('loa-templates.toggle');
});
