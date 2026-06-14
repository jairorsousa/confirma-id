<?php

namespace App\Models;

use Database\Factories\VerificationFileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationFile extends Model
{
    /** @use HasFactory<VerificationFileFactory> */
    use HasFactory;

    public const TYPE_FRONT = 'front';

    public const TYPE_BACK = 'back';

    public const TYPE_SELFIE = 'selfie';

    protected $fillable = [
        'verification_id',
        'file_type',
        'disk',
        'path',
        'mime_type',
        'size_bytes',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
            'uploaded_at' => 'datetime',
        ];
    }

    public function verification(): BelongsTo
    {
        return $this->belongsTo(Verification::class);
    }
}
