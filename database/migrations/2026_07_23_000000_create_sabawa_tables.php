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
            $table->string('sekolah')->nullable();
            $table->date('tanggal');
            $table->string('penguji')->default('Silvi Aryanti, M.Pd.');

            // Servis Pendek (20x percobaan + akumulasi)
            $table->json('trials_servis_pendek')->nullable();
            $table->integer('skor_servis_pendek')->nullable();
            $table->string('norma_servis_pendek')->nullable();

            // Servis Panjang (20x percobaan + akumulasi)
            $table->json('trials_servis_panjang')->nullable();
            $table->integer('skor_servis_panjang')->nullable();
            $table->string('norma_servis_panjang')->nullable();

            // Pukulan Lob (20x percobaan + akumulasi)
            $table->json('trials_lob')->nullable();
            $table->integer('skor_lob')->nullable();
            $table->string('norma_lob')->nullable();

            // Pukulan Smash (20x percobaan + akumulasi)
            $table->json('trials_smash')->nullable();
            $table->integer('skor_smash')->nullable();
            $table->string('norma_smash')->nullable();

            // Evaluasi Total Rata-rata
            $table->string('evaluasi_total')->nullable();
            $table->timestamps();
        });

        // 2. Table Schools
        Schema::dropIfExists('schools');
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        // 3. Table Materis
        Schema::dropIfExists('materis');
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // overview, servis_pendek, servis_panjang, lob, smash, spesifikasi
            $table->string('photo')->nullable();
            $table->text('deskripsi');
            $table->text('petunjuk')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 4. Table Videos
        Schema::dropIfExists('videos');
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('youtube_url');
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->default('Teknik Dasar');
            $table->timestamps();
        });

        // 5. Table FAQs
        Schema::dropIfExists('faqs');
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->text('jawaban');
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 6. Table Researchers
        Schema::dropIfExists('researchers');
        Schema::create('researchers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('photo')->nullable();
            $table->boolean('is_leader')->default(false);
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 7. Table About Settings
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
        Schema::dropIfExists('schools');
        Schema::dropIfExists('materis');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('researchers');
        Schema::dropIfExists('about_settings');
    }
};
