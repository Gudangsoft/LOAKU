<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$req = App\Models\LoaRequest::find(2);
if (!$req) {
    echo "No LoaRequest with ID 2\n";
    exit(0);
}
try {
    echo "DEBUG: bootstrapped\n";
    \$req = App\\Models\\LoaRequest::find(2);
    if (!\$req) {
        echo "DEBUG: No LoaRequest with ID 2\n";
        exit(0);
    }
    echo "DEBUG: found request id=\"" . \$req->id . "\"\n";
    \$html = view('publisher.loa-requests.show', ['loaRequest' => \$req])->render();
    if (strlen(\$html) === 0) {
        echo "DEBUG: rendered HTML empty\n";
    } else {
        echo "DEBUG: rendered HTML length=" . strlen(\$html) . "\n";
    }
    echo "-----BEGIN_HTML-----\n";
    echo \$html;
    echo "\n-----END_HTML-----\n";
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString();
}
