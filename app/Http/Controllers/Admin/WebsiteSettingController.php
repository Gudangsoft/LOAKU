<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WebsiteSettingController extends Controller
{
    /**
     * Display website settings
     */
    public function index()
    {
        $settings = WebsiteSetting::all()->keyBy('key');
        
        return view('admin.website-settings.index', compact('settings'));
    }

    /**
     * Update website settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg|max:1024',
            'admin_email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update text settings
            WebsiteSetting::set('site_name', $request->site_name, 'text', 'Nama Website');
            WebsiteSetting::set('site_description', $request->site_description, 'text', 'Deskripsi Website');
            WebsiteSetting::set('admin_email', $request->admin_email, 'text', 'Email Admin');
            WebsiteSetting::set('phone', $request->phone, 'text', 'Nomor Telepon');
            WebsiteSetting::set('address', $request->address, 'text', 'Alamat');

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                $oldLogo = WebsiteSetting::get('logo');
                if ($oldLogo && Storage::exists($oldLogo)) {
                    Storage::delete($oldLogo);
                }

                $logoPath = $request->file('logo')->store('website', 'public');
                WebsiteSetting::set('logo', $logoPath, 'image', 'Logo Website');
            }

            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                // Delete old favicon if exists
                $oldFavicon = WebsiteSetting::get('favicon');
                if ($oldFavicon && Storage::exists($oldFavicon)) {
                    Storage::delete($oldFavicon);
                }

                $faviconPath = $request->file('favicon')->store('website', 'public');
                WebsiteSetting::set('favicon', $faviconPath, 'image', 'Favicon Website');
            }

            return redirect()->back()->with('success', 'Website settings berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Error updating website settings', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui settings.');
        }
    }

    /**
     * Delete logo or favicon
     */
    public function deleteImage(Request $request)
    {
        $type = $request->type; // 'logo' or 'favicon'
        
        if (!in_array($type, ['logo', 'favicon'])) {
            return response()->json(['success' => false, 'message' => 'Invalid image type']);
        }

        try {
            $setting = WebsiteSetting::where('key', $type)->first();
            
            if ($setting && $setting->value) {
                // Delete file from storage
                if (Storage::exists($setting->value)) {
                    Storage::delete($setting->value);
                }
                
                // Update setting
                $setting->update(['value' => null]);
            }

            return response()->json(['success' => true, 'message' => ucfirst($type) . ' berhasil dihapus']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus ' . $type]);
        }
    }
}
