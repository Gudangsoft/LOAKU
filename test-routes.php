<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;

// Create the application
$app = new Application(realpath(__DIR__));

// Boot the application  
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Boot the kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Load routes
require_once 'routes/web.php';

// Test if admin register route exists
try {
    $router = app('router');
    $routes = $router->getRoutes();
    
    echo "=== Testing Admin Routes ===\n";
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'admin') !== false) {
            $methods = implode('|', $route->methods());
            $name = $route->getName() ?: 'unnamed';
            echo "{$methods} {$uri} [{$name}]\n";
        }
    }
    
    // Check specifically for register route
    $registerRoute = $routes->getByName('admin.register');
    if ($registerRoute) {
        echo "\n✅ Admin register route found: " . $registerRoute->uri() . "\n";
    } else {
        echo "\n❌ Admin register route NOT found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
