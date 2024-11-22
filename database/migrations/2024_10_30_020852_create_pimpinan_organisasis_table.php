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
        Schema::create('pimpinan_organisasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pimpinan_nama', 100);
            $table->string('pimpinan_email', 100);
            $table->date('pimpinan_tanggal');
            $table->string('pimpinan_sk');
            $table->string('pimpinan_sk_dokumen')->nullable();
            $table->enum('pimpinan_status', ['Aktif', 'Tidak Aktif']);
            $table->uuid('id_jabatan');
            $table->uuid('id_organization');
            $table->timestamps();

            $table->foreign('id_jabatan')->references('id')->on('jabatans');
            // $table->foreign('id_organization')->references('id')->on('organisasis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pimpinan_organisasi');
    }
};
