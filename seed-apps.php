<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Application;
use App\Models\Role;

// Create applications
$profileApp = Application::firstOrCreate(
    ['name' => 'Profile Website'],
    ['url' => 'http://localhost:5174/auth-receiver', 'description' => 'Profile Website']
);

$tourismApp = Application::firstOrCreate(
    ['name' => 'Tourism'],
    ['url' => 'http://localhost:5175/auth-receiver', 'description' => 'Tourism Website']
);

$eSuratApp = Application::firstOrCreate(
    ['name' => 'E-Surat'],
    ['url' => 'http://localhost:5177/auth-receiver', 'description' => 'E-Surat Website']
);

// Link all applications to Super Admin
$superAdminRole = Role::where('name', 'Super Admin')->first();
if ($superAdminRole) {
    $superAdminRole->applications()->syncWithoutDetaching([$profileApp->id, $tourismApp->id, $eSuratApp->id]);
}

// Link E-Surat to warga
$wargaRole = Role::where('name', 'warga')->first();
if ($wargaRole) {
    $wargaRole->applications()->syncWithoutDetaching([$eSuratApp->id]);
}

echo "Seeded successfully!\n";
