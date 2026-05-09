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
use App\Http\Controllers\Admin\LoaTemplateController as AdminLoaTemplateController;
use App\Http\Controllers\LoaTemplateController;
use App\Http\Controllers\Admin\AuthController;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/publishers', [HomeController::class, 'publishers'])->name('publishers.index');
Route::get('/publishers/{slug}', [HomeController::class, 'publisherDetail'])->name('publishers.detail');


// Universal login routes (for both admin and publisher)
Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

// Publisher Registration Routes
Route::get('/publisher/register', [App\Http\Controllers\PublisherRegistrationController::class, 'showRegistrationForm'])->name('publisher.register.form');
Route::post('/publisher/register', [App\Http\Controllers\PublisherRegistrationController::class, 'register'])->middleware('throttle:5,1')->name('publisher.register');
Route::get('/publisher/register/success', [App\Http\Controllers\PublisherRegistrationController::class, 'success'])->name('publisher.register.success');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email Anda berhasil diverifikasi! Selamat datang di LOA SIPTENAN.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');





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


// LOA Print, View & Widget Routes
Route::get('/loa/print/{loaCode}/{lang?}', [LoaController::class, 'print'])->name('loa.print');
Route::get('/loa/view/{loaCode}/{lang?}', [LoaController::class, 'view'])->name('loa.view');
Route::get('/widget/{loaCode}.js', [LoaController::class, 'widget'])->name('loa.widget');


// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
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

    // Admin user registration (requires existing admin session)
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // LOA Requests Management
    Route::get('/loa-requests', [AdminLoaRequestController::class, 'index'])->name('loa-requests.index');
    Route::get('/loa-requests/export', [AdminLoaRequestController::class, 'export'])->name('loa-requests.export');
    Route::post('/loa-requests/bulk-action', [AdminLoaRequestController::class, 'bulkAction'])->name('loa-requests.bulk-action');
    Route::get('/loa-requests/{loaRequest}', [AdminLoaRequestController::class, 'show'])->name('loa-requests.show');
    Route::post('/loa-requests/{loaRequest}/approve', [AdminLoaRequestController::class, 'approve'])->name('loa-requests.approve');
    Route::post('/loa-requests/{loaRequest}/reject', [AdminLoaRequestController::class, 'reject'])->name('loa-requests.reject');
    
    // LOA Validated Management
    Route::get('/loa-validated', function() {
        return redirect()->route('admin.loa-requests.index');
    })->name('loa.validated');
    
    // Journals Management
    Route::resource('journals', JournalController::class);
    Route::get('/journals-export', [JournalController::class, 'export'])->name('journals.export');
    Route::get('/journals-import', [JournalController::class, 'importForm'])->name('journals.import.form');
    Route::post('/journals-import', [JournalController::class, 'import'])->name('journals.import');
    Route::get('/journals-template', [JournalController::class, 'downloadTemplate'])->name('journals.template');
    
    // Publishers Management
    Route::resource('publishers', PublisherController::class);
    Route::patch('/publishers/{publisher}/toggle-status', [PublisherController::class, 'toggleStatus'])->name('publishers.toggle-status');
    Route::get('/publishers-export', [PublisherController::class, 'export'])->name('publishers.export');
    
    // LOA Templates Management
    Route::resource('loa-templates', LoaTemplateController::class);
    Route::get('/loa-templates/{loaTemplate}/preview', [LoaTemplateController::class, 'preview'])->name('loa-templates.preview');
    
    // Support Management
    Route::resource('supports', \App\Http\Controllers\Admin\SupportController::class);
    Route::patch('/supports/{support}/toggle', [\App\Http\Controllers\Admin\SupportController::class, 'toggle'])->name('supports.toggle');
    
    // Backup Management - TEST ROUTE (remove middleware temporarily)
    Route::prefix('backups-test')->name('backups-test.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('index');
        Route::get('/stats', [\App\Http\Controllers\Admin\BackupController::class, 'stats'])->name('stats');
        Route::post('/create-database', [\App\Http\Controllers\Admin\BackupController::class, 'createDatabase'])->name('create-database');
        Route::post('/create-files', [\App\Http\Controllers\Admin\BackupController::class, 'createFiles'])->name('create-files');
        Route::get('/download/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('download');
        Route::delete('/delete/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('delete');
        Route::post('/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('restore');
    });
    
    // Backup Management
    Route::prefix('backups')->name('backups.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('index');
        Route::get('/stats', [\App\Http\Controllers\Admin\BackupController::class, 'stats'])->name('stats');
        Route::post('/create-database', [\App\Http\Controllers\Admin\BackupController::class, 'createDatabase'])->name('create-database');
        Route::post('/create-files', [\App\Http\Controllers\Admin\BackupController::class, 'createFiles'])->name('create-files');
        Route::get('/download/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('download');
        Route::delete('/delete/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('delete');
        Route::post('/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('restore');
    });
    
    // User Management - dengan permission khusus
    Route::middleware(['permission:manage_users'])->group(function() {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::patch('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::post('/users/{user}/toggle-role', [\App\Http\Controllers\Admin\UserController::class, 'toggleRole'])->name('users.toggle-role');
        Route::patch('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/verify-all', [\App\Http\Controllers\Admin\UserController::class, 'verifyAll'])->name('users.verify-all');
        Route::post('/users/{user}/verify-email', [\App\Http\Controllers\Admin\UserController::class, 'verifyEmail'])->name('users.verify-email');
        Route::post('/users/{user}/unverify-email', [\App\Http\Controllers\Admin\UserController::class, 'unverifyEmail'])->name('users.unverify-email');
        Route::post('/users/{user}/resend-verification', [\App\Http\Controllers\Admin\UserController::class, 'resendVerification'])->name('users.resend-verification');
    });
    
    // Subscription Plans Management
    Route::resource('subscription-plans', \App\Http\Controllers\Admin\SubscriptionPlanController::class);

    // Publisher Subscriptions Management
    Route::resource('publisher-subscriptions', \App\Http\Controllers\Admin\PublisherSubscriptionController::class);
    Route::post('/publisher-subscriptions/{publisherSubscription}/cancel', [\App\Http\Controllers\Admin\PublisherSubscriptionController::class, 'cancel'])->name('publisher-subscriptions.cancel');

    // Subscription Payments (konfirmasi pembayaran)
    Route::get('/subscription-payments', [\App\Http\Controllers\Admin\SubscriptionPaymentController::class, 'index'])->name('subscription-payments.index');
    Route::get('/subscription-payments/{subscriptionPayment}', [\App\Http\Controllers\Admin\SubscriptionPaymentController::class, 'show'])->name('subscription-payments.show');
    Route::post('/subscription-payments/{subscriptionPayment}/confirm', [\App\Http\Controllers\Admin\SubscriptionPaymentController::class, 'confirm'])->name('subscription-payments.confirm');
    Route::post('/subscription-payments/{subscriptionPayment}/reject', [\App\Http\Controllers\Admin\SubscriptionPaymentController::class, 'reject'])->name('subscription-payments.reject');
    Route::delete('/subscription-payments/{subscriptionPayment}', [\App\Http\Controllers\Admin\SubscriptionPaymentController::class, 'destroy'])->name('subscription-payments.destroy');

    // Payment Settings (rekening bank)
    Route::get('/payment-settings', [\App\Http\Controllers\Admin\PaymentSettingController::class, 'index'])->name('payment-settings.index');
    Route::post('/payment-settings', [\App\Http\Controllers\Admin\PaymentSettingController::class, 'update'])->name('payment-settings.update');

    // Domain Requests Management
    Route::get('/domain-requests', [\App\Http\Controllers\Admin\DomainRequestController::class, 'index'])->name('domain-requests.index');
    Route::post('/domain-requests/{publisher}/approve', [\App\Http\Controllers\Admin\DomainRequestController::class, 'approve'])->name('domain-requests.approve');
    Route::post('/domain-requests/{publisher}/reject', [\App\Http\Controllers\Admin\DomainRequestController::class, 'reject'])->name('domain-requests.reject');
    Route::delete('/domain-requests/{publisher}/revoke', [\App\Http\Controllers\Admin\DomainRequestController::class, 'revoke'])->name('domain-requests.revoke');

    // Publisher Validation Management
    Route::prefix('publisher-validation')->name('publisher-validation.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PublisherValidationController::class, 'index'])->name('index');
        Route::get('/{publisher}', [\App\Http\Controllers\Admin\PublisherValidationController::class, 'show'])->name('show');
        Route::post('/{publisher}/activate', [\App\Http\Controllers\Admin\PublisherValidationController::class, 'activate'])->name('activate');
        Route::post('/{publisher}/suspend', [\App\Http\Controllers\Admin\PublisherValidationController::class, 'suspend'])->name('suspend');
        Route::post('/{publisher}/regenerate-token', [\App\Http\Controllers\Admin\PublisherValidationController::class, 'regenerateToken'])->name('regenerate-token');
        Route::post('/bulk-activate', [\App\Http\Controllers\Admin\PublisherValidationController::class, 'bulkActivate'])->name('bulk-activate');
        Route::get('/api/statistics', [\App\Http\Controllers\Admin\PublisherValidationController::class, 'statistics'])->name('statistics');
    });

    // Website Settings Management
    Route::prefix('website-settings')->name('website-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\WebsiteSettingController::class, 'index'])->name('index');
        Route::put('/update', [\App\Http\Controllers\Admin\WebsiteSettingController::class, 'update'])->name('update');
        Route::post('/delete-image', [\App\Http\Controllers\Admin\WebsiteSettingController::class, 'deleteImage'])->name('delete-image');
    });

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Audit / Activity Logs
    Route::get('/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');

    // System Logs
    Route::prefix('system-logs')->name('system-logs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SystemLogController::class, 'index'])->name('index');
        Route::delete('/{filename}/clear', [\App\Http\Controllers\Admin\SystemLogController::class, 'clear'])->name('clear');
        Route::get('/{filename}/download', [\App\Http\Controllers\Admin\SystemLogController::class, 'download'])->name('download');
    });
});


