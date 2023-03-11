<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;


class FooterComposer
{
    /**
     * Create a new header composer.
     *
     * @return void
     */
    public function __construct(){

    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->withViewCount(100);
    }
}