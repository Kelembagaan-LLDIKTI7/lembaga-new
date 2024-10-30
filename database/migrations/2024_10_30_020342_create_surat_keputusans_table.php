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
        Schema::create('surat_keputusans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sk_nomor', 105);
            $table->date('sk_tanggal');
            $table->date('sk_berakhir')->nullable();
            $table->string('sk_dokumen', 100);
            $table->uuid('id_jenis_surat_keputusan')->nullable();
            $table->uuid('id_organization')->nullable();
            $table->string('id_prodi')->nullable();
            $table->string('tahun_terbit')->nullable();
            $table->timestamps();

            $table->foreign('id_jenis_surat_keputusan')->references('id')->on('jenis_surat_keputusans');
            $table->foreign('id_organization')->references('id')->on('organisasis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keputusans');
    }
};
