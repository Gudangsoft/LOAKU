<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JournalController extends Controller
{
    public function index()
    {
        $journals = Journal::with('publisher')->paginate(15);
        return view('admin.journals.index', compact('journals'));
    }

    public function create()
    {
        $publishers = Publisher::all();
        return view('admin.journals.create', compact('publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'e_issn' => 'nullable|string|max:50',
            'p_issn' => 'nullable|string|max:50',
            'chief_editor' => 'required|string|max:255',
            'website' => 'nullable|url',
            'publisher_id' => 'required|exists:publishers,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd_stample' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'name', 'e_issn', 'p_issn', 'chief_editor', 'website', 'publisher_id'
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('journals/logos', 'public');
        }

        if ($request->hasFile('ttd_stample')) {
            $data['ttd_stample'] = $request->file('ttd_stample')->store('journals/stamps', 'public');
        }

        Journal::create($data);

        return redirect()->route('admin.journals.index')
            ->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function show(Journal $journal)
    {
        $journal->load('publisher');
        return view('admin.journals.show', compact('journal'));
    }

    public function edit(Journal $journal)
    {
        $publishers = Publisher::all();
        return view('admin.journals.edit', compact('journal', 'publishers'));
    }

    public function update(Request $request, Journal $journal)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'e_issn' => 'nullable|string|max:50',
            'p_issn' => 'nullable|string|max:50',
            'chief_editor' => 'required|string|max:255',
            'website' => 'nullable|url',
            'publisher_id' => 'required|exists:publishers,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd_stample' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'name', 'e_issn', 'p_issn', 'chief_editor', 'website', 'publisher_id'
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($journal->logo) {
                Storage::disk('public')->delete($journal->logo);
            }
            $data['logo'] = $request->file('logo')->store('journals/logos', 'public');
        }

        if ($request->hasFile('ttd_stample')) {
            // Delete old stamp if exists
            if ($journal->ttd_stample) {
                Storage::disk('public')->delete($journal->ttd_stample);
            }
            $data['ttd_stample'] = $request->file('ttd_stample')->store('journals/stamps', 'public');
        }

        $journal->update($data);

        return redirect()->route('admin.journals.index')
            ->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(Journal $journal)
    {
        // Delete associated files
        if ($journal->logo) {
            Storage::disk('public')->delete($journal->logo);
        }
        if ($journal->ttd_stample) {
            Storage::disk('public')->delete($journal->ttd_stample);
        }

        $journal->delete();

        return redirect()->route('admin.journals.index')
            ->with('success', 'Jurnal berhasil dihapus.');
    }
}
