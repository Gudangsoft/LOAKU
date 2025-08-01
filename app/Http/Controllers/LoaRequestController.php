<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\LoaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoaRequestController extends Controller
{
    public function create()
    {
        $journals = Journal::with('publisher')->get();
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
