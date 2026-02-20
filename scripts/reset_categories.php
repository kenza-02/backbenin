<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Truncate and reset
DB::statement('DELETE FROM sn_categories');
DB::statement('ALTER TABLE sn_categories AUTO_INCREMENT=1');

// Re-insert with correct IDs matching SQL dump
$categories = [
    ['id' => 1, 'libelle' => 'Tech'],
    ['id' => 2, 'libelle' => 'Formation'],
    ['id' => 3, 'libelle' => 'Civic Tech'],
    ['id' => 4, 'libelle' => 'Innovation'],
    ['id' => 5, 'libelle' => 'Sensibilisation'],
    ['id' => 6, 'libelle' => 'Citoyennete'],
    ['id' => 7, 'libelle' => 'Evenement'],
    ['id' => 8, 'libelle' => 'Activites'],
];

foreach($categories as $cat){
    DB::insert('INSERT INTO sn_categories (id, libelle, created_at, updated_at) VALUES (?, ?, NOW(), NOW())', [$cat['id'], $cat['libelle']]);
}

echo "Categories reset with correct IDs:\n";
$result = DB::select('SELECT id, libelle FROM sn_categories ORDER BY id');
foreach($result as $r){ 
    echo $r->id . ' => ' . $r->libelle . "\n"; 
}
