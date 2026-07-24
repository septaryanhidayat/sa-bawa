<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'skor_percobaan' => 'array',
            'skor_total' => 'integer',
        ];
    }

    /**
     * Get label nama jenis tes yang readable
     */
    public function getLabelJenisAttribute(): string
    {
        return match ($this->jenis_tes) {
            'servis_pendek' => 'Servis Pendek',
            'servis_panjang' => 'Servis Panjang',
            'lob' => 'Lob',
            'smash' => 'Smash',
            default => $this->jenis_tes,
        };
    }

    /**
     * Hitung kategori berdasarkan skor total
     * Skor maksimal = 100 (20 percobaan × 5 skor maks)
     */
    public static function hitungKategori(int $skorTotal): string
    {
        return match (true) {
            $skorTotal >= 85 => 'Sangat Baik',
            $skorTotal >= 70 => 'Baik',
            $skorTotal >= 55 => 'Cukup',
            $skorTotal >= 40 => 'Kurang',
            default => 'Sangat Kurang',
        };
    }

    /**
     * Warna badge kategori
     */
    public function getWarnaKategoriAttribute(): string
    {
        return match ($this->kategori) {
            'Sangat Baik' => 'emerald',
            'Baik' => 'blue',
            'Cukup' => 'amber',
            'Kurang' => 'orange',
            'Sangat Kurang' => 'red',
            default => 'slate',
        };
    }
}
