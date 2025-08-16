<?php
/**
 * Emergency Fix Script for loa.siptenan.org
 * Upload this file to server root and access via browser
 * URL: https://loa.siptenan.org/emergency_fix.php
 */

// Security check - remove this file after use!
$allowed_ips = ['127.0.0.1', '::1']; // Add your IP here
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips) && !isset($_GET['force'])) {
    die('Access denied. Add your IP to allowed_ips array or use ?force=1');
}

echo "<h1>🚨 Emergency Fix for loa.siptenan.org</h1>";
echo "<hr>";

try {
    // Change to Laravel directory
    if (file_exists('artisan')) {
        echo "✅ Found Laravel artisan<br>";
    } else {
        echo "❌ artisan not found. Please upload this file to Laravel root directory<br>";
        exit;
    }
    
    echo "<h2>Running Fixes...</h2>";
    
    // Clear caches
    $commands = [
        'php artisan cache:clear',
        'php artisan config:clear', 
        'php artisan route:clear',
        'php artisan view:clear',
        'php artisan session:flush',
        'php artisan key:generate --force'
    ];
    
    foreach ($commands as $cmd) {
        echo "Running: <code>$cmd</code><br>";
        $output = shell_exec($cmd . ' 2>&1');
        echo "<pre style='background:#f0f0f0;padding:10px;margin:10px 0;'>$output</pre>";
    }
    
    // Fix permissions
    echo "<h2>Setting Permissions...</h2>";
    $dirs = [
        'storage/framework/sessions',
        'storage/framework/views', 
        'storage/framework/cache',
        'storage/logs',
        'bootstrap/cache'
    ];
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            echo "✅ Created directory: $dir<br>";
        }
        chmod($dir, 0777);
        echo "✅ Set permissions for: $dir<br>";
    }
    
    // Recache
    echo "<h2>Rebuilding Cache...</h2>";
    $cache_commands = [
        'php artisan config:cache',
        'php artisan route:cache'
    ];
    
    foreach ($cache_commands as $cmd) {
        echo "Running: <code>$cmd</code><br>";
        $output = shell_exec($cmd . ' 2>&1');
        echo "<pre style='background:#f0f0f0;padding:10px;margin:10px 0;'>$output</pre>";
    }
    
    echo "<hr>";
    echo "<h2>🎉 Fixes Applied Successfully!</h2>";
    echo "<p><strong>Test these URLs:</strong></p>";
    echo "<ul>";
    echo "<li><a href='https://loa.siptenan.org/admin/login' target='_blank'>Admin Login</a></li>";
    echo "<li><a href='https://loa.siptenan.org/' target='_blank'>Home Page</a></li>";
    echo "<li><a href='https://loa.siptenan.org/publisher/login' target='_blank'>Publisher Login</a></li>";
    echo "</ul>";
    
    echo "<p style='color:red;'><strong>⚠️ IMPORTANT: Delete this file after use for security!</strong></p>";
    echo "<p><code>rm emergency_fix.php</code></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>";
    echo "<pre style='color:red;'>" . $e->getMessage() . "</pre>";
}
?>
