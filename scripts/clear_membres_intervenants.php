<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement('DELETE FROM sn_membres');
DB::statement('DELETE FROM sn_intervenants');
DB::statement('ALTER TABLE sn_membres AUTO_INCREMENT=1');
DB::statement('ALTER TABLE sn_intervenants AUTO_INCREMENT=1');

echo "✓ Tables vidées et AUTO_INCREMENT réinitialisé.\n";
