<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function dedupeTable($table, $groupExpr, $colsDesc = []){
    echo "\n--- Dedupe $table (group by $groupExpr) ---\n";
    $dup = DB::select("SELECT $groupExpr, COUNT(*) as c FROM $table GROUP BY $groupExpr HAVING c>1");
    $totalRemoved = 0;
    foreach($dup as $d){
      
    }
    $sql = "DELETE t1 FROM $table t1 INNER JOIN $table t2 ON ($groupExpr) WHERE t1.id > t2.id";
    try{
        $r = DB::statement($sql);
        echo "Executed delete on $table.\n";
    }catch(Exception $e){
        echo "Delete failed for $table: " . $e->getMessage() . "\n";
    }

    //  AUTO_INCREMENT
    $max = DB::select("SELECT IFNULL(MAX(id),0) as m FROM $table")[0]->m;
    $next = $max + 1;
    try{
        DB::statement("ALTER TABLE $table AUTO_INCREMENT=$next");
        echo "Reset AUTO_INCREMENT for $table to $next\n";
    }catch(Exception $e){
        echo "Failed to reset AUTO_INCREMENT for $table: " . $e->getMessage() . "\n";
    }
}

try{
   DB::statement("DELETE t1 FROM sn_membres t1 INNER JOIN sn_membres t2 ON (LCASE(t1.prenom)=LCASE(t2.prenom) AND LCASE(t1.nom)=LCASE(t2.nom)) WHERE t1.id > t2.id");
    $max = DB::select("SELECT IFNULL(MAX(id),0) as m FROM sn_membres")[0]->m;
    DB::statement("ALTER TABLE sn_membres AUTO_INCREMENT=".($max+1));
    echo "Deduped sn_membres and reset AUTO_INCREMENT.\n";

    // Intervenants
    DB::statement("DELETE t1 FROM sn_intervenants t1 INNER JOIN sn_intervenants t2 ON (LCASE(t1.prenom)=LCASE(t2.prenom) AND LCASE(t1.nom)=LCASE(t2.nom)) WHERE t1.id > t2.id");
    $max = DB::select("SELECT IFNULL(MAX(id),0) as m FROM sn_intervenants")[0]->m;
    DB::statement("ALTER TABLE sn_intervenants AUTO_INCREMENT=".($max+1));
    echo "Deduped sn_intervenants and reset AUTO_INCREMENT.\n";

   DB::statement("DELETE t1 FROM sn_documents t1 INNER JOIN sn_documents t2 ON (LCASE(t1.libelle)=LCASE(t2.libelle) AND t1.categorie_id = t2.categorie_id) WHERE t1.id > t2.id");
    $max = DB::select("SELECT IFNULL(MAX(id),0) as m FROM sn_documents")[0]->m;
    DB::statement("ALTER TABLE sn_documents AUTO_INCREMENT=".($max+1));
    echo "Deduped sn_documents and reset AUTO_INCREMENT.\n";

    // Podcasts
     DB::statement("DELETE t1 FROM sn_podcasts t1 INNER JOIN sn_podcasts t2 ON (LCASE(t1.libelle)=LCASE(t2.libelle) AND t1.membre_id=t2.membre_id AND t1.categorie_id=t2.categorie_id) WHERE t1.id > t2.id");
    $max = DB::select("SELECT IFNULL(MAX(id),0) as m FROM sn_podcasts")[0]->m;
    DB::statement("ALTER TABLE sn_podcasts AUTO_INCREMENT=".($max+1));
    echo "Deduped sn_podcasts and reset AUTO_INCREMENT.\n";

    
    echo "\nChecking document files...\n";
    $docs = DB::select("SELECT id, libelle, fichier FROM sn_documents WHERE fichier IS NOT NULL");
    $missingDocs = [];
    foreach($docs as $d){
        if(!Storage::disk('public')->exists($d->fichier)){
            $missingDocs[] = [$d->id, $d->fichier];
        }
    }
    if(count($missingDocs)){
        echo "Missing document files:\n";
        foreach($missingDocs as $m){ echo "id={$m[0]} path={$m[1]}\n"; }
    } else { echo "All document files present in storage/app/public.\n"; }

   
    echo "\nChecking podcast files...\n";
    $pods = DB::select("SELECT id, libelle, fichier FROM sn_podcasts WHERE fichier IS NOT NULL");
    $missingPods = [];
    foreach($pods as $p){
        if(!Storage::disk('public')->exists($p->fichier)){
            $missingPods[] = [$p->id, $p->fichier];
        }
    }
    if(count($missingPods)){
        echo "Missing podcast files:\n";
        foreach($missingPods as $m){ echo "id={$m[0]} path={$m[1]}\n"; }
    } else { echo "All podcast files present in storage/app/public.\n"; }

    
    echo "\nRecreating storage link (php artisan storage:link)...\n";
   
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->call('storage:link');
    echo "storage:link executed.\n";

    echo "\nCleanup completed.\n";

} catch (Exception $e){
    echo "Error: " . $e->getMessage() . "\n";
}
