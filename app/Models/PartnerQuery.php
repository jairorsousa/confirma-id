<?php

namespace App\Models;

use Database\Factories\PartnerQueryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerQuery extends Model
{
    /** @use HasFactory<PartnerQueryFactory> */
    use HasFactory;

    public const TYPE_CODE = 'verification_code';

    public const TYPE_EMAIL = 'email';

    public const TYPE_CPF = 'cpf';

    public const RESULT_APPROVED = 'approved';

    public const RESULT_UNDER_REVIEW = 'under_review';

    public const RESULT_NOT_FOUND = 'not_found';

    public const RESULT_BLOCKED = 'blocked';

    protected $fillable = [
        'partner_id',
        'user_id',
        'query_type',
        'queried_term_hash',
        'queried_term_masked',
        'result',
        'ip_address',
        'origin',
        'credential_label',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
