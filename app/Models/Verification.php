<?php

namespace App\Models;

use Database\Factories\VerificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Verification extends Model
{
    /** @use HasFactory<VerificationFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_UNDER_REVIEW = 'under_review';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CORRECTION_REQUESTED = 'correction_requested';

    public const STATUS_BLOCKED = 'blocked';

    protected $fillable = [
        'user_id',
        'attempt_number',
        'document_type',
        'status',
        'verification_code',
        'submitted_at',
        'approved_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(VerificationFile::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(VerificationReview::class);
    }
}
