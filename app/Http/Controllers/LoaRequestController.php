<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\LoaRequest;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoaRequestController extends Controller
{
    public function create()
    {
        // Jika user adalah publisher, hanya tampilkan jurnal miliknya
        if (auth()->user() && auth()->user()->role === 'publisher') {
            $journals = Journal::where('publisher_id', auth()->id())->with('publisher')->get();
        } else {
            $journals = Journal::with('publisher')->get();
        }
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $years = range(date('Y'), date('Y') - 10);
        
        return view('loa.create', compact('journals', 'months', 'years'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article_id' => 'required|string|max:255',
            'volume' => 'required|integer|min:1',
            'number' => 'required|integer|min:1',
            'month' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'article_title' => 'required|string|max:500',
            'author' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'journal_id' => 'required|exists:journals,id',
        ], [
            'required' => 'Field :attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'exists' => 'Jurnal yang dipilih tidak valid.',
            'integer' => 'Field :attribute harus berupa angka.',
            'min' => 'Field :attribute minimal :min.',
            'max' => 'Field :attribute maksimal :max karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek kuota LOA bulanan berdasarkan paket langganan publisher
        $journal = Journal::find($request->journal_id);
        if ($journal && $journal->publisher_id) {
            $publisher = Publisher::find($journal->publisher_id);
            if ($publisher) {
                $sub = $publisher->activeSubscription;
                if ($sub) {
                    $maxLoa = $sub->plan->max_loa_per_month;
                    if ($maxLoa !== null) {
                        $usedThisMonth = LoaRequest::where('journal_id', $request->journal_id)
                            ->whereYear('created_at', now()->year)
                            ->whereMonth('created_at', now()->month)
                            ->count();
                        if ($usedThisMonth >= $maxLoa) {
                            return redirect()->back()
                                ->with('error', "Kuota pengajuan LOA untuk jurnal ini sudah penuh bulan ini. Silakan coba lagi bulan depan.")
                                ->withInput();
                        }
                    }
                }
            }
        }

        $noReg = LoaRequest::generateNoReg($request->article_id);

        LoaRequest::create([
            'no_reg' => $noReg,
            'article_id' => $request->article_id,
            'volume' => $request->volume,
            'number' => $request->number,
            'month' => $request->month,
            'year' => $request->year,
            'article_title' => $request->article_title,
            'author' => $request->author,
            'author_email' => $request->author_email,
            'journal_id' => $request->journal_id,
        ]);

        return redirect()->route('home')
            ->with('success', 'Permintaan LOA berhasil dikirim dengan No. Registrasi: ' . $noReg . '. Tunggu validasi admin.');
    }
}
