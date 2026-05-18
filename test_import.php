<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $import = new App\Imports\BarangImport();
    Maatwebsite\Excel\Facades\Excel::import($import, 'public/template_master_barang.xlsx');
    echo "OK";
} catch (\Throwable $e) {
    echo "ERROR: " . get_class($e) . "\n";
    echo $e->getMessage() . "\n";
}
