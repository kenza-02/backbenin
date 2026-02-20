<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cats = App\Models\Categorie::orderBy('id')->get()->toArray();

echo json_encode($cats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
