<?php

namespace App\Providers;

use App\Models\Domain;
use App\Models\Concept;
use App\Policies\DomainPolicy;
use App\Policies\ConceptPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(Domain::class, DomainPolicy::class);
        Gate::policy(Concept::class, ConceptPolicy::class);
    }
}
