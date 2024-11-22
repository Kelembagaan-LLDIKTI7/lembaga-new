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
            $table->string('akta_nomor', 45);
            $table->date('akta_tanggal');
            $table->string('akta_nama_notaris', 150);
            $table->string('akta_kota_notaris', 150);
            $table->enum('akta_status', ['Aktif', 'Tidak Aktif']);
            $table->enum('akta_jenis', ['Pendirian', 'Perubahan']);
            $table->string('akta_dokumen', 100)->nullable();
            $table->uuid('id_organization');
            $table->uuid('id_user');
            $table->uuid('akta_referensi')->nullable();
            $table->timestamps();

            $table->foreign('id_organization')->references('id')->on('organisasis');
            $table->foreign('id_user')->references('id')->on('users');
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
