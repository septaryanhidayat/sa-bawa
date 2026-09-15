<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date:Y-m-d',
            'trials_servis_pendek' => 'array',
            'trials_servis_panjang' => 'array',
            'trials_lob' => 'array',
            'trials_smash' => 'array',
            'skor_servis_pendek' => 'integer',
            'skor_servis_panjang' => 'integer',
            'skor_lob' => 'integer',
            'skor_smash' => 'integer',
        ];
    }

    /**
     * Hitung norma Servis Pendek
     */
    public static function calculateNormaServisPendek(?int $score): string
    {
        if ($score === null) return '-';
        if ($score > 82.2) return 'Sangat Tinggi';
        if ($score >= 67) return 'Tinggi';
        if ($score >= 51) return 'Sedang';
        if ($score >= 36) return 'Kurang';
        return 'Sangat Kurang';
    }

    /**
     * Hitung norma Servis Panjang
     */
    public static function calculateNormaServisPanjang(?int $score): string
    {
        if ($score === null) return '-';
        if ($score > 60) return 'Sangat Tinggi';
        if ($score >= 47) return 'Tinggi';
        if ($score >= 34) return 'Sedang';
        if ($score >= 21) return 'Kurang';
        return 'Sangat Kurang';
    }

    /**
     * Hitung norma Pukulan Lob
     */
    public static function calculateNormaLob(?int $score): string
    {
        if ($score === null) return '-';
        if ($score > 91) return 'Sangat Tinggi';
        if ($score >= 80) return 'Tinggi';
        if ($score >= 70) return 'Sedang';
        if ($score >= 59) return 'Kurang';
        return 'Sangat Kurang';
    }

    /**
     * Hitung norma Pukulan Smash
     */
    public static function calculateNormaSmash(?int $score): string
    {
        if ($score === null) return '-';
        if ($score > 33) return 'Sangat Tinggi';
        if ($score >= 25) return 'Tinggi';
        if ($score >= 17) return 'Sedang';
        if ($score >= 8) return 'Kurang';
        return 'Sangat Kurang';
    }

    /**
     * Hitung Evaluasi Total Rata-rata
     */
    public static function calculateOverallCategory(?int $sp, ?int $sj, ?int $lob, ?int $smash): string
    {
        $nSp = self::calculateNormaServisPendek($sp);
        $nSj = self::calculateNormaServisPanjang($sj);
        $nLob = self::calculateNormaLob($lob);
        $nSmash = self::calculateNormaSmash($smash);

        $mapNorma = [
            'Sangat Tinggi' => 5,
            'Tinggi' => 4,
            'Sedang' => 3,
            'Kurang' => 2,
            'Sangat Kurang' => 1,
        ];

        $scores = array_values(array_filter([
            $mapNorma[$nSp] ?? 0,
            $mapNorma[$nSj] ?? 0,
            $mapNorma[$nLob] ?? 0,
            $mapNorma[$nSmash] ?? 0,
        ], fn($v) => $v > 0));

        if (empty($scores)) return '-';

        $avg = array_sum($scores) / count($scores);

        if ($avg >= 4.5) return 'Sangat Tinggi';
        if ($avg >= 3.5) return 'Tinggi';
        if ($avg >= 2.5) return 'Sedang';
        if ($avg >= 1.5) return 'Kurang';
        return 'Sangat Kurang';
    }
}
