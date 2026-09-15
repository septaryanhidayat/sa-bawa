<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;
use App\Models\Assessment;
use App\Models\Faq;
use App\Models\Materi;
use App\Models\Researcher;
use App\Models\School;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get all application data for instant multi-device hydration
     */
    public function getAppData(): JsonResponse
    {
        $records = Assessment::orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'nama' => $r->nama,
                    'nim' => $r->nim,
                    'jenisKelamin' => $r->jenis_kelamin,
                    'kelas' => $r->kelas ?? '',
                    'sekolah' => $r->sekolah ?? '',
                    'tanggal' => $r->tanggal ? $r->tanggal->format('Y-m-d') : '',
                    'penguji' => $r->penguji ?? 'Silvi Aryanti, M.Pd.',
                    'trialsServisPendek' => $r->trials_servis_pendek ?? [],
                    'skorServisPendek' => $r->skor_servis_pendek,
                    'normaServisPendek' => $r->norma_servis_pendek ?? Assessment::calculateNormaServisPendek($r->skor_servis_pendek),
                    'trialsServisPanjang' => $r->trials_servis_panjang ?? [],
                    'skorServisPanjang' => $r->skor_servis_panjang,
                    'normaServisPanjang' => $r->norma_servis_panjang ?? Assessment::calculateNormaServisPanjang($r->skor_servis_panjang),
                    'trialsLob' => $r->trials_lob ?? [],
                    'skorLob' => $r->skor_lob,
                    'normaLob' => $r->norma_lob ?? Assessment::calculateNormaLob($r->skor_lob),
                    'trialsSmash' => $r->trials_smash ?? [],
                    'skorSmash' => $r->skor_smash,
                    'normaSmash' => $r->norma_smash ?? Assessment::calculateNormaSmash($r->skor_smash),
                    'evaluasiTotal' => $r->evaluasi_total ?? Assessment::calculateOverallCategory(
                        $r->skor_servis_pendek,
                        $r->skor_servis_panjang,
                        $r->skor_lob,
                        $r->skor_smash
                    ),
                ];
            });

        $schools = School::orderBy('id', 'asc')->get(['id', 'nama']);

        $materis = Materi::orderBy('urutan', 'asc')->get();

        $videos = Video::orderBy('id', 'asc')->get()->map(function ($v) {
            return [
                'id' => $v->id,
                'judul' => $v->judul,
                'kategori' => $v->kategori,
                'url' => $v->youtube_url,
                'deskripsi' => $v->deskripsi,
            ];
        });

        $faqs = Faq::orderBy('urutan', 'asc')->get()->map(function ($f) {
            return [
                'id' => $f->id,
                'q' => $f->pertanyaan,
                'a' => $f->jawaban,
            ];
        });

        $researchers = Researcher::orderBy('urutan', 'asc')->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'name' => $r->name,
                'role' => $r->role,
                'photo' => $r->photo ?? '/images/logo1.png',
                'isLeader' => (bool)$r->is_leader,
            ];
        });

        $appSettings = AboutSetting::getAllKeyValues();

        return response()->json([
            'records' => $records,
            'schools' => $schools,
            'materis' => $materis,
            'videos' => $videos,
            'faqs' => $faqs,
            'researchers' => $researchers,
            'appSettings' => $appSettings,
            'dbDriver' => config('database.default', 'mysql'),
        ]);
    }

    /**
     * Store or update an assessment record with 20 trial scores
     */
    public function storeAssessment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'jenisKelamin' => 'required|string|in:L,P',
            'kelas' => 'nullable|string|max:255',
            'sekolah' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'penguji' => 'nullable|string|max:255',
            'trialsServisPendek' => 'nullable|array',
            'trialsServisPanjang' => 'nullable|array',
            'trialsLob' => 'nullable|array',
            'trialsSmash' => 'nullable|array',
            'skorServisPendek' => 'nullable|numeric',
            'skorServisPanjang' => 'nullable|numeric',
            'skorLob' => 'nullable|numeric',
            'skorSmash' => 'nullable|numeric',
        ]);

        // Process Servis Pendek
        $spTrials = isset($data['trialsServisPendek']) ? array_map('intval', $data['trialsServisPendek']) : [];
        $spScore = !empty($spTrials) ? array_sum($spTrials) : (isset($data['skorServisPendek']) ? (int)$data['skorServisPendek'] : 0);
        $spNorma = Assessment::calculateNormaServisPendek($spScore);

        // Process Servis Panjang
        $sjTrials = isset($data['trialsServisPanjang']) ? array_map('intval', $data['trialsServisPanjang']) : [];
        $sjScore = !empty($sjTrials) ? array_sum($sjTrials) : (isset($data['skorServisPanjang']) ? (int)$data['skorServisPanjang'] : 0);
        $sjNorma = Assessment::calculateNormaServisPanjang($sjScore);

        // Process Lob
        $lobTrials = isset($data['trialsLob']) ? array_map('intval', $data['trialsLob']) : [];
        $lobScore = !empty($lobTrials) ? array_sum($lobTrials) : (isset($data['skorLob']) ? (int)$data['skorLob'] : 0);
        $lobNorma = Assessment::calculateNormaLob($lobScore);

        // Process Smash
        $smashTrials = isset($data['trialsSmash']) ? array_map('intval', $data['trialsSmash']) : [];
        $smashScore = !empty($smashTrials) ? array_sum($smashTrials) : (isset($data['skorSmash']) ? (int)$data['skorSmash'] : 0);
        $smashNorma = Assessment::calculateNormaSmash($smashScore);

        // Evaluasi Total
        $evalTotal = Assessment::calculateOverallCategory($spScore, $sjScore, $lobScore, $smashScore);

        $payload = [
            'nama' => $data['nama'],
            'nim' => $data['nim'],
            'jenis_kelamin' => $data['jenisKelamin'],
            'kelas' => $data['kelas'] ?? '',
            'sekolah' => $data['sekolah'] ?? '',
            'tanggal' => $data['tanggal'],
            'penguji' => $data['penguji'] ?? 'Silvi Aryanti, M.Pd.',
            'trials_servis_pendek' => $spTrials,
            'skor_servis_pendek' => $spScore,
            'norma_servis_pendek' => $spNorma,
            'trials_servis_panjang' => $sjTrials,
            'skor_servis_panjang' => $sjScore,
            'norma_servis_panjang' => $sjNorma,
            'trials_lob' => $lobTrials,
            'skor_lob' => $lobScore,
            'norma_lob' => $lobNorma,
            'trials_smash' => $smashTrials,
            'skor_smash' => $smashScore,
            'norma_smash' => $smashNorma,
            'evaluasi_total' => $evalTotal,
        ];

        if (!empty($data['id'])) {
            $assessment = Assessment::updateOrCreate(['id' => $data['id']], $payload);
        } else {
            $assessment = Assessment::create($payload);
        }

        return response()->json([
            'success' => true,
            'message' => 'Penilaian berhasil disimpan ke database MySQL.',
            'record' => [
                'id' => $assessment->id,
                'nama' => $assessment->nama,
                'nim' => $assessment->nim,
                'jenisKelamin' => $assessment->jenis_kelamin,
                'kelas' => $assessment->kelas ?? '',
                'sekolah' => $assessment->sekolah ?? '',
                'tanggal' => $assessment->tanggal ? $assessment->tanggal->format('Y-m-d') : '',
                'penguji' => $assessment->penguji,
                'trialsServisPendek' => $assessment->trials_servis_pendek ?? [],
                'skorServisPendek' => $assessment->skor_servis_pendek,
                'normaServisPendek' => $assessment->norma_servis_pendek,
                'trialsServisPanjang' => $assessment->trials_servis_panjang ?? [],
                'skorServisPanjang' => $assessment->skor_servis_panjang,
                'normaServisPanjang' => $assessment->norma_servis_panjang,
                'trialsLob' => $assessment->trials_lob ?? [],
                'skorLob' => $assessment->skor_lob,
                'normaLob' => $assessment->norma_lob,
                'trialsSmash' => $assessment->trials_smash ?? [],
                'skorSmash' => $assessment->skor_smash,
                'normaSmash' => $assessment->norma_smash,
                'evaluasiTotal' => $assessment->evaluasi_total,
            ],
        ]);
    }

    /**
     * Delete an assessment record
     */
    public function deleteAssessment($id): JsonResponse
    {
        $assessment = Assessment::find($id);
        if ($assessment) {
            $assessment->delete();
            return response()->json(['success' => true, 'message' => 'Data penilaian berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
    }

    /**
     * Save School
     */
    public function storeSchool(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'nama' => 'required|string|max:255',
        ]);

        if (!empty($data['id'])) {
            $school = School::updateOrCreate(['id' => $data['id']], ['nama' => $data['nama']]);
        } else {
            $school = School::create(['nama' => $data['nama']]);
        }

        return response()->json(['success' => true, 'school' => $school]);
    }

    /**
     * Delete School
     */
    public function deleteSchool($id): JsonResponse
    {
        School::destroy($id);
        return response()->json(['success' => true]);
    }

    /**
     * Save Materi
     */
    public function storeMateri(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'photo' => 'nullable|string',
            'deskripsi' => 'required|string',
            'petunjuk' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        if (!empty($data['id'])) {
            $materi = Materi::updateOrCreate(['id' => $data['id']], $data);
        } else {
            $materi = Materi::create($data);
        }

        return response()->json(['success' => true, 'materi' => $materi]);
    }

    /**
     * Delete Materi
     */
    public function deleteMateri($id): JsonResponse
    {
        Materi::destroy($id);
        return response()->json(['success' => true]);
    }

    /**
     * Save Video
     */
    public function storeVideo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'deskripsi' => 'nullable|string',
        ]);

        $payload = [
            'judul' => $data['judul'],
            'kategori' => $data['kategori'],
            'youtube_url' => $data['url'],
            'deskripsi' => $data['deskripsi'] ?? '',
        ];

        if (!empty($data['id'])) {
            $video = Video::updateOrCreate(['id' => $data['id']], $payload);
        } else {
            $video = Video::create($payload);
        }

        return response()->json([
            'success' => true,
            'video' => [
                'id' => $video->id,
                'judul' => $video->judul,
                'kategori' => $video->kategori,
                'url' => $video->youtube_url,
                'deskripsi' => $video->deskripsi,
            ],
        ]);
    }

    /**
     * Delete Video
     */
    public function deleteVideo($id): JsonResponse
    {
        Video::destroy($id);
        return response()->json(['success' => true]);
    }

    /**
     * Save FAQ
     */
    public function storeFaq(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'q' => 'required|string',
            'a' => 'required|string',
        ]);

        $payload = [
            'pertanyaan' => $data['q'],
            'jawaban' => $data['a'],
        ];

        if (!empty($data['id'])) {
            $faq = Faq::updateOrCreate(['id' => $data['id']], $payload);
        } else {
            $faq = Faq::create($payload);
        }

        return response()->json([
            'success' => true,
            'faq' => [
                'id' => $faq->id,
                'q' => $faq->pertanyaan,
                'a' => $faq->jawaban,
            ],
        ]);
    }

    /**
     * Delete FAQ
     */
    public function deleteFaq($id): JsonResponse
    {
        Faq::destroy($id);
        return response()->json(['success' => true]);
    }

    /**
     * Save Researcher
     */
    public function storeResearcher(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo' => 'nullable|string',
            'isLeader' => 'nullable|boolean',
        ]);

        $payload = [
            'name' => $data['name'],
            'role' => $data['role'],
            'photo' => $data['photo'] ?? '/images/logo1.png',
            'is_leader' => !empty($data['isLeader']),
        ];

        if (!empty($data['id'])) {
            $researcher = Researcher::updateOrCreate(['id' => $data['id']], $payload);
        } else {
            $researcher = Researcher::create($payload);
        }

        return response()->json([
            'success' => true,
            'researcher' => [
                'id' => $researcher->id,
                'name' => $researcher->name,
                'role' => $researcher->role,
                'photo' => $researcher->photo,
                'isLeader' => (bool)$researcher->is_leader,
            ],
        ]);
    }

    /**
     * Delete Researcher
     */
    public function deleteResearcher($id): JsonResponse
    {
        Researcher::destroy($id);
        return response()->json(['success' => true]);
    }

    /**
     * Update App Settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $settings = $request->all();
        foreach ($settings as $key => $value) {
            AboutSetting::setKeyValue($key, is_string($value) ? $value : json_encode($value));
        }

        return response()->json([
            'success' => true,
            'appSettings' => AboutSetting::getAllKeyValues(),
        ]);
    }
}
