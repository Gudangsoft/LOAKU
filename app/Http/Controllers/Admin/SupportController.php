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
            
            \Log::info('Starting file upload: ' . $filename);
            \Log::info('File original name: ' . $file->getClientOriginalName());
            \Log::info('File size: ' . $file->getSize());
            \Log::info('File mime type: ' . $file->getMimeType());
            
            // Ensure directory exists
            $supportDir = storage_path('app/public/supports');
            if (!file_exists($supportDir)) {
                mkdir($supportDir, 0755, true);
                \Log::info('Created supports directory');
            }
            
            try {
                // Store file using Laravel Storage
                $path = $file->storeAs('public/supports', $filename);
                \Log::info('Support logo stored at: ' . $path);
                
                // Additional verification - try direct file operation as backup
                $fullPath = storage_path('app/public/supports/' . $filename);
                if (!file_exists($fullPath)) {
                    \Log::warning('File not found after Laravel storage, trying direct move...');
                    $file->move($supportDir, $filename);
                    \Log::info('File moved directly to: ' . $fullPath);
                }
                
                // Final verification
                if (file_exists($fullPath)) {
                    \Log::info('File successfully verified: ' . $filename);
                    $data['logo'] = $filename;
                } else {
                    \Log::error('File upload completely failed: ' . $filename);
                    return redirect()->back()->withInput()->with('error', 'Gagal menyimpan file logo. Silakan coba lagi.');
                }
                
            } catch (\Exception $e) {
                \Log::error('File upload exception: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat upload file: ' . $e->getMessage());
            }
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
                \Log::info('Deleted old logo: ' . $support->logo);
            }
            
            $file = $request->file('logo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            
            \Log::info('Starting file upload for update: ' . $filename);
            \Log::info('File original name: ' . $file->getClientOriginalName());
            \Log::info('File size: ' . $file->getSize());
            
            // Ensure directory exists
            $supportDir = storage_path('app/public/supports');
            if (!file_exists($supportDir)) {
                mkdir($supportDir, 0755, true);
                \Log::info('Created supports directory for update');
            }
            
            try {
                // Store file using Laravel Storage
                $path = $file->storeAs('public/supports', $filename);
                \Log::info('Updated support logo stored at: ' . $path);
                
                // Additional verification - try direct file operation as backup
                $fullPath = storage_path('app/public/supports/' . $filename);
                if (!file_exists($fullPath)) {
                    \Log::warning('File not found after Laravel storage on update, trying direct move...');
                    $file->move($supportDir, $filename);
                    \Log::info('File moved directly on update to: ' . $fullPath);
                }
                
                // Final verification
                if (file_exists($fullPath)) {
                    \Log::info('Update file successfully verified: ' . $filename);
                    $data['logo'] = $filename;
                } else {
                    \Log::error('Update file upload completely failed: ' . $filename);
                    return redirect()->back()->withInput()->with('error', 'Gagal menyimpan file logo. Silakan coba lagi.');
                }
                
            } catch (\Exception $e) {
                \Log::error('Update file upload exception: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat update file: ' . $e->getMessage());
            }
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
