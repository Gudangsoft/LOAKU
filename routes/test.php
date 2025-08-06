<?php

use Illuminate\Support\Facades\Route;
use App\Models\Publisher;

Route::get('/test-publisher', function () {
    try {
        // Test database connection and table structure
        $publishers = Publisher::all();
        $columns = Schema::getColumnListing('publishers');
        
        return response()->json([
            'status' => 'success',
            'publishers_count' => $publishers->count(),
            'table_columns' => $columns,
            'test_data' => $publishers->take(3)
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test route untuk mengecek semua fungsi sistem
Route::get('/system-test', function() {
    $results = [];
    
    try {
        // 1. Test Database Connection
        $results['database'] = [
            'status' => 'OK',
            'connection' => DB::connection()->getPDO() ? 'Connected' : 'Failed'
        ];
        
        // 2. Test Models
        $results['models'] = [
            'users_count' => \App\Models\User::count(),
            'publishers_count' => \App\Models\Publisher::count(),
            'journals_count' => \App\Models\Journal::count(),
            'loa_requests_count' => \App\Models\LoaRequest::count(),
            'loa_validated_count' => \App\Models\LoaValidated::count(),
        ];
        
        // 3. Test Role System (if available)
        try {
            $results['role_system'] = [
                'roles_count' => \App\Models\Role::count(),
                'permissions_count' => \App\Models\Permission::count(),
                'role_users_count' => \App\Models\RoleUser::count(),
            ];
        } catch (\Exception $e) {
            $results['role_system'] = [
                'error' => 'Role system not available: ' . $e->getMessage()
            ];
        }
        
        // 4. Test Admin Users
        $adminUsers = \App\Models\User::where('is_admin', true)->get(['name', 'email', 'role']);
        $results['admin_users'] = $adminUsers->map(function($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'admin'
            ];
        });
        
        // 5. Test Storage
        $results['storage'] = [
            'app_writable' => is_writable(storage_path('app')),
            'logs_writable' => is_writable(storage_path('logs')),
            'framework_writable' => is_writable(storage_path('framework'))
        ];
        
        // 6. Test Configuration
        $results['config'] = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'database' => config('database.default'),
            'cache' => config('cache.default'),
            'session' => config('session.driver')
        ];
        
    } catch (\Exception $e) {
        $results['error'] = [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

// Test route for QR Code functionality
Route::get('/test-qr', function() {
    try {
        $qrService = new \App\Services\QrCodeService();
        $qrCode = $qrService->generateQrCode('TEST123', 'This is a test verification');
        
        return response()->json([
            'status' => 'OK',
            'qr_generated' => !empty($qrCode),
            'message' => 'QR Code service is working'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage()
        ]);
    }
});
