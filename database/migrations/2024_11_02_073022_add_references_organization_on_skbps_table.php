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
        Schema::table('skbps', function (Blueprint $table) {
            $table->foreign('id_organization')->references('id')->on('organisasis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skbps', function (Blueprint $table) {
            $table->dropForeign(['id_organization']);
        });
    }
};
