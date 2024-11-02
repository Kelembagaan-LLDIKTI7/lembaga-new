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
        Schema::create('skbps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor');
            $table->date('tanggal');
            $table->string('dokumen');
            $table->string('jenis');
            $table->uuid('id_organization');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skbps');
    }
};
