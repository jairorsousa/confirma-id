<?php

namespace App\Providers;

use App\Actions\Partner\ResolvePartnerForUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
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
        RateLimiter::for('partner-api', function (Request $request): Limit {
            $user = $request->user();
            $membership = $user ? app(ResolvePartnerForUser::class)->firstMembership($user) : null;
            $tokenId = $user?->currentAccessToken()?->id ?: 'no-token';
            $partnerKey = $membership?->partner_id ?: $request->ip();

            return Limit::perMinute(60)->by($partnerKey.'|'.$tokenId);
        });
    }
}
