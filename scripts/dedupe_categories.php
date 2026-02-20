<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Trim libelle
    DB::statement("UPDATE sn_categories SET libelle = TRIM(libelle)");

   
    while (DB::table('sn_categories')->where('libelle', 'like', '%  %')->exists()) {
        DB::statement("UPDATE sn_categories SET libelle = REPLACE(libelle, '  ', ' ')");
    }

    // Delete duplicates keeping the smallest id (case-insensitive)
    DB::statement("DELETE t1 FROM sn_categories t1 INNER JOIN sn_categories t2 WHERE t1.id > t2.id AND LCASE(t1.libelle) = LCASE(t2.libelle)");

    // Add unique index if not exists
    // First check if index exists
    $indexes = DB::select("SHOW INDEX FROM sn_categories WHERE Key_name = 'unique_libelle'");
    if (empty($indexes)) {
        DB::statement("ALTER TABLE sn_categories ADD UNIQUE INDEX unique_libelle (libelle)");
    }

    echo "dedupe_ok\n";
} catch (Exception $e) {
    echo "error: " . $e->getMessage() . "\n";
}
