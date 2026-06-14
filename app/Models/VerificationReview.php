<?php

namespace App\Models;

use Database\Factories\VerificationReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationReview extends Model
{
    /** @use HasFactory<VerificationReviewFactory> */
    use HasFactory;

    public const DECISION_APPROVED = 'approved';

    public const DECISION_REJECTED = 'rejected';

    public const DECISION_CORRECTION_REQUESTED = 'correction_requested';

    public const DECISION_BLOCKED = 'blocked';

    protected $fillable = [
        'verification_id',
        'admin_id',
        'decision',
        'reason',
        'notes',
        'decided_at',
    ];

    protected function casts(): array
    {
        return [
            'decided_at' => 'datetime',
        ];
    }

    public function verification(): BelongsTo
    {
        return $this->belongsTo(Verification::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
