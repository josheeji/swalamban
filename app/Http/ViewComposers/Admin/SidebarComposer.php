<?php

namespace App\Http\ViewComposers\Admin;

use Illuminate\View\View;

class SidebarComposer
{
    /**
     * Create a new sidebar composer.
     *
     * @return void
     */
    public function __construct()
    {
        auth()->shouldUse('admin');
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $admin = auth()->user();
        $view->with('admin', $admin);
        dd($view);
    }
}
