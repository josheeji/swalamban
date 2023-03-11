<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Seo;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the seo.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Seo  $seo
     * @return mixed
     */
    public function view(Admin $admin, Seo $seo)
    {
        //
    }

    /**
     * Determine whether the user can create seos.
     *
     * @param  \App\Models\Admin  $admin
     * @return mixed
     */
    public function create(Admin $admin)
    {
        //
    }

    /**
     * Determine whether the user can update the seo.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Seo  $seo
     * @return mixed
     */
    public function update(Admin $admin, Seo $seo)
    {
        //
    }

    /**
     * Determine whether the user can delete the seo.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Seo  $seo
     * @return mixed
     */
    public function delete(Admin $admin, Seo $seo)
    {
        return $seo->deletable == 1;
    }
}
