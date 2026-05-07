<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate a real, scannable QR code.
     * Uses the installed simplesoftwareio/simple-qrcode library (SVG, no ImageMagick needed).
     * Falls back to external APIs if the library fails.
     */
    public function generateQrFromService($data, $size = 300, $publisherName = null)
    {
        // Try local library first (always works, no external dependency)
        try {
            $svg = QrCode::format('svg')
                ->size($size)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($data);

            return (string) $svg;
        } catch (\Exception $e) {
            \Log::warning('Local QR generation failed: ' . $e->getMessage());
        }

        // Fallback: external services
        $services = [
            'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($data),
            'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($data),
        ];

        foreach ($services as $url) {
            try {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL            => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_TIMEOUT        => 10,
                    CURLOPT_CONNECTTIMEOUT => 5,
                ]);
                $image    = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($image && $httpCode === 200) {
                    return $image;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        \Log::error('All QR generation methods failed for: ' . $data);
        return null;
    }

    /**
     * Generate QR SVG directly (used by LoaController).
     */
    public function generateQrSvg($data, $size = 300, $publisherName = null)
    {
        return $this->generateQrFromService($data, $size, $publisherName);
    }
}
