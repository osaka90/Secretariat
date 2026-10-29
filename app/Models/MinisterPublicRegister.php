<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MinisterPublicRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequential_number',
        'registration_date',
        'registration_year',
        'result_or_notes',
        'category',
        'pdf_file',
    ];

    protected static function booted()
    {
        static::saving(function ($register) {
            if ($register->registration_date) {
                $register->registration_year = (int) \Carbon\Carbon::parse($register->registration_date)->format('Y');
            } elseif (!$register->registration_year) {
                $register->registration_year = (int) now()->format('Y');
            }

            if (empty($register->sequential_number)) {
                $register->sequential_number = static::getNextSequentialNumber($register->category, $register->registration_year);
            }
        });
    }

    public static function getNextSequentialNumber(?string $category, ?int $year = null): int
    {
        $year = $year ?: (int) now()->format('Y');

        $max = static::where('category', $category)
            ->where(function ($query) use ($year) {
                $query->where('registration_year', $year)
                      ->orWhere(function ($q) use ($year) {
                          $q->whereNull('registration_year')
                            ->whereYear('registration_date', $year);
                      });
            })
            ->max('sequential_number');

        return ($max ?? 0) + 1;
    }

    public function incoming(): HasOne
    {
        return $this->hasOne(MinisterPublicRegisterIncoming::class);
    }

    public function correspondences(): HasMany
    {
        return $this->hasMany(MinisterPublicRegisterCorrespondence::class);
    }
}
