<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Permission;
use App\Models\Role;

// Create manage-presensi permission
$perm = Permission::firstOrCreate(['name' => 'manage-presensi']);

// Attach to Super Admin
$adminRole = Role::where('name', 'Super Admin')->first();
if ($adminRole) {
    $adminRole->permissions()->syncWithoutDetaching([$perm->id]);
    echo "Assigned manage-presensi to Super Admin.\n";
} else {
    echo "Super Admin role not found.\n";
}
