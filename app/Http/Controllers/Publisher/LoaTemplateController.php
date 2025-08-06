<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\LoaTemplate;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoaTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('publisher');
    }

    /**
     * Display a listing of LOA templates
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get publishers owned by this user
        $publisherIds = Publisher::where('user_id', $user->id)->pluck('id');
        
        $templates = LoaTemplate::whereIn('publisher_id', $publisherIds)
            ->orWhereNull('publisher_id') // Include system templates
            ->with('publisher')
            ->orderBy('is_default', 'desc')
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->paginate(10);

        return view('publisher.loa-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new LOA template
     */
    public function create()
    {
        $user = Auth::user();
        $publishers = Publisher::where('user_id', $user->id)->get();
        
        return view('publisher.loa-templates.create', compact('publishers'));
    }

    /**
     * Store a newly created LOA template
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'required|in:id,en,both',
            'format' => 'required|in:html,pdf',
            'header_template' => 'nullable|string',
            'body_template' => 'required|string',
            'footer_template' => 'nullable|string',
            'css_styles' => 'nullable|string',
            'publisher_id' => 'nullable|exists:publishers,id',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'variables' => 'nullable|string'
        ]);

        // Verify publisher ownership if publisher_id is provided
        if ($request->publisher_id) {
            $publisher = Publisher::where('id', $request->publisher_id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$publisher) {
                return back()->with('error', 'Anda tidak memiliki akses ke publisher tersebut.');
            }
        }

        // Parse variables from JSON string
        $variables = null;
        if ($request->variables) {
            $variables = json_decode($request->variables, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Format variables tidak valid. Gunakan format JSON.');
            }
        }

        LoaTemplate::create([
            'name' => $request->name,
            'description' => $request->description,
            'language' => $request->language,
            'format' => $request->format,
            'header_template' => $request->header_template,
            'body_template' => $request->body_template,
            'footer_template' => $request->footer_template,
            'css_styles' => $request->css_styles,
            'publisher_id' => $request->publisher_id,
            'is_active' => $request->boolean('is_active'),
            'is_default' => $request->boolean('is_default'),
            'variables' => $variables,
        ]);

        return redirect()->route('publisher.loa-templates')
            ->with('success', 'Template LOA berhasil dibuat.');
    }

    /**
     * Display the specified LOA template
     */
    public function show(LoaTemplate $loaTemplate)
    {
        $user = Auth::user();
        
        // Check if user owns this template
        if ($loaTemplate->publisher_id) {
            $publisher = Publisher::where('id', $loaTemplate->publisher_id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$publisher) {
                abort(403, 'Anda tidak memiliki akses ke template ini.');
            }
        }

        return view('publisher.loa-templates.show', compact('loaTemplate'));
    }

    /**
     * Show the form for editing the specified LOA template
     */
    public function edit(LoaTemplate $loaTemplate)
    {
        $user = Auth::user();
        
        // Check if user owns this template
        if ($loaTemplate->publisher_id) {
            $publisher = Publisher::where('id', $loaTemplate->publisher_id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$publisher) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit template ini.');
            }
        } else {
            // System templates cannot be edited by publishers
            abort(403, 'Template sistem tidak dapat diedit.');
        }

        $publishers = Publisher::where('user_id', $user->id)->get();
        
        return view('publisher.loa-templates.edit', compact('loaTemplate', 'publishers'));
    }

    /**
     * Update the specified LOA template
     */
    public function update(Request $request, LoaTemplate $loaTemplate)
    {
        $user = Auth::user();
        
        // Check if user owns this template
        if ($loaTemplate->publisher_id) {
            $publisher = Publisher::where('id', $loaTemplate->publisher_id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$publisher) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit template ini.');
            }
        } else {
            abort(403, 'Template sistem tidak dapat diedit.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'required|in:id,en,both',
            'format' => 'required|in:html,pdf',
            'header_template' => 'nullable|string',
            'body_template' => 'required|string',
            'footer_template' => 'nullable|string',
            'css_styles' => 'nullable|string',
            'publisher_id' => 'nullable|exists:publishers,id',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'variables' => 'nullable|string'
        ]);

        // Verify new publisher ownership if changed
        if ($request->publisher_id) {
            $newPublisher = Publisher::where('id', $request->publisher_id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$newPublisher) {
                return back()->with('error', 'Anda tidak memiliki akses ke publisher tersebut.');
            }
        }

        // Parse variables from JSON string
        $variables = $loaTemplate->variables;
        if ($request->has('variables') && $request->variables) {
            $variables = json_decode($request->variables, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Format variables tidak valid. Gunakan format JSON.');
            }
        }

        $loaTemplate->update([
            'name' => $request->name,
            'description' => $request->description,
            'language' => $request->language,
            'format' => $request->format,
            'header_template' => $request->header_template,
            'body_template' => $request->body_template,
            'footer_template' => $request->footer_template,
            'css_styles' => $request->css_styles,
            'publisher_id' => $request->publisher_id,
            'is_active' => $request->boolean('is_active'),
            'is_default' => $request->boolean('is_default'),
            'variables' => $variables,
        ]);

        return redirect()->route('publisher.loa-templates')
            ->with('success', 'Template LOA berhasil diperbarui.');
    }

    /**
     * Remove the specified LOA template
     */
    public function destroy(LoaTemplate $loaTemplate)
    {
        $user = Auth::user();
        
        // Check if user owns this template
        if ($loaTemplate->publisher_id) {
            $publisher = Publisher::where('id', $loaTemplate->publisher_id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$publisher) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus template ini.');
            }
        } else {
            abort(403, 'Template sistem tidak dapat dihapus.');
        }

        $loaTemplate->delete();

        return redirect()->route('publisher.loa-templates')
            ->with('success', 'Template LOA berhasil dihapus.');
    }

    /**
     * Preview the LOA template
     */
    public function preview(LoaTemplate $loaTemplate)
    {
        $user = Auth::user();
        
        // Check if user has access to this template
        if ($loaTemplate->publisher_id) {
            $publisher = Publisher::where('id', $loaTemplate->publisher_id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$publisher) {
                abort(403, 'Anda tidak memiliki akses ke template ini.');
            }
        }

        // Sample data for preview
        $sampleData = [
            'article_title' => 'Sample Article Title for LOA Preview',
            'author_name' => 'John Doe, Jane Smith',
            'registration_number' => 'LOASIP.2024.001',
            'publisher_name' => $loaTemplate->publisher ? $loaTemplate->publisher->name : 'Sample Publisher',
            'journal_name' => 'Sample Journal Name',
            'submission_date' => now()->format('d F Y'),
            'approval_date' => now()->addDays(7)->format('d F Y'),
            'website' => 'https://example.com',
            'email' => 'publisher@example.com'
        ];

        return view('publisher.loa-templates.preview', compact('loaTemplate', 'sampleData'));
    }
}
