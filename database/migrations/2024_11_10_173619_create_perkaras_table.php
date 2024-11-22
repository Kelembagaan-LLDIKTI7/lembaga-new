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
        Schema::create('perkaras', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('no_perkara')->nullable();
            $table->string('deskripsi_kejadian');
            $table->date('tanggal_kejadian');
            $table->string('bukti_foto')->nullable();
            $table->string('status');
            $table->string('id_organization')->nullable();
            $table->string('id_prodi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkaras');
    }
};
