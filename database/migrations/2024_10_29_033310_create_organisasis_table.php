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
        Schema::create('organisasis', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('organisasi_nama');
            $table->string('organisasi_nama_singkat')->nullable();
            $table->string('organisasi_kode')->nullable();
            $table->longText('organisasi_email')->nullable();
            $table->longText('organisasi_telp')->nullable();
            $table->string('organisasi_kota')->nullable();
            $table->longText('organisasi_alamat')->nullable();
            $table->string('organisasi_website')->nullable();
            $table->string('organisasi_logo')->nullable();
            $table->string('organisasi_status')->nullable();
            $table->string('organisasi_type_id')->nullable();
            $table->string('organisasi_berubah_id')->nullable();
            $table->string('organisasi_berubah_status')->nullable();
            $table->string('parent_id')->nullable();
            $table->boolean('tampil')->nullable();
            $table->string('users_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisasis');
    }
};
