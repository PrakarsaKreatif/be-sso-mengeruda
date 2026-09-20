<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$wargaRole = \App\Models\Role::where('name', 'warga')->first();
if ($wargaRole) {
    echo "Warga applications: " . $wargaRole->applications->pluck('name')->toJson() . "\n";
} else {
    echo "Warga role not found\n";
}
