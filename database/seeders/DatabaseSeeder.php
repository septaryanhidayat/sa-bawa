<?php

namespace Database\Seeders;

use App\Models\AboutSetting;
use App\Models\Assessment;
use App\Models\Faq;
use App\Models\Materi;
use App\Models\Researcher;
use App\Models\School;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Schools
        School::truncate();
        School::insert([
            ['nama' => 'SMP 4', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'SMP 5', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Researchers
        Researcher::truncate();
        Researcher::insert([
            [
                'name' => 'Silvi Aryanti, M.Pd.',
                'role' => 'Ketua Peneliti • NIDN 0021079101',
                'photo' => 'images/Picture1.png',
                'is_leader' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Destriana, M.Pd.',
                'role' => 'Anggota 1 • NIDN 0001128905',
                'photo' => 'images/Picture2.png',
                'is_leader' => false,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fitri Agung Nanda, M.Pd.',
                'role' => 'Anggota 2 • NIDN 0016039408',
                'photo' => 'images/Picture3.png',
                'is_leader' => false,
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Soleh Solahuddin, M.Pd.',
                'role' => 'Anggota 3 • NIDK 8898323419',
                'photo' => 'images/Picture4.png',
                'is_leader' => false,
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Materis
        Materi::truncate();
        Materi::insert([
            [
                'judul' => 'Overview Instrumen Penilaian Bulutangkis',
                'kategori' => 'Umum',
                'photo' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Instrumen Penilaian Bulutangkis ini dikembangkan oleh Silvi Aryanti, M.Pd. dan tim mengacu pada metode penelitian R&D (Sugiyono, 2009: 148 & Suharsimi Arikunto, 2013: 193). Mengukur 4 keterampilan utama: Servis Pendek, Servis Panjang, Pukulan Lob, dan Pukulan Smash.',
                'petunjuk' => 'Setiap teste melakukan 20 kali percobaan pada masing-masing item tes. Penguji mencatat skor per percobaan pada form.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => '1. Servis Pendek (Short Serve Test)',
                'kategori' => 'Servis Pendek',
                'photo' => 'https://images.unsplash.com/photo-1521537634581-0ddea2efe2b6?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Tes Servis Pendek (Manurung 2018) bertujuan mengukur akurasi dan ketepatan servis backhand/forehand tipis di atas net menuju area sasaran bernilai 5, 4, 3, 2, dan 1.',
                'petunjuk' => 'Subjek berdiri di petak servis dan melakukan 20 kali servis pendek berurutan. Bola yang menyangkut di net mendapat skor 0.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => '2. Servis Panjang (Long Serve Test)',
                'kategori' => 'Servis Panjang',
                'photo' => 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Tes Servis Panjang (Bayu Tri Kurniawan 2018:54) mengukur kemampuan melambungkan shuttlecock jauh dan tinggi menuju garis belakang batas lapangan lawan.',
                'petunjuk' => 'Subjek diberi kesempatan 20 kali melakukan servis melambung tinggi. Nilai dicatat sesuai angka pada target garis belakang.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => '3. Tes Pukulan Lob (High Clear Test)',
                'kategori' => 'Tes Lob',
                'photo' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Pukulan Lob mengukur kemampuan mengembalikan shuttlecock melambung tinggi melampaui tali batas setinggi 155 cm (8 kaki) menuju lapangan belakang lawan.',
                'petunjuk' => 'Shuttlecock diumpan oleh penguji, subjek melakukan pukulan lob 20 kali. Bola yang melewati bawah tali dianggap tidak sah (skor 0).',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => '4. Tes Pukulan Smash (Smash Test)',
                'kategori' => 'Tes Smash',
                'photo' => 'https://images.unsplash.com/photo-1613918108466-292b78a8ef95?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Tes Smash mengukur kecepatan, ketepatan, dan menukiknya pukulan smash forehand ke area sasaran bernilai pada lapangan lawan.',
                'petunjuk' => 'Subjek menerima 20 umpan lob tinggi dari penguji dan wajib melakukan smash keras menukik ke area target lawan.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 4. Videos
        Video::truncate();
        Video::insert([
            [
                'judul' => 'Teknik Servis Pendek Backhand',
                'kategori' => 'Servis Pendek',
                'youtube_url' => 'https://www.youtube.com/embed/5D2Y8JtK11A',
                'deskripsi' => 'Panduan rincian gerakan dan posisi pegangan raket untuk servis pendek.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Teknik Servis Panjang Forehand',
                'kategori' => 'Servis Panjang',
                'youtube_url' => 'https://www.youtube.com/embed/sLd2vHnQO9k',
                'deskripsi' => 'Panduan servis melambung tinggi jauh ke belakang lapangan lawan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. FAQs
        Faq::truncate();
        Faq::insert([
            [
                'pertanyaan' => "Apa itu aplikasi SA'BAWA?",
                'jawaban' => "SA'BAWA (Silvi Aryanti' Badminton Assessment WebApp) adalah aplikasi web yang dikembangkan oleh tim Silvi Aryanti, M.Pd. untuk mengukur dan mengonversi hasil tes 4 teknik dasar bulutangkis secara otomatis berdasarkan standar norma ilmiah.",
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pertanyaan' => 'Siapa saja tim peneliti pengembang instrumen ini?',
                'jawaban' => 'Ketua: Silvi Aryanti, M.Pd. (NIDN 0021079101), Anggota: 1. Destriana, M.Pd., 2. Fitri Agung Nanda, M.Pd., 3. Soleh Solahuddin, M.Pd.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pertanyaan' => 'Berapa kali kesempatan servis/pukulan yang diberikan?',
                'jawaban' => 'Setiap teste mendapatkan 20 kali kesempatan percobaan untuk masing-masing tes (Servis Pendek, Servis Panjang, Lob, dan Smash).',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pertanyaan' => 'Bagaimana cara penilaian Servis Pendek & Panjang?',
                'jawaban' => 'Shuttlecock diarahkan ke zona sasaran bernilai 5, 4, 3, 2, dan 1. Skor dikonversi ke norma nilai otomatis.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pertanyaan' => 'Bagaimana cara login Admin?',
                'jawaban' => 'Silakan login dengan akun admin.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 6. About Settings
        AboutSetting::truncate();
        $settings = [
            'appName' => "SA'BAWA",
            'appSubtitle' => "Silvi Aryanti' Badminton Assessment WebApp",
            'appLogo' => '/images/logo1.png',
            'heroTitle' => 'Pengembangan Instrumen Penilaian Teknik Dasar Bulutangkis',
            'heroDescription' => "Aplikasi SA'BAWA (Silvi Aryanti' Badminton Assessment WebApp) dirancang khusus untuk mempermudah penilaian dan pengolahan skor tes 4 teknik dasar bulutangkis secara otomatis berdasarkan standar norma ilmiah.",
        ];
        foreach ($settings as $k => $v) {
            AboutSetting::create(['key' => $k, 'value' => $v]);
        }

        // 7. Assessments (Sample data with 20 individual trial scores each)
        Assessment::truncate();

        // Sample 1: Ahmad Rizky Pratama
        $sp1 = [4, 5, 4, 5, 4, 4, 5, 4, 4, 5, 4, 4, 5, 4, 4, 4, 5, 4, 5, 3]; // sum = 85
        $sj1 = [3, 3, 4, 3, 3, 3, 3, 3, 3, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3]; // sum = 62
        $lob1 = [5, 5, 5, 4, 5, 5, 4, 5, 5, 4, 5, 5, 4, 5, 5, 4, 5, 5, 4, 4]; // sum = 92
        $smash1 = [2, 2, 2, 2, 1, 2, 2, 2, 2, 1, 2, 2, 2, 2, 1, 2, 2, 2, 2, 1]; // sum = 35

        Assessment::create([
            'nama' => 'Ahmad Rizky Pratama',
            'nim' => '06121001001',
            'jenis_kelamin' => 'L',
            'kelas' => 'Palembang A 2024',
            'sekolah' => 'SMP 4',
            'tanggal' => '2026-07-20',
            'penguji' => 'Silvi Aryanti, M.Pd.',
            'trials_servis_pendek' => $sp1,
            'skor_servis_pendek' => array_sum($sp1),
            'norma_servis_pendek' => Assessment::calculateNormaServisPendek(array_sum($sp1)),
            'trials_servis_panjang' => $sj1,
            'skor_servis_panjang' => array_sum($sj1),
            'norma_servis_panjang' => Assessment::calculateNormaServisPanjang(array_sum($sj1)),
            'trials_lob' => $lob1,
            'skor_lob' => array_sum($lob1),
            'norma_lob' => Assessment::calculateNormaLob(array_sum($lob1)),
            'trials_smash' => $smash1,
            'skor_smash' => array_sum($smash1),
            'norma_smash' => Assessment::calculateNormaSmash(array_sum($smash1)),
            'evaluasi_total' => Assessment::calculateOverallCategory(array_sum($sp1), array_sum($sj1), array_sum($lob1), array_sum($smash1)),
        ]);

        // Sample 2: Siti Nurhaliza
        $sp2 = [4, 4, 3, 4, 4, 3, 4, 4, 3, 4, 4, 3, 4, 4, 3, 4, 4, 3, 4, 2]; // sum = 72
        $sj2 = [2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 3, 2]; // sum = 50
        $lob2 = [4, 4, 4, 5, 4, 4, 4, 5, 4, 4, 4, 5, 4, 4, 4, 5, 4, 4, 4, 3]; // sum = 83
        $smash2 = [1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 0]; // sum = 28

        Assessment::create([
            'nama' => 'Siti Nurhaliza',
            'nim' => '06121001015',
            'jenis_kelamin' => 'P',
            'kelas' => 'Indralaya B 2024',
            'sekolah' => 'SMP 5',
            'tanggal' => '2026-07-21',
            'penguji' => 'Silvi Aryanti, M.Pd.',
            'trials_servis_pendek' => $sp2,
            'skor_servis_pendek' => array_sum($sp2),
            'norma_servis_pendek' => Assessment::calculateNormaServisPendek(array_sum($sp2)),
            'trials_servis_panjang' => $sj2,
            'skor_servis_panjang' => array_sum($sj2),
            'norma_servis_panjang' => Assessment::calculateNormaServisPanjang(array_sum($sj2)),
            'trials_lob' => $lob2,
            'skor_lob' => array_sum($lob2),
            'norma_lob' => Assessment::calculateNormaLob(array_sum($lob2)),
            'trials_smash' => $smash2,
            'skor_smash' => array_sum($smash2),
            'norma_smash' => Assessment::calculateNormaSmash(array_sum($smash2)),
            'evaluasi_total' => Assessment::calculateOverallCategory(array_sum($sp2), array_sum($sj2), array_sum($lob2), array_sum($smash2)),
        ]);

        // Sample 3: Budi Santoso
        $sp3 = [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2]; // sum = 58
        $sj3 = [2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2]; // sum = 40
        $lob3 = [4, 4, 4, 4, 4, 4, 3, 4, 4, 4, 4, 4, 3, 4, 4, 4, 4, 4, 3, 3]; // sum = 75
        $smash3 = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1]; // sum = 20

        Assessment::create([
            'nama' => 'Budi Santoso',
            'nim' => '06121001024',
            'jenis_kelamin' => 'L',
            'kelas' => 'Palembang A 2024',
            'sekolah' => 'SMP 4',
            'tanggal' => '2026-07-22',
            'penguji' => 'Destriana, M.Pd.',
            'trials_servis_pendek' => $sp3,
            'skor_servis_pendek' => array_sum($sp3),
            'norma_servis_pendek' => Assessment::calculateNormaServisPendek(array_sum($sp3)),
            'trials_servis_panjang' => $sj3,
            'skor_servis_panjang' => array_sum($sj3),
            'norma_servis_panjang' => Assessment::calculateNormaServisPanjang(array_sum($sj3)),
            'trials_lob' => $lob3,
            'skor_lob' => array_sum($lob3),
            'norma_lob' => Assessment::calculateNormaLob(array_sum($lob3)),
            'trials_smash' => $smash3,
            'skor_smash' => array_sum($smash3),
            'norma_smash' => Assessment::calculateNormaSmash(array_sum($smash3)),
            'evaluasi_total' => Assessment::calculateOverallCategory(array_sum($sp3), array_sum($sj3), array_sum($lob3), array_sum($smash3)),
        ]);
    }
}
