<?php
// app/Models/SalesTarget.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SalesTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_target',
        'current_achievement',
        'target_year',
        'target_month',
        'is_active'
    ];

    protected $casts = [
        'monthly_target' => 'decimal:2',
        'current_achievement' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Scope untuk mendapatkan target aktif bulan ini
    public function scopeCurrentMonth($query)
    {
        $now = Carbon::now();
        return $query->where('target_year', $now->year)
            ->where('target_month', $now->month)
            ->where('is_active', true);
    }

    // Scope untuk mendapatkan target berdasarkan tahun dan bulan
    public function scopeByYearMonth($query, $year, $month)
    {
        return $query->where('target_year', $year)
            ->where('target_month', $month)
            ->where('is_active', true);
    }

    // Accessor untuk mendapatkan persentase pencapaian
    public function getAchievementPercentageAttribute()
    {
        if ($this->monthly_target == 0) {
            return 0;
        }
        return round(($this->current_achievement / $this->monthly_target) * 100, 2);
    }

    // Accessor untuk mendapatkan sisa target
    public function getRemainingTargetAttribute()
    {
        return max(0, $this->monthly_target - $this->current_achievement);
    }

    // Method untuk mendapatkan atau membuat target bulan ini
    public static function getCurrentMonthTarget()
    {
        $now = Carbon::now();

        return self::firstOrCreate(
            [
                'target_year' => $now->year,
                'target_month' => $now->month,
                'is_active' => true
            ],
            [
                'monthly_target' => 0,
                'current_achievement' => 0
            ]
        );
    }

    // Method untuk update pencapaian
    public function updateAchievement($amount)
    {
        $this->current_achievement += $amount;
        $this->save();
        return $this;
    }
}
