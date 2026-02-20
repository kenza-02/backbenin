<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Finding pivot table names...\n";
$tables = DB::select('SHOW TABLES');
foreach($tables as $t){
  $key = 'Tables_in_mobile_citizen_sn';
  if(isset($t->$key)){
    $name = $t->$key;
    if(strpos($name, 'evenement') !== false && strpos($name, 'intervenant') !== false){
      echo "Found: " . $name . "\n";
    }
  }
}
