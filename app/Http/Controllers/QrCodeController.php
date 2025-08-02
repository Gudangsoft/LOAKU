<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoaValidated;
use App\Services\QrCodeService;

class QrCodeController extends Controller
{
    protected $qrService;
    
    public function __construct(QrCodeService $qrService)
    {
        $this->qrService = $qrService;
    }

    /**
     * Generate QR Code for LOA verification
     */
    public function generateLoaQr($loaCode)
    {
        try {
            // Check if LOA exists and get publisher info
            $loaValidated = LoaValidated::where('loa_code', $loaCode)
                ->with(['loaRequest.journal.publisher'])
                ->first();
            
            if (!$loaValidated) {
                // Return a simple error QR code
                return $this->generateErrorQr('LOA not found: ' . $loaCode);
            }

            // Generate verification URL
            $verificationUrl = route('qr.verify', $loaCode);
            
            // Get publisher name for logo
            $publisherName = $loaValidated->loaRequest->journal->publisher->name ?? 'Default Publisher';
            
            // Try to generate simple QR Code from external service (without logo)
            $qrImage = $this->qrService->generateQrFromService($verificationUrl, 300, null);
            
            // Check if we got valid image data
            if (empty($qrImage)) {
                return $this->generateErrorQr('Failed to generate QR Code');
            }
            
            // Check if we got SVG (fallback) or PNG (from service)
            if (strpos($qrImage, '<?xml') === 0) {
                // SVG response
                return response($qrImage)->header('Content-Type', 'image/svg+xml');
            } else {
                // PNG response from service
                return response($qrImage)->header('Content-Type', 'image/png');
            }
            
        } catch (\Exception $e) {
            // Log the error and return error QR code
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            return $this->generateErrorQr('QR Generation Error');
        }
    }
    
    /**
     * Generate error QR code as SVG
     */
    private function generateErrorQr($message)
    {
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="300" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
    <rect width="300" height="300" fill="white"/>
    <rect x="10" y="10" width="280" height="280" fill="none" stroke="red" stroke-width="2"/>
    <circle cx="150" cy="120" r="40" fill="#ff4444"/>
    <text x="150" y="125" text-anchor="middle" font-family="Arial" font-size="24" fill="white">!</text>
    <text x="150" y="200" text-anchor="middle" font-family="Arial" font-size="12" fill="red">' . htmlspecialchars($message) . '</text>
    <text x="150" y="220" text-anchor="middle" font-family="Arial" font-size="10" fill="gray">QR Code Error</text>
</svg>';
        
        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Show QR Scanner page
     */
    public function showScanner()
    {
        return view('loa.qr-scanner');
    }

    /**
     * Handle QR code verification directly
     */
    public function verifyFromQr($loaCode)
    {
        $loaValidated = LoaValidated::where('loa_code', $loaCode)
            ->with(['loaRequest.journal.publisher'])
            ->first();

        return view('loa.qr-verification-result', [
            'loa' => $loaValidated
        ]);
    }

    /**
     * Generate QR Code for download
     */
    public function downloadQr($loaCode, Request $request)
    {
        // Check if LOA exists and get publisher info
        $loaValidated = LoaValidated::where('loa_code', $loaCode)
            ->with(['loaRequest.journal.publisher'])
            ->first();
        
        if (!$loaValidated) {
            abort(404, 'LOA not found');
        }

        // Generate verification URL
        $verificationUrl = route('qr.verify', $loaCode);
        
        // Get requested format (default to PNG)
        $format = $request->get('format', 'png');
        
        // Generate QR Code with appropriate size
        $qrImage = $this->qrService->generateQrFromService($verificationUrl, 512, null);
        
        // Handle different formats
        if ($format === 'svg' || strpos($qrImage, '<?xml') === 0) {
            // SVG format
            if (strpos($qrImage, '<?xml') !== 0) {
                // If service returned PNG but SVG requested, we'll still return PNG
                $format = 'png';
            } else {
                return response($qrImage)
                    ->header('Content-Type', 'image/svg+xml')
                    ->header('Content-Disposition', 'attachment; filename="QR_' . $loaCode . '.svg"');
            }
        }
        
        // Default PNG response
        return response($qrImage)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="QR_' . $loaCode . '.png"');
    }

    /**
     * Download QR Code via API (more reliable)
     */
    public function downloadQrApi($loaCode)
    {
        try {
            // Check if LOA exists
            $loaValidated = LoaValidated::where('loa_code', $loaCode)
                ->with(['loaRequest.journal.publisher'])
                ->first();
            
            if (!$loaValidated) {
                return response()->json(['error' => 'LOA not found'], 404);
            }

            // Generate verification URL
            $verificationUrl = route('qr.verify', $loaCode);
            
            // Generate simple QR Code (no logo)
            $qrImage = $this->qrService->generateQrFromService($verificationUrl, 400, null);
            
            if (empty($qrImage)) {
                return response()->json(['error' => 'Failed to generate QR'], 500);
            }
            
            // Return as base64 for JavaScript download
            if (strpos($qrImage, '<?xml') === 0) {
                // SVG to base64
                $base64 = 'data:image/svg+xml;base64,' . base64_encode($qrImage);
            } else {
                // PNG to base64
                $base64 = 'data:image/png;base64,' . base64_encode($qrImage);
            }
            
            return response()->json([
                'success' => true,
                'data' => $base64,
                'filename' => 'QR_' . $loaCode . '.png',
                'loaCode' => $loaCode
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
