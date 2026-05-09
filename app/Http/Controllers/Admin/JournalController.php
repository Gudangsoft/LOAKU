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

    public function export()
    {
        $journals = Journal::with('publisher')->get();
        $filename = 'journals_' . date('Y-m-d_His') . '.csv';

        return response()->stream(function () use ($journals) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['id', 'nama_jurnal', 'p_issn', 'e_issn', 'chief_editor', 'email', 'website', 'deskripsi', 'sinta_id', 'doi_prefix', 'garuda_id', 'accreditation_level', 'publisher', 'publisher_email', 'created_at']);
            foreach ($journals as $j) {
                fputcsv($out, [
                    $j->id, $j->name, $j->p_issn, $j->e_issn, $j->chief_editor,
                    $j->email, $j->website, $j->description,
                    $j->sinta_id, $j->doi_prefix, $j->garuda_id, $j->accreditation_level,
                    $j->publisher?->name, $j->publisher?->email,
                    $j->created_at?->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function importForm()
    {
        return view('admin.journals.import');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt|max:2048']);

        $path    = $request->file('file')->getRealPath();
        $handle  = fopen($path, 'r');
        $headers = array_map('trim', fgetcsv($handle));

        $successCount = 0;
        $errors       = [];
        $rowNumber    = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $data = array_combine($headers, array_pad($row, count($headers), ''));

            if (empty(trim($data['nama_jurnal'] ?? ''))) {
                $errors[] = "Baris {$rowNumber}: nama_jurnal wajib diisi";
                continue;
            }

            try {
                $publisherId = null;
                if (!empty($data['publisher_email'])) {
                    $pub = Publisher::where('email', trim($data['publisher_email']))->first();
                    $publisherId = $pub?->id;
                }

                $existing = Journal::where('name', trim($data['nama_jurnal']))->first();

                $fields = [
                    'p_issn'             => trim($data['p_issn'] ?? $data['issn'] ?? ''),
                    'e_issn'             => trim($data['e_issn'] ?? ''),
                    'chief_editor'       => trim($data['chief_editor'] ?? ''),
                    'email'              => trim($data['email'] ?? ''),
                    'website'            => trim($data['website'] ?? ''),
                    'description'        => trim($data['deskripsi'] ?? ''),
                    'sinta_id'           => trim($data['sinta_id'] ?? ''),
                    'doi_prefix'         => trim($data['doi_prefix'] ?? ''),
                    'garuda_id'          => trim($data['garuda_id'] ?? ''),
                    'accreditation_level'=> trim($data['accreditation_level'] ?? ''),
                ];

                if ($existing) {
                    $existing->update($fields);
                } else {
                    Journal::create(array_merge($fields, [
                        'name'         => trim($data['nama_jurnal']),
                        'publisher_id' => $publisherId,
                    ]));
                }
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
            }
        }
        fclose($handle);

        $message = "Import berhasil! {$successCount} jurnal diproses.";
        if (!empty($errors)) {
            $preview = implode('; ', array_slice($errors, 0, 3));
            $message .= " Error: {$preview}" . (count($errors) > 3 ? ' dan ' . (count($errors) - 3) . ' lainnya.' : '');
            return redirect()->route('admin.journals.index')->with('warning', $message);
        }

        return redirect()->route('admin.journals.index')->with('success', $message);
    }

    public function downloadTemplate()
    {
        return response()->stream(function () {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['nama_jurnal', 'p_issn', 'e_issn', 'chief_editor', 'email', 'website', 'deskripsi', 'sinta_id', 'doi_prefix', 'garuda_id', 'accreditation_level', 'publisher_email']);
            fputcsv($out, ['Jurnal Teknologi Informasi', '1234-5678', '8765-4321', 'Dr. Ahmad', 'editor@jti.com', 'https://jti.example.com', 'Jurnal teknologi informasi terkini', 'S12345', '10.12345', 'G12345', 'Sinta 2', 'publisher@example.com']);
            fputcsv($out, ['Jurnal Ilmu Komputer', '2345-6789', '9876-5432', 'Dr. Budi', 'editor@jik.com', 'https://jik.example.com', 'Jurnal penelitian ilmu komputer', 'S23456', '10.23456', 'G23456', 'Sinta 3', 'publisher@example.com']);
            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_jurnal.csv"',
        ]);
    }
}
