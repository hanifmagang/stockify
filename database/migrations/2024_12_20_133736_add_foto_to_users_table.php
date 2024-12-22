<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaultPath = 'public/images/profile';
        $defaultFile = $defaultPath . '/ppdepresi.png';

        if (!Storage::exists($defaultPath)) {
            Storage::makeDirectory($defaultPath);
        }

        // Pastikan file default tersedia
        if (!Storage::exists($defaultFile)) {
            Storage::put($defaultFile, file_get_contents(public_path('images/profile/ppdepresi.png')));
        }

        // Update struktur tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto')->default('ppdepresi.png');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto')->default(null)->change();
        });
    }
};
