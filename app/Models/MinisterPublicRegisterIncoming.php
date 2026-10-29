<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinisterPublicRegisterIncoming extends Model
{
    use HasFactory;

    protected $fillable = [
        'minister_public_register_id',
        'letter_date',
        'letter_number',
        'arrival_date',
        'sender',
        'subject',
    ];

    protected $casts = [
        'letter_date' => 'date',
        'arrival_date' => 'date',
    ];

    public function register(): BelongsTo
    {
        return $this->belongsTo(MinisterPublicRegister::class, 'minister_public_register_id');
    }
}
