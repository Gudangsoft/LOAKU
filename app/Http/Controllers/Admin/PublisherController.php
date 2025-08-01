<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::withCount('journals')->paginate(15);
        return view('admin.publishers.index', compact('publishers'));
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
            'email' => 'required|email|max:255|unique:publishers',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'name', 'address', 'phone', 'whatsapp', 'email'
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'name', 'address', 'phone', 'whatsapp', 'email'
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
}
