<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaRequest;
use App\Models\LoaValidated;
use App\Models\Journal;
use App\Models\Publisher;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Statistik umum - pakai try catch untuk setiap query
            $totalRequests = LoaRequest::count();
            $pendingRequests = LoaRequest::where('status', 'pending')->count();
            $approvedRequests = LoaRequest::where('status', 'approved')->count();
            $rejectedRequests = LoaRequest::where('status', 'rejected')->count();
            
            // Aman check jika tabel ada
            $validatedLoas = 0;
            try {
                $validatedLoas = LoaValidated::count();
            } catch (\Exception $e) {
                $validatedLoas = 0;
            }
            
            $totalJournals = Journal::count();
            $totalPublishers = Publisher::count();

            // Statistik bulanan (sederhana)
            $monthlyStats = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthlyStats[] = [
                    'month' => $date->format('M Y'),
                    'requests' => LoaRequest::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                    'approved' => LoaRequest::where('status', 'approved')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                ];
            }

            // Permohonan terbaru dengan relasi
            $recentRequests = LoaRequest::with(['journal', 'loaValidated'])
                ->latest()
                ->take(10)
                ->get();

            // Jurnal dengan permohonan terbanyak (tanpa withCount dulu)
            $topJournals = Journal::take(5)->get();

            // Statistik per status
            $statusStats = [
                'pending' => [
                    'count' => $pendingRequests,
                    'percentage' => $totalRequests > 0 ? round(($pendingRequests / $totalRequests) * 100, 1) : 0,
                    'color' => 'warning'
                ],
                'approved' => [
                    'count' => $approvedRequests,
                    'percentage' => $totalRequests > 0 ? round(($approvedRequests / $totalRequests) * 100, 1) : 0,
                    'color' => 'success'
                ],
                'rejected' => [
                    'count' => $rejectedRequests,
                    'percentage' => $totalRequests > 0 ? round(($rejectedRequests / $totalRequests) * 100, 1) : 0,
                    'color' => 'danger'
                ]
            ];

            // Statistik hari ini
            $todayStats = [
                'requests' => LoaRequest::whereDate('created_at', Carbon::today())->count(),
                'approved' => LoaRequest::where('status', 'approved')
                    ->whereDate('updated_at', Carbon::today())->count(),
            ];

            return view('admin.dashboard', compact(
                'totalRequests',
                'pendingRequests',
                'approvedRequests',
                'rejectedRequests',
                'validatedLoas',
                'totalJournals',
                'totalPublishers',
                'monthlyStats',
                'recentRequests',
                'topJournals',
                'statusStats',
                'todayStats'
            ));
            
        } catch (\Exception $e) {
            // Fallback dengan data kosong jika ada error
            return view('admin.dashboard', [
                'totalRequests' => 0,
                'pendingRequests' => 0,
                'approvedRequests' => 0,
                'rejectedRequests' => 0,
                'validatedLoas' => 0,
                'totalJournals' => 0,
                'totalPublishers' => 0,
                'monthlyStats' => [],
                'recentRequests' => collect(),
                'topJournals' => collect(),
                'statusStats' => [
                    'pending' => ['count' => 0, 'percentage' => 0, 'color' => 'warning'],
                    'approved' => ['count' => 0, 'percentage' => 0, 'color' => 'success'],
                    'rejected' => ['count' => 0, 'percentage' => 0, 'color' => 'danger']
                ],
                'todayStats' => ['requests' => 0, 'approved' => 0]
            ]);
        }
    }
}
