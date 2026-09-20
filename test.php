<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$roles = \App\Models\Role::pluck('name')->toArray();
print_r($roles);
