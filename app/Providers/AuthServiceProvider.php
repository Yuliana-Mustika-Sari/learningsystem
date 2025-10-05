<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Course;
use App\Policies\CoursePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Contoh tambahan Gate manual (opsional)
        Gate::define('isAdmin', function ($user) {
            return $user->role_id === 1;
        });

        Gate::define('isInstructor', function ($user) {
            return $user->role_id === 2;
        });

        Gate::define('isStudent', function ($user) {
            return $user->role_id === 3;
        });
    }
}
