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
        Schema::table('jabatans', function (Blueprint $table) {
            $table->uuid('bentuk_pt')->nullable()->after('jabatan_organisasi');

            $table->foreign('bentuk_pt')->references('id')->on('bentuk_pts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropForeign(['bentuk_pt']);
            $table->dropColumn('bentuk_pt');
        });
    }
};
