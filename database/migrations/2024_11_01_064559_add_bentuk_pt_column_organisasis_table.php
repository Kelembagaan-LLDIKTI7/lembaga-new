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
        Schema::table('organisasis', function (Blueprint $table) {
            $table->string('organisasi_bentuk_pt')->nullable()->after('organisasi_type_id');

            $table->foreign('organisasi_bentuk_pt')->references('id')->on('bentuk_pts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organisasis', function (Blueprint $table) {
            $table->dropForeign(['organisasi_bentuk_pt']);
            $table->dropColumn('organisasi_bentuk_pt');
        });
    }
};
