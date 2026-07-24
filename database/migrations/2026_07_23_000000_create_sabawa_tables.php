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
        // 1. Table Assessments
        Schema::dropIfExists('assessments');
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nim');
            $table->string('jenis_kelamin')->default('L');
            $table->string('kelas')->nullable();
            $table->date('tanggal');
            $table->string('penguji')->default('Silvi Aryanti, M.Pd.');
            $table->integer('skor_servis_pendek')->nullable();
            $table->string('norma_servis_pendek')->nullable();
            $table->integer('skor_servis_panjang')->nullable();
            $table->string('norma_servis_panjang')->nullable();
            $table->integer('skor_lob')->nullable();
            $table->string('norma_lob')->nullable();
            $table->integer('skor_smash')->nullable();
            $table->string('norma_smash')->nullable();
            $table->string('evaluasi_total')->nullable();
            $table->timestamps();
        });

        // 2. Table Materis
        Schema::dropIfExists('materis');
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // overview, servis_pendek, servis_panjang, lob, smash, spesifikasi
            $table->text('deskripsi');
            $table->text('petunjuk')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 3. Table Videos
        Schema::dropIfExists('videos');
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('youtube_url');
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->default('Teknik Dasar');
            $table->timestamps();
        });

        // 4. Table FAQs
        Schema::dropIfExists('faqs');
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->text('jawaban');
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 5. Table About Settings & Team
        Schema::dropIfExists('about_settings');
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('materis');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('about_settings');
    }
};
