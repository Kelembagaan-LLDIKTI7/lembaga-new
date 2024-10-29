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
        Schema::create('peringkat_akreditasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('peringkat_nama');
            $table->string('peringkat_logo');
            $table->string('peringkat_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peringkat_akreditasis');
    }
};
