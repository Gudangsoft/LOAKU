Route::get('/test-qr/{code}', function($code) {
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="300" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
    <rect width="300" height="300" fill="white"/>
    
    <!-- QR Code Pattern -->
    <!-- Top-left finder pattern -->
    <rect x="20" y="20" width="60" height="60" fill="black"/>
    <rect x="30" y="30" width="40" height="40" fill="white"/>
    <rect x="40" y="40" width="20" height="20" fill="black"/>
    
    <!-- Top-right finder pattern -->
    <rect x="220" y="20" width="60" height="60" fill="black"/>
    <rect x="230" y="30" width="40" height="40" fill="white"/>
    <rect x="240" y="40" width="20" height="20" fill="black"/>
    
    <!-- Bottom-left finder pattern -->
    <rect x="20" y="220" width="60" height="60" fill="black"/>
    <rect x="30" y="230" width="40" height="40" fill="white"/>
    <rect x="40" y="240" width="20" height="20" fill="black"/>
    
    <!-- Data modules -->
    <rect x="100" y="50" width="10" height="10" fill="black"/>
    <rect x="120" y="50" width="10" height="10" fill="black"/>
    <rect x="140" y="60" width="10" height="10" fill="black"/>
    <rect x="160" y="70" width="10" height="10" fill="black"/>
    <rect x="180" y="50" width="10" height="10" fill="black"/>
    
    <!-- More data patterns -->
    <rect x="90" y="100" width="10" height="10" fill="black"/>
    <rect x="110" y="120" width="10" height="10" fill="black"/>
    <rect x="130" y="140" width="10" height="10" fill="black"/>
    <rect x="150" y="160" width="10" height="10" fill="black"/>
    <rect x="170" y="180" width="10" height="10" fill="black"/>
    <rect x="190" y="160" width="10" height="10" fill="black"/>
    <rect x="170" y="140" width="10" height="10" fill="black"/>
    <rect x="150" y="120" width="10" height="10" fill="black"/>
    <rect x="130" y="100" width="10" height="10" fill="black"/>
    
    <!-- Publisher Logo -->
    <rect x="120" y="120" width="60" height="60" fill="white" stroke="black" stroke-width="2" rx="8"/>
    <circle cx="150" cy="150" r="20" fill="#007bff"/>
    <text x="150" y="155" text-anchor="middle" font-family="Arial" font-size="10" fill="white" font-weight="bold">LOA</text>
    
    <!-- Code text -->
    <text x="150" y="280" text-anchor="middle" font-family="Arial" font-size="12" fill="gray">' . $code . '</text>
</svg>';
    
    return response($svg)->header("Content-Type", "image/svg+xml");
});
