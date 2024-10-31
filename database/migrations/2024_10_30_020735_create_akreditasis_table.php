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
        Schema::create('akreditasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('akreditasi_sk', 105)->nullable();
            $table->date('akreditasi_tgl_awal')->nullable();
            $table->date('akreditasi_tgl_akhir')->nullable();
            $table->enum('akreditasi_status', ['Berlaku', 'Tidak Berlaku', 'Dicabut'])->nullable();
            $table->string('akreditasi_dokumen', 100)->nullable();
            $table->uuid('id_organization')->nullable();
            $table->uuid('id_peringkat_akreditasi');
            $table->uuid('id_lembaga_akreditasi')->nullable();
            $table->uuid('id_prodi')->nullable();
            $table->uuid('id_user')->nullable();
            $table->timestamps();

            $table->foreign('id_organization')->references('id')->on('organisasis');
            $table->foreign('id_peringkat_akreditasi')->references('id')->on('peringkat_akreditasis');
            $table->foreign('id_lembaga_akreditasi')->references('id')->on('lembaga_akreditasis');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akreditasis');
    }
};
