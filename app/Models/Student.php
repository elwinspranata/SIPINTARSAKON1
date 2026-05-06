<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'nisn', 'class_id', 'gender'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function behaviorRecords()
    {
        return $this->hasMany(BehaviorRecord::class);
    }

    /**
     * Total poin penyakit (pelanggaran).
     */
    public function getViolationPointsAttribute(): int
    {
        return $this->behaviorRecords->whereNotNull('violation_type_id')
            ->sum(fn($r) => $r->violationType->points ?? 0);
    }

    /**
     * Total poin vitamin (prestasi).
     */
    public function getVitaminPointsAttribute(): int
    {
        return $this->behaviorRecords->whereNotNull('vitamin_type_id')
            ->sum(fn($r) => $r->vitaminType->points ?? 0);
    }

    /**
     * Poin bersih (Vitamin - Penyakit). 
     * Positif (+) = kelebihan vitamin (SEHAT).
     * Negatif (-) = lebih banyak penyakit.
     */
    public function getNetPointsAttribute(): int
    {
        return $this->vitamin_points - $this->violation_points;
    }

    /**
     * Status poin siswa: label, color, bg, sp (surat peringatan), zone.
     *
     * > 0   → SEHAT   (kelebihan vitamin)
     * 0 s/d -20  → AMAN
     * -21 s/d -50 → BAIK
     * -51 s/d -100→ WASPADA
     * < -100 → KRITIS
     */
    public function getPointStatusAttribute(): array
    {
        $points = $this->net_points;

        // Jika poin lebih besar dari 0, berarti banyak Vitamin (+)
        if ($points > 0) {
            return ['label' => 'AMAN', 'color' => '#06b6d4', 'bg' => '#ecfeff', 'sp' => 'Normal', 'zone' => 'ZONA AMAN — SEHAT'];
        }

        // Konversi poin negatif menjadi positif untuk dicek dengan batas Penyakit
        $penyakit = -$points;

        if ($penyakit <= 20) {
            return ['label' => 'AMAN', 'color' => '#10b981', 'bg' => '#ecfdf5', 'sp' => 'Normal', 'zone' => 'ZONA AMAN'];
        }
        if ($penyakit <= 50) {
            return ['label' => 'BAIK', 'color' => '#3b82f6', 'bg' => '#eff6ff', 'sp' => 'SP I', 'zone' => 'ZONA BAIK'];
        }
        if ($penyakit <= 100) {
            return ['label' => 'WASPADA', 'color' => '#f59e0b', 'bg' => '#fffbeb', 'sp' => 'SP II', 'zone' => 'ZONA WASPADA'];
        }

        return ['label' => 'KRITIS', 'color' => '#ef4444', 'bg' => '#fef2f2', 'sp' => 'SP III', 'zone' => 'ZONA KRITIS'];
    }
}
