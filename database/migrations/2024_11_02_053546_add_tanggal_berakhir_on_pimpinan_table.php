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
        Schema::table('pimpinan_organisasis', function (Blueprint $table) {
            $table->date('pimpinan_tanggal_berakhir')->after('pimpinan_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pimpinan_organisasis', function (Blueprint $table) {
            $table->dropColumn('pimpinan_tanggal_berakhir');
        });
    }
};
