<?php

namespace App\Http\Controllers;

use App\Models\LoaTemplate;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoaTemplateController extends Controller
{
    public function __construct()
    {
        // Middleware akan ditangani oleh routes, tidak perlu di constructor
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get publisher for current user
        $publisher = Publisher::where('user_id', $user->id)->first();
        
        if (!$publisher) {
            return redirect()->route('publisher.dashboard')
                ->with('error', 'Anda harus terdaftar sebagai publisher untuk mengakses template LOA.');
        }
        
        // Get LOA templates for the current publisher
        $templates = LoaTemplate::with(['publisher'])
            ->where(function($query) use ($publisher) {
                $query->where('publisher_id', $publisher->id)
                      ->orWhereNull('publisher_id'); // Include global templates
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('publisher.loa-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get publisher for current user
        $publisher = Publisher::where('user_id', $user->id)->first();
        
        if (!$publisher) {
            return redirect()->route('publisher.loa-templates.index')
                ->with('error', 'Anda harus terdaftar sebagai publisher untuk membuat template LOA.');
        }

        return view('publisher.loa-templates.create', compact('publisher'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Get publisher for current user
        $publisher = Publisher::where('user_id', $user->id)->first();
        
        if (!$publisher) {
            return redirect()->route('publisher.loa-templates.index')
                ->with('error', 'Anda harus terdaftar sebagai publisher.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'language' => 'required|in:id,en,both',
            'format' => 'required|in:html,pdf',
            'header_template' => 'required|string',
            'body_template' => 'required|string',
            'footer_template' => 'required|string',
            'variables' => 'nullable|json',
            'css_styles' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        LoaTemplate::create([
            'name' => $request->name,
            'description' => $request->description,
            'language' => $request->language,
            'format' => $request->format,
            'header_template' => $request->header_template,
            'body_template' => $request->body_template,
            'footer_template' => $request->footer_template,
            'variables' => $request->variables ? json_decode($request->variables, true) : null,
            'css_styles' => $request->css_styles,
            'is_active' => $request->has('is_active'),
            'publisher_id' => $publisher->id
        ]);

        return redirect()->route('publisher.loa-templates.index')
            ->with('success', 'Template LOA berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoaTemplate $loaTemplate)
    {
        $this->authorizeAccess($loaTemplate);

        return view('publisher.loa-templates.show', compact('loaTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoaTemplate $loaTemplate)
    {
        $this->authorizeAccess($loaTemplate);

        $user = Auth::user();
        
        // Get publisher for current user
        $publisher = Publisher::where('user_id', $user->id)->first();

        return view('publisher.loa-templates.edit', compact('loaTemplate', 'publisher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoaTemplate $loaTemplate)
    {
        $this->authorizeAccess($loaTemplate);

        $user = Auth::user();
        
        // Get publisher for current user
        $publisher = Publisher::where('user_id', $user->id)->first();
        
        if (!$publisher) {
            return redirect()->route('publisher.loa-templates.index')
                ->with('error', 'Anda harus terdaftar sebagai publisher.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'language' => 'required|in:id,en,both',
            'format' => 'required|in:html,pdf',
            'header_template' => 'required|string',
            'body_template' => 'required|string',
            'footer_template' => 'required|string',
            'variables' => 'nullable|json',
            'css_styles' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $loaTemplate->update([
            'name' => $request->name,
            'description' => $request->description,
            'language' => $request->language,
            'format' => $request->format,
            'header_template' => $request->header_template,
            'body_template' => $request->body_template,
            'footer_template' => $request->footer_template,
            'variables' => $request->variables ? json_decode($request->variables, true) : null,
            'css_styles' => $request->css_styles,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('publisher.loa-templates.index')
            ->with('success', 'Template LOA berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoaTemplate $loaTemplate)
    {
        $this->authorizeAccess($loaTemplate);

        $loaTemplate->delete();

        return redirect()->route('publisher.loa-templates.index')
            ->with('success', 'Template LOA berhasil dihapus.');
    }

    /**
     * Preview the template
     */
    public function preview(LoaTemplate $loaTemplate)
    {
        $this->authorizeAccess($loaTemplate);

        return view('publisher.loa-templates.preview', compact('loaTemplate'));
    }

    /**
     * Toggle active status
     */
    public function toggle(LoaTemplate $loaTemplate)
    {
        $this->authorizeAccess($loaTemplate);

        $loaTemplate->update([
            'is_active' => !$loaTemplate->is_active
        ]);

        $status = $loaTemplate->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Template LOA berhasil {$status}.");
    }

    /**
     * Check if user has access to the template
     */
    private function authorizeAccess(LoaTemplate $loaTemplate)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return; // Admin can access all templates
        }

        // Get publisher for current user
        $publisher = Publisher::where('user_id', $user->id)->first();
        
        if (!$publisher || $loaTemplate->publisher_id !== $publisher->id) {
            abort(403, 'Unauthorized access to LOA template.');
        }
    }
}
