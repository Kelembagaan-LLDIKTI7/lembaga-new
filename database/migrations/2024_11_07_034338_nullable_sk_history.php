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
        Schema::table('history_program_studis', function (Blueprint $table) {
            $table->string('sk_nomor')->nullable()->change();
            $table->date('sk_tanggal')->nullable()->change();
            $table->string('sk_dokumen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_program_studis', function (Blueprint $table) {
            $table->string('sk_nomor')->nullable(false)->change();
            $table->date('sk_tanggal')->nullable(false)->change();
            $table->string('sk_dokumen')->nullable(false)->change();
        });
    }
};
