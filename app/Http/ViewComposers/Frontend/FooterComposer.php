<?php

namespace App\Http\ViewComposers\Frontend;


use App\Http\Controllers\DestinationController;
use App\Repositories\ActivityRepository;
use App\Repositories\DestinationRepository;
use App\Repositories\ContentRepository;

use App\Repositories\NewsRepository;
use App\Repositories\PackageRepository;
use App\Repositories\SettingRepository;
use Illuminate\View\View;

class FooterComposer
{

    protected $activity,$package,$news,$content,$destionation,$setting;

    public function __construct(
        ActivityRepository $activity,
        PackageRepository $package,
        NewsRepository $news,
        ContentRepository $content,
        DestinationRepository $destination,
        SettingRepository $setting
    )
    {
        $this->activity = $activity;
        $this->content = $content;
        $this->destination = $destination;
        $this->news = $news;
        $this->setting = $setting;
    }

    public function compose(view $view)
    {

    }
}