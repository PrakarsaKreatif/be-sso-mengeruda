<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$app = \App\Models\Application::firstOrCreate(['name' => 'E-Presensi'], ['url' => 'http://localhost:5178/auth-receiver', 'description' => 'Aplikasi Presensi Warga']);
\App\Models\Role::where('name', 'Super Admin')->first()->applications()->syncWithoutDetaching([$app->id]);
\App\Models\Role::where('name', 'warga')->first()->applications()->syncWithoutDetaching([$app->id]);

echo "Seeded E-Presensi successfully!\n";
