<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = [
    'sn_categories',
    'sn_membres',
    'sn_intervenants',
    'sn_documents',
    'sn_podcasts',
    'sn_evenements',
    'users',
];

echo "Fixing AUTO_INCREMENT for all tables...\n\n";

foreach($tables as $table){
    $max = DB::select("SELECT IFNULL(MAX(id), 0) as m FROM $table")[0]->m;
    $next = $max + 1;
    
    try{
        DB::statement("ALTER TABLE $table AUTO_INCREMENT=$next");
        echo "✓ $table: AUTO_INCREMENT set to $next\n";
    }catch(Exception $e){
        echo "✗ $table: " . $e->getMessage() . "\n";
    }
}

echo "\nDone! Next inserted records will use sequential IDs.\n";
