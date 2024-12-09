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
        Schema::create('history_program_studis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_prodi');
            $table->string('prodi_kode')->nullable();
            $table->string('prodi_nama', 100);
            $table->string('prodi_jenjang');
            $table->bigInteger('prodi_periode')->nullable();
            $table->string('prodi_active_status');

            $table->string('id_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_program_studis');
    }
};
