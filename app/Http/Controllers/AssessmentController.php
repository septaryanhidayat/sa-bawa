<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $assessments = Assessment::orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Agregasi statistik
        $totalPenilaian = $assessments->count();
        $skorRataRata = $totalPenilaian > 0 ? round($assessments->avg('skor_total'), 1) : 0;

        // Distribusi per jenis tes
        $distribusiJenisTes = Assessment::select('jenis_tes', DB::raw('count(*) as total'), DB::raw('avg(skor_total) as rata_rata'))
            ->groupBy('jenis_tes')
            ->get()
            ->keyBy('jenis_tes');

        // Distribusi kategori
        $distribusiKategori = Assessment::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->get()
            ->pluck('total', 'kategori');

        return view('welcome', compact(
            'assessments',
            'totalPenilaian',
            'skorRataRata',
            'distribusiJenisTes',
            'distribusiKategori'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_atlet' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'jenis_tes' => 'required|string|in:servis_pendek,servis_panjang,lob,smash',
            'skor' => 'required|array|size:20',
            'skor.*' => 'required|integer|min:0|max:5',
            'catatan' => 'nullable|string',
        ], [
            'nama_atlet.required' => 'Nama atlet wajib diisi.',
            'tanggal.required' => 'Tanggal penilaian wajib diisi.',
            'jenis_tes.required' => 'Jenis tes wajib dipilih.',
            'jenis_tes.in' => 'Jenis tes tidak valid.',
            'skor.required' => 'Skor percobaan wajib diisi.',
            'skor.size' => 'Harus ada tepat 20 skor percobaan.',
            'skor.*.integer' => 'Setiap skor harus berupa angka.',
            'skor.*.min' => 'Skor minimal adalah 0.',
            'skor.*.max' => 'Skor maksimal adalah 5.',
        ]);

        $skorPercobaan = array_map('intval', $validated['skor']);
        $skorTotal = array_sum($skorPercobaan);
        $kategori = Assessment::hitungKategori($skorTotal);

        Assessment::create([
            'nama_atlet' => $validated['nama_atlet'],
            'tanggal' => $validated['tanggal'],
            'jenis_tes' => $validated['jenis_tes'],
            'skor_percobaan' => $skorPercobaan,
            'skor_total' => $skorTotal,
            'kategori' => $kategori,
            'catatan' => $validated['catatan'],
        ]);

        return redirect()->route('home', ['tab' => 'riwayat'])->with('success', 'Penilaian berhasil disimpan! Kategori: ' . $kategori);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $assessment = Assessment::findOrFail($id);
        return response()->json($assessment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $log = Assessment::findOrFail($id);
        $log->delete();

        return redirect()->route('home', ['tab' => 'riwayat'])->with('success', 'Data penilaian berhasil dihapus!');
    }
}
