<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\Publisher;
use App\Exports\JournalsExport;
use App\Imports\JournalsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

    /**
     * Export journals to Excel
     */
    public function export()
    {
        return Excel::download(new JournalsExport(), 'journals_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('admin.journals.import');
    }

    /**
     * Import journals from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new JournalsImport();
            Excel::import($import, $request->file('file'));

            $message = "Import berhasil! {$import->getSuccessCount()} jurnal berhasil diproses.";
            
            if ($import->hasErrors()) {
                $message .= " Namun ada beberapa error: " . implode('; ', array_slice($import->getErrors(), 0, 3));
                if (count($import->getErrors()) > 3) {
                    $message .= " dan " . (count($import->getErrors()) - 3) . " error lainnya.";
                }
                return redirect()->route('admin.journals.index')
                    ->with('warning', $message);
            }

            return redirect()->route('admin.journals.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('admin.journals.index')
                ->with('error', 'Gagal mengimport file: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        $headers = [
            ['nama_jurnal', 'deskripsi', 'issn', 'e_issn', 'website', 'email', 'alamat', 'publisher_email', 'status'],
            ['Jurnal Teknologi Informasi', 'Jurnal yang membahas teknologi informasi terkini', '1234-5678', '8765-4321', 'https://jti.example.com', 'editor@jti.com', 'Jl. Teknologi No. 123', 'publisher@example.com', 'active'],
            ['Jurnal Ilmu Komputer', 'Jurnal penelitian ilmu komputer dan informatika', '2345-6789', '9876-5432', 'https://jik.example.com', 'editor@jik.com', 'Jl. Komputer No. 456', 'publisher@example.com', 'active']
        ];

        $fileName = 'template_import_jurnal.xlsx';
        
        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $data;
            
            public function __construct($data) {
                $this->data = $data;
            }
            
            public function array(): array {
                return $this->data;
            }
        }, $fileName);
    }
}
