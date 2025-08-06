<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Database Status Checker
Route::get('/db-status', function () {
    try {
        $tables = [
            'users' => 'Tabel pengguna sistem',
            'accounts' => 'Tabel akun admin/publisher',
            'publishers' => 'Tabel penerbit',
            'journals' => 'Tabel jurnal',
            'loa_requests' => 'Tabel permintaan LOA',
            'loa_validated' => 'Tabel LOA tervalidasi',
            'loa_templates' => 'Tabel template LOA'
        ];

        $status = [];
        $allGood = true;

        foreach ($tables as $table => $description) {
            $exists = Schema::hasTable($table);
            $count = $exists ? DB::table($table)->count() : 0;
            
            $status[] = [
                'table' => $table,
                'description' => $description,
                'exists' => $exists,
                'records' => $count,
                'status' => $exists ? 'OK' : 'MISSING'
            ];
            
            if (!$exists) $allGood = false;
        }

        return response()->json([
            'database_connection' => 'OK',
            'overall_status' => $allGood ? 'SEMUA TABEL OK' : 'ADA TABEL YANG HILANG',
            'tables' => $status,
            'suggestions' => [
                'Jika ada tabel yang hilang, jalankan: php artisan migrate',
                'Untuk membuat data awal admin: php artisan db:seed --class=AdminUserSeeder',
                'Untuk setup role sistem: kunjungi /setup-role-system'
            ]
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'database_connection' => 'ERROR',
            'error' => $e->getMessage(),
            'suggestion' => 'Periksa koneksi database di file .env'
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->name('db.status');
