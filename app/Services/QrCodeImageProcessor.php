<?php

namespace App\Services;

class QrCodeImageProcessor
{
    /**
     * Add publisher logo OUTSIDE QR code area to avoid scanning interference
     */
    public function addLogoOverlay($qrImageData, $publisherName, $size = 300)
    {
        // Create image from QR code data
        $qrImage = imagecreatefromstring($qrImageData);
        if (!$qrImage) {
            return $qrImageData; // Return original if failed
        }
        
        // Create a larger canvas to accommodate logo outside QR area
        $canvasSize = $size + 60; // Extra space for logo
        $canvas = imagecreatetruecolor($canvasSize, $canvasSize);
        
        // White background
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        
        // Copy QR code to center of canvas
        $qrX = 30;
        $qrY = 30;
        imagecopy($canvas, $qrImage, $qrX, $qrY, 0, 0, $size, $size);
        
        // Create logo in top-right corner (outside QR area)
        $logoSize = intval($size * 0.12); // 12% of QR size
        $logoImage = $this->createPublisherLogo($publisherName, $logoSize);
        
        if ($logoImage) {
            // Position logo in top-right corner, outside QR area
            $logoX = $canvasSize - $logoSize - 10;
            $logoY = 10;
            
            // Add white background for logo
            $black = imagecolorallocate($canvas, 0, 0, 0);
            imagefilledrectangle($canvas, 
                $logoX - 3, 
                $logoY - 3, 
                $logoX + $logoSize + 3, 
                $logoY + $logoSize + 3, 
                $white
            );
            
            // Add border around logo area
            imagerectangle($canvas, 
                $logoX - 3, 
                $logoY - 3, 
                $logoX + $logoSize + 3, 
                $logoY + $logoSize + 3, 
                $black
            );
            
            // Place logo in top-right corner
            imagecopy($canvas, $logoImage, $logoX, $logoY, 0, 0, $logoSize, $logoSize);
            
            // Clean up logo image
            imagedestroy($logoImage);
        }
        
        // Add text at bottom
        $textColor = imagecolorallocate($canvas, 100, 100, 100);
        imagestring($canvas, 2, ($canvasSize/2) - 60, $canvasSize - 15, "Scan untuk Verifikasi LOA", $textColor);
        
        // Output image to string
        ob_start();
        imagepng($canvas, null, 9);
        $result = ob_get_contents();
        ob_end_clean();
        
        // Clean up images
        imagedestroy($qrImage);
        imagedestroy($canvas);
        
        return $result;
    }
    
    /**
     * Create publisher logo based on name with better design
     */
    private function createPublisherLogo($publisherName, $size)
    {
        $image = imagecreatetruecolor($size, $size);
        if (!$image) {
            return null;
        }
        
        // Enable alpha blending for transparency
        imagealphablending($image, true);
        imagesavealpha($image, true);
        
        // Transparent background
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);
        
        // Determine colors and design based on publisher type
        $orgName = strtolower($publisherName);
        
