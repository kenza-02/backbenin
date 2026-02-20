<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Clearing test data...\n\n";

try {
   
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    DB::statement('DELETE FROM sn_evenement_intervenant');
    echo "✓ Cleared sn_evenement_intervenant\n";
    
    DB::statement('DELETE FROM sn_podcasts');
    echo "✓ Cleared sn_podcasts\n";
    
    DB::statement('DELETE FROM sn_documents');
    echo "✓ Cleared sn_documents\n";
    
    DB::statement('DELETE FROM sn_evenements');
    echo "✓ Cleared sn_evenements\n";
    
    DB::statement('DELETE FROM sn_membres');
    echo "✓ Cleared sn_membres\n";
    
    DB::statement('DELETE FROM sn_intervenants');
    echo "✓ Cleared sn_intervenants\n";
    
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    // Reset AUTO_INCREMENT
    DB::statement('ALTER TABLE sn_evenement_intervenant AUTO_INCREMENT=1');
    DB::statement('ALTER TABLE sn_podcasts AUTO_INCREMENT=1');
    DB::statement('ALTER TABLE sn_documents AUTO_INCREMENT=1');
    DB::statement('ALTER TABLE sn_evenements AUTO_INCREMENT=1');
    DB::statement('ALTER TABLE sn_membres AUTO_INCREMENT=1');
    DB::statement('ALTER TABLE sn_intervenants AUTO_INCREMENT=1');
    
    echo "\n✓ Reset all AUTO_INCREMENT counters\n";
    echo "\nDatabase cleaned! Ready for fresh data entry via API forms.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
