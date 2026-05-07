<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::withCount('journals');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'pending', 'suspended'])) {
            $query->where('status', $request->status);
        }

        $publishers = $query->orderByRaw("FIELD(status,'pending','active','suspended')")
                            ->orderBy('created_at', 'desc')
                            ->paginate(15)
                            ->withQueryString();

        $stats = [
            'total'     => Publisher::count(),
            'active'    => Publisher::where('status', 'active')->count(),
            'pending'   => Publisher::where('status', 'pending')->count(),
            'suspended' => Publisher::where('status', 'suspended')->count(),
        ];

        return view('admin.publishers.index', compact('publishers', 'stats'));
    }

    public function toggleStatus(Request $request, Publisher $publisher)
    {
        $request->validate([
            'action' => 'required|in:activate,suspend',
            'notes'  => 'nullable|string|max:500',
        ]);

        if ($request->action === 'activate') {
            if (!$publisher->validation_token) {
                $publisher->generateValidationToken();
            }
            $publisher->activate(auth()->id(), $request->notes);
            $message = "Publisher {$publisher->name} berhasil diaktifkan.";
        } else {
            $publisher->suspend(auth()->id(), $request->notes ?? 'Disuspend oleh admin.');
            $message = "Publisher {$publisher->name} berhasil disuspend.";
        }

        return redirect()->back()->with('success', $message);
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:publishers,email',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'name', 'address', 'phone', 'whatsapp', 'email', 'website'
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('publishers/logos', 'public');
        }

        Publisher::create($data);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function show(Publisher $publisher)
    {
        $publisher->load('journals');
        return view('admin.publishers.show', compact('publisher'));
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:publishers,email,' . $publisher->id,
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'name', 'address', 'phone', 'whatsapp', 'email', 'website'
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($publisher->logo) {
                Storage::disk('public')->delete($publisher->logo);
            }
            $data['logo'] = $request->file('logo')->store('publishers/logos', 'public');
        }

        $publisher->update($data);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Penerbit berhasil diperbarui.');
    }

    public function destroy(Publisher $publisher)
    {
        // Check if publisher has journals
        if ($publisher->journals()->count() > 0) {
            return redirect()->route('admin.publishers.index')
                ->with('error', 'Tidak dapat menghapus penerbit yang memiliki jurnal.');
        }

        // Delete associated logo
        if ($publisher->logo) {
            Storage::disk('public')->delete($publisher->logo);
        }

        $publisher->delete();

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Penerbit berhasil dihapus.');
    }

    public function export()
    {
        $publishers = Publisher::withCount('journals')->orderBy('name')->get();

        $filename = 'publishers-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($publishers) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Nama Publisher', 'Status', 'Email', 'Telepon',
                           'WhatsApp', 'Website', 'Alamat', 'Jumlah Jurnal', 'Tanggal Daftar']);
            foreach ($publishers as $p) {
                fputcsv($out, [
                    $p->id,
                    $p->name,
                    $p->status,
                    $p->email,
                    $p->phone,
                    $p->whatsapp,
                    $p->website,
                    $p->address,
                    $p->journals_count,
                    $p->created_at->format('d/m/Y'),
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
