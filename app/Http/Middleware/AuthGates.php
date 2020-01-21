<?php

namespace App\Http\Middleware;

use App\Feature;
use Closure;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $userFeatures = Feature::select('features.name')
                ->join('feature_plan', 'feature_plan.feature_id', '=', 'features.id')
                ->join('plans', 'feature_plan.plan_id', '=', 'plans.id')
                ->join('subscriptions', 'plans.stripe_plan_id', '=', 'subscriptions.stripe_plan')
                ->where('subscriptions.user_id', auth()->id())
                ->where(function($query) {
                    return $query->whereNull('subscriptions.ends_at')
                        ->orWhere('subscriptions.ends_at', '>', now()->toDateTimeString());
                })
                ->pluck('features.name');
            foreach ($userFeatures as $feature) {
                Gate::define($feature, function() { return true; });
            }
        }

        return $next($request);
    }
}
