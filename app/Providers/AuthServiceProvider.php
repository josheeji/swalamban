<?php

namespace App\Providers;

use App\Models\News;
use App\Models\Notice;
use App\Models\Seo;
use App\Policies\NewsPolicy;
use App\Policies\NoticePolicy;
use App\Policies\SeoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Seo::class => SeoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('master-policy.perform', 'App\Policies\Admin\MasterAccessPolicy@perform');
        Gate::define('master-policy.performArray', 'App\Policies\Admin\MasterAccessPolicy@performArray');
    }
}