// Publisher Dashboard Route
Route::get('/publisher/dashboard', function() {
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $user = Auth::user();
    if ($user->role !== 'publisher') {
        return redirect()->route('home')->with('error', 'Akses ditolak. Halaman ini khusus untuk publisher.');
    }
    
    // Check if publisher is active and set session
    $publisher = App\Models\Publisher::where('user_id', $user->id)->first();
    if ($publisher && $publisher->isActive()) {
        session(['publisher_validated' => true]);
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
    // Publisher Validation Routes (no validation check needed)
    Route::get('/validation/pending', [\App\Http\Controllers\Publisher\ValidationController::class, 'pending'])->name('validation.pending');
    Route::get('/validation/token', [\App\Http\Controllers\Publisher\ValidationController::class, 'tokenForm'])->name('validation.token');
    Route::post('/validation/token', [\App\Http\Controllers\Publisher\ValidationController::class, 'validateToken'])->name('validation.token.submit');
    Route::get('/validation/suspended', [\App\Http\Controllers\Publisher\ValidationController::class, 'suspended'])->name('validation.suspended');
    
    // Protected Publisher Routes (require validation)
    Route::middleware(['publisher.validated'])->group(function () {
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
        Route::get('/publishers', [App\Http\Controllers\PublisherController::class, 'publishers'])->name('publishers.index');
        Route::get('/publishers/create', [App\Http\Controllers\PublisherController::class, 'createPublisher'])->name('publishers.create');
        Route::post('/publishers', [App\Http\Controllers\PublisherController::class, 'storePublisher'])->name('publishers.store');
        Route::get('/publishers/{publisher}', [App\Http\Controllers\PublisherController::class, 'showPublisher'])->name('publishers.show');
        Route::get('/publishers/{publisher}/edit', [App\Http\Controllers\PublisherController::class, 'editPublisher'])->name('publishers.edit');
        Route::put('/publishers/{publisher}', [App\Http\Controllers\PublisherController::class, 'updatePublisher'])->name('publishers.update');
        Route::delete('/publishers/{publisher}', [App\Http\Controllers\PublisherController::class, 'destroyPublisher'])->name('publishers.destroy');
        
        // Journal Management
        Route::get('/journals', [App\Http\Controllers\PublisherController::class, 'journals'])->name('journals.index');
        Route::get('/journals/create', [App\Http\Controllers\PublisherController::class, 'createJournal'])->name('journals.create');
        Route::post('/journals', [App\Http\Controllers\PublisherController::class, 'storeJournal'])->name('journals.store');
        Route::get('/journals/{journal}', [App\Http\Controllers\PublisherController::class, 'showJournal'])->name('journals.show');
        Route::get('/journals/{journal}/edit', [App\Http\Controllers\PublisherController::class, 'editJournal'])->name('journals.edit');
        Route::put('/journals/{journal}', [App\Http\Controllers\PublisherController::class, 'updateJournal'])->name('journals.update');
        Route::delete('/journals/{journal}', [App\Http\Controllers\PublisherController::class, 'destroyJournal'])->name('journals.destroy');
        
        // Journals Export/Import
        Route::get('/journals-export', [App\Http\Controllers\PublisherController::class, 'exportJournals'])->name('journals.export');
        Route::get('/journals-import', [App\Http\Controllers\PublisherController::class, 'importJournalsForm'])->name('journals.import.form');
        Route::post('/journals-import', [App\Http\Controllers\PublisherController::class, 'importJournals'])->name('journals.import');
        Route::get('/journals-template', [App\Http\Controllers\PublisherController::class, 'downloadJournalsTemplate'])->name('journals.template');
        
        // LOA Request Management
        Route::get('/loa-requests', [App\Http\Controllers\PublisherController::class, 'loaRequests'])->name('loa-requests.index');
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
    })->name('loa-templates.index');
    
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
    
        // Subscription & Payment
        Route::get('/subscription', [\App\Http\Controllers\Publisher\SubscriptionController::class, 'index'])->name('subscription.index');
        Route::post('/subscription/select-plan', [\App\Http\Controllers\Publisher\SubscriptionController::class, 'selectPlan'])->name('subscription.select-plan');
        Route::post('/subscription/upload-proof/{payment}', [\App\Http\Controllers\Publisher\SubscriptionController::class, 'uploadProof'])->name('subscription.upload-proof');

        // Domain Management
        Route::get('/domain', [\App\Http\Controllers\Publisher\DomainController::class, 'index'])->name('domain.index');
        Route::post('/domain', [\App\Http\Controllers\Publisher\DomainController::class, 'update'])->name('domain.update');
        Route::post('/domain/cancel', [\App\Http\Controllers\Publisher\DomainController::class, 'cancel'])->name('domain.cancel');

    }); // End of protected publisher routes (publisher.validated middleware)
}); // End of publisher routes group

// Notification routes (publisher & admin)
Route::middleware('auth')->group(function () {
    Route::get('/notifications/fetch', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.fetch');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

// Simple test route for new view without middleware
Route::get('/test-new-view/{id}', function ($id) {
    $loaRequest = \App\Models\LoaRequest::findOrFail($id);
    return view('publisher.loa-requests.show', compact('loaRequest'));
});

// Short publisher portal URL: /{slug} → publisher detail
Route::get('/{slug}', [HomeController::class, 'publisherDetail'])
    ->where('slug', '[a-zA-Z0-9\-]+')
    ->name('publishers.short');

