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
        // Drop old training_logs table if it exists
        Schema::dropIfExists('training_logs');

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_atlet');
            $table->date('tanggal');
            $table->string('jenis_tes'); // servis_pendek, servis_panjang, lob, smash
            $table->json('skor_percobaan'); // Array of 20 scores
            $table->integer('skor_total');
            $table->string('kategori'); // Sangat Baik, Baik, Cukup, Kurang, Sangat Kurang
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
