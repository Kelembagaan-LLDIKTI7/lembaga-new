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
        Schema::create('lembaga_akreditasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('lembaga_nama');
            $table->string('lembaga_nama_singkat');
            $table->string('lembaga_status');
            $table->string('lembaga_logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga_akreditasis');
    }
};
