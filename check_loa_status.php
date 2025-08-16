<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

$loa = App\Models\LoaRequest::find(4);
if ($loa) {
    echo "LOA Request 4:\n";
    echo "Status: " . $loa->status . "\n";
    echo "Title: " . ($loa->article_title ?? 'No title') . "\n";
    echo "Journal ID: " . $loa->journal_id . "\n";
} else {
    echo "LOA Request 4 not found\n";
}
