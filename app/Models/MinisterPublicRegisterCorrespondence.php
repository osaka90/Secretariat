<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinisterPublicRegisterCorrespondence extends Model
{
    use HasFactory;

    protected $fillable = [
        'minister_public_register_id',
        'type',
        'date',
        'correspondent',
        'subject_or_reply',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function register(): BelongsTo
    {
        return $this->belongsTo(MinisterPublicRegister::class, 'minister_public_register_id');
    }
}
