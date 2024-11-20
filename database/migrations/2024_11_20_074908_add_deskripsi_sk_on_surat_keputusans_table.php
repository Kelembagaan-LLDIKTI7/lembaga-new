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
        Schema::table('surat_keputusans', function (Blueprint $table) {
            $table->text('sk_deskripsi')->nullable()->after('sk_berakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keputusans', function (Blueprint $table) {
            $table->dropColumn('sk_deskripsi');
        });
    }
};
