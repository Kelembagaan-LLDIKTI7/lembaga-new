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
        Schema::create('program_studis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('prodi_kode')->nullable();
            $table->string('prodi_nama', 100);
            $table->string('prodi_jenjang');
            $table->integer('prodi_periode')->nullable();
            $table->string('prodi_active_status');
            $table->string('id_organization');
            $table->string('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_studis');
    }
};
