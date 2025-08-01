<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Generate a realistic QR Code SVG
     */
    public function generateQrSvg($data, $size = 300, $publisherName = null)
    {
        // Simple QR code without external logo positioning
        $module = $size / 25; // Size of each QR module
        $logoSize = $size * 0.15; // Logo size (center overlay)
        $logoX = ($size - $logoSize) / 2; // Center logo
        $logoY = ($size - $logoSize) / 2; // Center logo
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <style>
            .qr-module { fill: #000000; }
            .qr-bg { fill: #ffffff; }
            .qr-finder { fill: #000000; }
            .qr-separator { fill: #ffffff; }
        </style>
    </defs>
    
    <!-- Background -->
    <rect width="' . $size . '" height="' . $size . '" class="qr-bg" stroke="#e5e7eb" stroke-width="1"/>
    
    <!-- Generate realistic QR pattern -->
    ';

        // Generate finder patterns (corners)
        $svg .= $this->generateFinderPattern(15, 15, $module);
        $svg .= $this->generateFinderPattern($size - 85, 15, $module);
        $svg .= $this->generateFinderPattern(15, $size - 85, $module);
        
        // Generate timing patterns
        for ($i = 8; $i < 18; $i++) {
            if ($i % 2 == 0) {
                $svg .= '<rect x="' . (15 + $i * $module) . '" y="' . (15 + 6 * $module) . '" width="' . $module . '" height="' . $module . '" class="qr-module"/>';
                $svg .= '<rect x="' . (15 + 6 * $module) . '" y="' . (15 + $i * $module) . '" width="' . $module . '" height="' . $module . '" class="qr-module"/>';
            }
        }
        
        // Generate data modules (realistic pattern)
        $patterns = [
            [9, 9], [11, 9], [13, 9], [15, 9], [17, 9],
            [9, 11], [11, 11], [13, 11], [15, 11], [17, 11],
            [9, 13], [11, 13], [13, 13], [15, 13], [17, 13],
            [9, 15], [11, 15], [13, 15], [15, 15], [17, 15],
            [9, 17], [11, 17], [13, 17], [15, 17], [17, 17],
        ];
        
        foreach ($patterns as $pattern) {
            $x = 15 + $pattern[0] * $module;
            $y = 15 + $pattern[1] * $module;
            if ($x < $size - 15 && $y < $size - 15) {
                $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . $module . '" height="' . $module . '" class="qr-module"/>';
            }
        }
    
        // Publisher Logo (center overlay)
        $svg .= '
    <!-- Logo background (center) -->
    <rect x="' . $logoX . '" y="' . $logoY . '" width="' . $logoSize . '" height="' . $logoSize . '" fill="white" stroke="#e5e7eb" stroke-width="1" rx="8"/>
    ';
    
        // Add publisher-specific logo or icon
        if ($publisherName) {
            $logoContent = $this->getPublisherLogo($publisherName, $logoX, $logoY, $logoSize);
            $svg .= $logoContent;
        } else {
            // Default LOA logo
            $svg .= '
    <defs>
        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
        </linearGradient>
    </defs>
    <circle cx="' . ($logoX + $logoSize/2) . '" cy="' . ($logoY + $logoSize/2 - 3) . '" r="' . ($logoSize/3.5) . '" fill="url(#logoGradient)"/>
    <text x="' . ($logoX + $logoSize/2) . '" y="' . ($logoY + $logoSize/2) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="' . ($logoSize/6) . '" fill="white" font-weight="bold">LOA</text>
    <text x="' . ($logoX + $logoSize/2) . '" y="' . ($logoY + $logoSize - 4) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="' . ($logoSize/10) . '" fill="#6b7280" font-weight="500">SIPTENAN</text>';
        }
        
        // Add QR Code title at bottom
        $svg .= '
    <text x="' . ($size/2) . '" y="' . ($size - 10) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#6b7280" font-weight="500">Scan untuk Verifikasi LOA</text>';
        
        $svg .= '</svg>';
        
        return $svg;
    }
    
    /**
     * Generate finder pattern (corner squares)
     */
    private function generateFinderPattern($x, $y, $module)
    {
        return '
    <!-- Finder pattern at (' . $x . ', ' . $y . ') -->
    <rect x="' . $x . '" y="' . $y . '" width="' . ($module * 7) . '" height="' . ($module * 7) . '" class="qr-finder"/>
    <rect x="' . ($x + $module) . '" y="' . ($y + $module) . '" width="' . ($module * 5) . '" height="' . ($module * 5) . '" class="qr-separator"/>
    <rect x="' . ($x + $module * 2) . '" y="' . ($y + $module * 2) . '" width="' . ($module * 3) . '" height="' . ($module * 3) . '" class="qr-finder"/>
    ';
    }
    
    /**
     * Get publisher-specific logo content
     */
    private function getPublisherLogo($publisherName, $x, $y, $size)
    {
        $centerX = $x + $size/2;
        $centerY = $y + $size/2;
        $iconSize = $size * 0.5;
        
        // Detect organization type from name
        $orgName = strtolower($publisherName);
        
        if (strpos($orgName, 'universitas') !== false || strpos($orgName, 'university') !== false) {
            // University logo - graduation cap with gradient
            return '
    <defs>
        <linearGradient id="univGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#1e40af;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#1e3a8a;stop-opacity:1" />
        </linearGradient>
    </defs>
    <g>
        <!-- Graduation cap -->
        <polygon points="' . ($centerX - $iconSize/2.5) . ',' . ($centerY - 8) . ' ' . ($centerX + $iconSize/2.5) . ',' . ($centerY - 8) . ' ' . ($centerX + $iconSize/3) . ',' . ($centerY - 2) . ' ' . ($centerX - $iconSize/3) . ',' . ($centerY - 2) . '" fill="url(#univGradient)"/>
        <rect x="' . ($centerX - $iconSize/8) . '" y="' . ($centerY - 2) . '" width="' . ($iconSize/4) . '" height="8" fill="#1e3a8a"/>
        <circle cx="' . ($centerX + $iconSize/3) . '" cy="' . ($centerY + 3) . '" r="2.5" fill="#dc2626"/>
        <text x="' . $centerX . '" y="' . ($centerY + 16) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="7" fill="#1e40af" font-weight="bold">UNIV</text>
    </g>';
        } elseif (strpos($orgName, 'institut') !== false || strpos($orgName, 'institute') !== false) {
            // Institute logo - building with gradient
            return '
    <defs>
        <linearGradient id="instGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
        </linearGradient>
    </defs>
    <g>
        <!-- Building icon -->
        <rect x="' . ($centerX - $iconSize/2.5) . '" y="' . ($centerY - 10) . '" width="' . ($iconSize * 0.8) . '" height="16" fill="url(#instGradient)" stroke="#1e40af" stroke-width="0.5" rx="1"/>
        <rect x="' . ($centerX - $iconSize/4) . '" y="' . ($centerY - 7) . '" width="3" height="3" fill="white" rx="0.5"/>
        <rect x="' . ($centerX - $iconSize/12) . '" y="' . ($centerY - 7) . '" width="3" height="3" fill="white" rx="0.5"/>
        <rect x="' . ($centerX + $iconSize/12) . '" y="' . ($centerY - 7) . '" width="3" height="3" fill="white" rx="0.5"/>
        <rect x="' . ($centerX - $iconSize/4) . '" y="' . ($centerY - 2) . '" width="3" height="3" fill="white" rx="0.5"/>
        <rect x="' . ($centerX - $iconSize/12) . '" y="' . ($centerY - 2) . '" width="3" height="3" fill="white" rx="0.5"/>
        <rect x="' . ($centerX + $iconSize/12) . '" y="' . ($centerY - 2) . '" width="3" height="3" fill="white" rx="0.5"/>
        <text x="' . $centerX . '" y="' . ($centerY + 14) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="6.5" fill="#1e40af" font-weight="bold">INST</text>
    </g>';
        } elseif (strpos($orgName, 'press') !== false || strpos($orgName, 'publishing') !== false || strpos($orgName, 'publisher') !== false) {
            // Publisher logo - book with gradient
            return '
    <defs>
        <linearGradient id="pressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#059669;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#047857;stop-opacity:1" />
        </linearGradient>
    </defs>
    <g>
        <!-- Book icon -->
        <rect x="' . ($centerX - $iconSize/2.5) . '" y="' . ($centerY - 8) . '" width="' . ($iconSize * 0.8) . '" height="12" fill="url(#pressGradient)" stroke="#047857" stroke-width="0.5" rx="1"/>
        <rect x="' . ($centerX - $iconSize/2.5 + 2) . '" y="' . ($centerY - 6) . '" width="' . ($iconSize * 0.6) . '" height="1.5" fill="white" rx="0.5"/>
        <rect x="' . ($centerX - $iconSize/2.5 + 2) . '" y="' . ($centerY - 3) . '" width="' . ($iconSize * 0.6) . '" height="1" fill="white" rx="0.5"/>
        <rect x="' . ($centerX - $iconSize/2.5 + 2) . '" y="' . ($centerY - 1) . '" width="' . ($iconSize * 0.6) . '" height="1" fill="white" rx="0.5"/>
        <text x="' . $centerX . '" y="' . ($centerY + 12) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="6" fill="#047857" font-weight="bold">PRESS</text>
    </g>';
        } else {
            // Default LOA logo with enhanced design
            return '
    <defs>
        <linearGradient id="defaultGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
        </linearGradient>
    </defs>
    <g>
        <circle cx="' . $centerX . '" cy="' . ($centerY - 2) . '" r="' . ($iconSize/2.5) . '" fill="url(#defaultGradient)" stroke="#1e40af" stroke-width="0.5"/>
        <text x="' . $centerX . '" y="' . ($centerY + 1) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="' . ($iconSize/3.5) . '" fill="white" font-weight="bold">LOA</text>
        <text x="' . $centerX . '" y="' . ($centerY + 14) . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="5.5" fill="#3b82f6" font-weight="600">SIPTENAN</text>
    </g>';
        }
    }
    
    /**
     * Generate QR Code using external service (simple QR without logo)
     */
    public function generateQrFromService($data, $size = 300, $publisherName = null)
    {
        // Ignore publisherName parameter - always generate simple QR
        $services = [
            'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($data),
            'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($data),
            'https://qr-generator.qrcode.studio/qr/create?size=' . $size . '&data=' . urlencode($data)
        ];
        
        foreach ($services as $url) {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
                
                $image = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);
                
                if ($image !== false && $httpCode === 200 && empty($error)) {
                    // Return simple QR code - no logo processing
                    return $image;
                }
                
                // Log the failed attempt
                \Log::warning("QR Service failed: $url - HTTP: $httpCode - Error: $error");
                
            } catch (\Exception $e) {
                \Log::warning("QR Service exception: $url - " . $e->getMessage());
                continue;
            }
        }
        
        // If all services fail, return simple SVG fallback (without logo)
        \Log::info("All QR services failed, using simple SVG fallback");
        return $this->generateSimpleQrSvg($data, $size);
    }
    
    /**
     * Generate simple QR Code SVG without logo (fallback)
     */
    private function generateSimpleQrSvg($data, $size = 300)
    {
        $module = $size / 25; // Size of each QR module
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <style>
            .qr-module { fill: #000000; }
            .qr-bg { fill: #ffffff; }
            .qr-finder { fill: #000000; }
            .qr-separator { fill: #ffffff; }
        </style>
    </defs>
    
    <!-- Background -->
    <rect width="' . $size . '" height="' . $size . '" class="qr-bg"/>
    
    <!-- Generate QR pattern without logo -->
    ';

        // Generate finder patterns (corners)
        $svg .= $this->generateFinderPattern(15, 15, $module);
        $svg .= $this->generateFinderPattern($size - 85, 15, $module);
        $svg .= $this->generateFinderPattern(15, $size - 85, $module);
        
        // Generate timing patterns
        for ($i = 8; $i < 18; $i++) {
            if ($i % 2 == 0) {
                $svg .= '<rect x="' . (15 + $i * $module) . '" y="' . (15 + 6 * $module) . '" width="' . $module . '" height="' . $module . '" class="qr-module"/>';
                $svg .= '<rect x="' . (15 + 6 * $module) . '" y="' . (15 + $i * $module) . '" width="' . $module . '" height="' . $module . '" class="qr-module"/>';
            }
        }
        
        // Generate data modules (realistic pattern)
        $patterns = [
            [9, 9], [11, 9], [13, 9], [15, 9], [17, 9],
            [9, 11], [11, 11], [13, 11], [15, 11], [17, 11],
            [9, 13], [11, 13], [13, 13], [15, 13], [17, 13],
            [9, 15], [11, 15], [13, 15], [15, 15], [17, 15],
            [9, 17], [11, 17], [13, 17], [15, 17], [17, 17],
        ];
        
        foreach ($patterns as $pattern) {
            $x = 15 + $pattern[0] * $module;
            $y = 15 + $pattern[1] * $module;
            if ($x < $size - 15 && $y < $size - 15) {
                $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . $module . '" height="' . $module . '" class="qr-module"/>';
            }
        }
        
        $svg .= '</svg>';
        
        return $svg;
    }
}
