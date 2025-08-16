<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{
    /**
     * Show validation pending page
     */
    public function pending()
    {
        if (!Auth::check() || Auth::user()->role !== 'publisher') {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $publisher = Publisher::where('user_id', $user->id)->first();

        if (!$publisher) {
            return redirect()->route('home')->with('error', 'Data publisher tidak ditemukan.');
        }

        if ($publisher->isActive()) {
            // Set session to indicate publisher has validated their token
            session(['publisher_validated' => true]);
            return redirect()->route('publisher.dashboard');
        }

        return view('publisher.validation.pending', compact('publisher'));
    }

    /**
     * Show token validation form
     */
    public function tokenForm()
    {
        if (!Auth::check() || Auth::user()->role !== 'publisher') {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $publisher = Publisher::where('user_id', $user->id)->first();

        if (!$publisher) {
            return redirect()->route('home')->with('error', 'Data publisher tidak ditemukan.');
        }

        if ($publisher->isActive()) {
            // Set session to indicate publisher has validated their token
            session(['publisher_validated' => true]);
            return redirect()->route('publisher.dashboard');
        }

        return view('publisher.validation.token', compact('publisher'));
    }

    /**
     * Process token validation
     */
    public function validateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'validation_token' => 'required|string|size:8'
        ], [
            'validation_token.required' => 'Token validasi wajib diisi.',
            'validation_token.size' => 'Token validasi harus 8 karakter.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $publisher = Publisher::where('user_id', $user->id)->first();

        if (!$publisher) {
            return redirect()->route('home')->with('error', 'Data publisher tidak ditemukan.');
        }

        // Check if token matches and publisher is active
        if ($publisher->validation_token === strtoupper($request->validation_token) && $publisher->isActive()) {
            // Set session to indicate publisher has validated their token
            session(['publisher_validated' => true]);
            
            return redirect()->route('publisher.dashboard')
                ->with('success', 'Token valid! Selamat datang di LOA SIPTENAN.');
        }

        if ($publisher->validation_token !== strtoupper($request->validation_token)) {
            return redirect()->back()
                ->with('error', 'Token validasi tidak valid.')
                ->withInput();
        }

        if (!$publisher->isActive()) {
            return redirect()->back()
                ->with('error', 'Akun Anda belum diaktifkan oleh admin.')
                ->withInput();
        }

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan dalam validasi token.')
            ->withInput();
    }

    /**
     * Check if publisher can access dashboard
     */
    public function checkAccess()
    {
        if (!Auth::check() || Auth::user()->role !== 'publisher') {
            return response()->json(['access' => false, 'message' => 'Unauthorized']);
        }

        $user = Auth::user();
        $publisher = Publisher::where('user_id', $user->id)->first();

        if (!$publisher) {
            return response()->json(['access' => false, 'message' => 'Publisher not found']);
        }

        return response()->json([
            'access' => $publisher->isActive(),
            'status' => $publisher->status,
            'token' => $publisher->validation_token,
            'message' => $publisher->isActive() ? 'Access granted' : 'Account pending validation'
        ]);
    }

    public function suspended()
    {
        $publisher = auth()->user()->publisher;
        
        if (!$publisher) {
            return redirect()->route('home')->with('error', 'Publisher data not found.');
        }

        if ($publisher->status !== 'suspended') {
            return redirect()->route('home');
        }

        return view('publisher.validation.suspended', compact('publisher'));
    }
}
