<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pastikan Aplikasi ada
        $appProfile = \App\Models\Application::firstOrCreate(['name' => 'Profile Website'], ['url' => 'https://mengeruda.id', 'description' => 'Profile Website']);
        $appTourism = \App\Models\Application::firstOrCreate(['name' => 'Tourism'], ['url' => 'https://tourism.mengeruda.id', 'description' => 'Pariwisata (Tourism)']);
        $appSurat = \App\Models\Application::firstOrCreate(['name' => 'E-Surat'], ['url' => 'https://e-surat.mengeruda.id', 'description' => 'E-Surat']);
        $appPresensi = \App\Models\Application::firstOrCreate(['name' => 'E-Presensi'], ['url' => 'https://e-presensi.mengeruda.id', 'description' => 'E-Presensi']);

        // 2. Kaitkan permission ke Aplikasi
        // Profile Permissions
        \App\Models\Permission::whereIn('name', ['manage-profile', 'manage-news', 'manage-demographics', 'manage-apb', 'manage-map', 'manage-org-chart', 'manage-gallery'])->update(['application_id' => $appProfile->id]);
        
        // Tourism Permissions
        \App\Models\Permission::whereIn('name', ['manage-tourism-profile', 'manage-umkm', 'manage-tourism-places', 'manage-activities', 'manage-tourism-news', 'manage-tourism-gallery'])->update(['application_id' => $appTourism->id]);

        // E-Surat Permissions
        \App\Models\Permission::whereIn('name', ['manage-surat', 'request-surat'])->update(['application_id' => $appSurat->id]);

        // E-Presensi Permissions (kalau ada)
        \App\Models\Permission::whereIn('name', ['manage-presensi'])->update(['application_id' => $appPresensi->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
