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
        Schema::create('aktas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('akta_nomor');
            $table->date('akta_tanggal');
            $table->string('akta_nama_notaris')->nullable();
            $table->string('akta_kota_notaris')->nullable();
            $table->enum('akta_status', ['Aktif', 'Tidak Aktif']);
            $table->enum('akta_jenis', ['Pendirian', 'Perubahan']);
            $table->string('akta_dokumen')->nullable();
            $table->char('id_organisasi')->nullable();
            $table->uuid('id_user');
            $table->char('akta_referensi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktas');
    }
};
