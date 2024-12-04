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
        Schema::create('histori_pts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_organization')->nullable();
            $table->string('parent_id_lama')->nullable();
            $table->string('parent_id_baru')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histori_pts');
    }
};
