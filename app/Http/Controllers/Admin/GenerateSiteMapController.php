<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Console\Commands\SiteMapGenerator;

class GenerateSiteMapController extends Controller
{


    public function __construct(SiteMapGenerator $generator){
        auth()->shouldUse('admin');
        $this->generator = $generator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate()
    {
        $this->generator->handle();
        return redirect()->route('admin.seos.index')
            ->with('flash_notice', 'SitMap Generated Successfully.');

    }


}
