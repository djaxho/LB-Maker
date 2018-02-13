<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\BlogGroup' => 'App\Policies\BlogGroupPolicy',
        'App\Blog' => 'App\Policies\BlogPolicy',
        'App\Leadbox' => 'App\Policies\LeadboxPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Team' => 'App\Policies\TeamPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
