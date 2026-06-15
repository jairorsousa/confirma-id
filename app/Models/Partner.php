<?php

namespace App\Models;

use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_BLOCKED = 'blocked';

    protected $fillable = [
        'legal_name',
        'trade_name',
        'cnpj',
        'responsible_name',
        'email',
        'phone',
        'status',
        'plan_name',
        'can_query_cpf',
        'api_key_hash',
    ];

    protected $hidden = [
        'api_key_hash',
    ];

    protected function casts(): array
    {
        return [
            'can_query_cpf' => 'boolean',
        ];
    }

    public function members(): HasMany
    {
        return $this->hasMany(PartnerMember::class);
    }

    public function queries(): HasMany
    {
        return $this->hasMany(PartnerQuery::class);
    }
}
