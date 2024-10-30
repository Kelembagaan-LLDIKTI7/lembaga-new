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
        Schema::create('sk_kumhams', function (Blueprint $table) {
            $table->id();
            $table->string('kumham_nomor', 105);
            $table->longText('kumham_perihal');
            $table->date('kumham_tanggal');
            $table->string('kumham_dokumen', 100);
            $table->uuid('id_akta');
            $table->timestamps();

            $table->foreign('id_akta')->references('id')->on('aktas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_kumhams');
    }
};
