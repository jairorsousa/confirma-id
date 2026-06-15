<?php

namespace App\Providers;

use App\Actions\Partner\ResolvePartnerForUser;
use App\Models\Partner;
use App\Models\PartnerQuery;
use App\Models\Verification;
use App\Models\VerificationFile;
use App\Policies\PartnerPolicy;
use App\Policies\PartnerQueryPolicy;
use App\Policies\VerificationFilePolicy;
use App\Policies\VerificationPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Verification::class, VerificationPolicy::class);
        Gate::policy(VerificationFile::class, VerificationFilePolicy::class);
        Gate::policy(Partner::class, PartnerPolicy::class);
        Gate::policy(PartnerQuery::class, PartnerQueryPolicy::class);

        RateLimiter::for('partner-api', function (Request $request): Limit {
            $user = $request->user();
            $membership = $user ? app(ResolvePartnerForUser::class)->firstMembership($user) : null;
            $tokenId = $user?->currentAccessToken()?->id ?: 'no-token';
            $partnerKey = $membership?->partner_id ?: $request->ip();

            return Limit::perMinute(config('confirmaid.rate_limits.partner_api_per_minute'))
                ->by($partnerKey.'|'.$tokenId);
        });

        RateLimiter::for('partner-query', function (Request $request): Limit {
            $user = $request->user();
            $membership = $user ? app(ResolvePartnerForUser::class)->firstMembership($user) : null;
            $partnerKey = $membership?->partner_id ?: $request->ip();

            return Limit::perMinute(config('confirmaid.rate_limits.partner_web_per_minute'))
                ->by($partnerKey.'|'.($user?->id ?: 'guest'));
        });
    }
}