        if (strpos($orgName, 'universitas') !== false || strpos($orgName, 'university') !== false) {
            // University - Blue gradient effect
            $bgColor = imagecolorallocate($image, 30, 64, 175);
            $bgColorLight = imagecolorallocate($image, 59, 130, 246);
            $textColor = imagecolorallocate($image, 255, 255, 255);
            
            // Create gradient effect
            $centerX = $size / 2;
            $centerY = $size / 2;
            $radius = $size * 0.4;
            
            // Draw gradient circle
            for ($i = $radius; $i > 0; $i--) {
                $alpha = intval(127 - (127 * ($radius - $i) / $radius));
                $gradientColor = imagecolorallocatealpha($image, 30 + ($i * 0.5), 64 + ($i * 2), 175, $alpha);
                imagefilledellipse($image, $centerX, $centerY, $i * 2, $i * 2, $gradientColor);
            }
            
            // Add graduation cap symbol
            $capPoints = array(
                $centerX - $size*0.2, $centerY - $size*0.1,
                $centerX + $size*0.2, $centerY - $size*0.1,
                $centerX + $size*0.15, $centerY + $size*0.05,
                $centerX - $size*0.15, $centerY + $size*0.05
            );
            imagefilledpolygon($image, $capPoints, 4, $textColor);
            
            // Add text
            $fontSize = intval($size * 0.15);
            $text = 'UNI';
            $bbox = imagettfbbox($fontSize, 0, __DIR__ . '/../../public/fonts/arial.ttf', $text);
            if ($bbox) {
                $textX = $centerX - ($bbox[4] / 2);
                $textY = $centerY + $size*0.25;
                imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, __DIR__ . '/../../public/fonts/arial.ttf', $text);
            } else {
                // Fallback to imagestring
                imagestring($image, 3, $centerX - 10, $centerY + $size*0.2, $text, $textColor);
            }
            
        } elseif (strpos($orgName, 'institut') !== false || strpos($orgName, 'institute') !== false) {
            // Institute - Red with building icon
            $bgColor = imagecolorallocate($image, 220, 38, 38);
            $bgColorLight = imagecolorallocate($image, 248, 113, 113);
            $textColor = imagecolorallocate($image, 255, 255, 255);
            
            $centerX = $size / 2;
            $centerY = $size / 2;
            
            // Draw building shape
            $buildingWidth = $size * 0.6;
            $buildingHeight = $size * 0.5;
            $buildingX = $centerX - $buildingWidth/2;
            $buildingY = $centerY - $buildingHeight/2;
            
            imagefilledrectangle($image, $buildingX, $buildingY, $buildingX + $buildingWidth, $buildingY + $buildingHeight, $bgColor);
            
            // Add windows
            $windowSize = $size * 0.08;
            for ($row = 0; $row < 2; $row++) {
                for ($col = 0; $col < 3; $col++) {
                    $windowX = $buildingX + ($col + 1) * ($buildingWidth / 4) - $windowSize/2;
                    $windowY = $buildingY + ($row + 1) * ($buildingHeight / 3) - $windowSize/2;
                    imagefilledrectangle($image, $windowX, $windowY, $windowX + $windowSize, $windowY + $windowSize, $textColor);
                }
            }
            
            // Add text
            $text = 'INST';
            imagestring($image, 2, $centerX - 12, $centerY + $size*0.3, $text, $bgColor);
            
        } elseif (strpos($orgName, 'press') !== false || strpos($orgName, 'publishing') !== false) {
            // Press - Green with book icon
            $bgColor = imagecolorallocate($image, 5, 150, 105);
            $textColor = imagecolorallocate($image, 255, 255, 255);
            
            $centerX = $size / 2;
            $centerY = $size / 2;
            
            // Draw book shape
            $bookWidth = $size * 0.5;
            $bookHeight = $size * 0.6;
            $bookX = $centerX - $bookWidth/2;
            $bookY = $centerY - $bookHeight/2;
            
            imagefilledrectangle($image, $bookX, $bookY, $bookX + $bookWidth, $bookY + $bookHeight, $bgColor);
            
            // Add book pages (lines)
            $lineColor = imagecolorallocate($image, 255, 255, 255);
            for ($i = 1; $i <= 3; $i++) {
                $lineY = $bookY + ($i * $bookHeight / 4);
                imageline($image, $bookX + 3, $lineY, $bookX + $bookWidth - 3, $lineY, $lineColor);
            }
            
            // Add text
            $text = 'PRESS';
            imagestring($image, 1, $centerX - 12, $centerY + $size*0.35, $text, $bgColor);
            
        } else {
            // Default - Purple LOA logo
            $bgColor = imagecolorallocate($image, 59, 130, 246);
            $textColor = imagecolorallocate($image, 255, 255, 255);
            
            $centerX = $size / 2;
            $centerY = $size / 2;
            $radius = $size * 0.4;
            
            // Draw circle
            imagefilledellipse($image, $centerX, $centerY, $radius * 2, $radius * 2, $bgColor);
            
            // Add LOA text
            $text = 'LOA';
            imagestring($image, 3, $centerX - 12, $centerY - 8, $text, $textColor);
            
            $text2 = 'SIPTENAN';
            imagestring($image, 1, $centerX - 16, $centerY + $size*0.25, $text2, $bgColor);
        }
        
        return $image;
    }
    
    /**
     * Check if GD extension is available
     */
    public function isGdAvailable()
    {
        return extension_loaded('gd');
    }
}
