<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supports = Support::ordered()->get();
        return view('admin.supports.index', compact('supports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.supports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
        ]);

        $data = $request->except('logo');
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/supports', $filename);
            $data['logo'] = $filename;
        }

        Support::create($data);

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Support $support)
    {
        return view('admin.supports.show', compact('support'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Support $support)
    {
        return view('admin.supports.edit', compact('support'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Support $support)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
        ]);

        $data = $request->except('logo');
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($support->logo && Storage::exists('public/supports/' . $support->logo)) {
                Storage::delete('public/supports/' . $support->logo);
            }
            
            $file = $request->file('logo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/supports', $filename);
            $data['logo'] = $filename;
        }

        $support->update($data);

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Support $support)
    {
        // Delete logo file
        if ($support->logo && Storage::exists('public/supports/' . $support->logo)) {
            Storage::delete('public/supports/' . $support->logo);
        }

        $support->delete();

        return redirect()->route('admin.supports.index')
            ->with('success', 'Support berhasil dihapus.');
    }

    /**
     * Toggle active status
     */
    public function toggle(Support $support)
    {
        $support->update(['is_active' => !$support->is_active]);
        
        $status = $support->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Support {$support->name} berhasil {$status}.");
    }
}
