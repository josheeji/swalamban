<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Repositories\DestinationRepository;
use App\Repositories\ContentRepository;
use App\Repositories\PackageRepository;

use Spatie\Sitemap\Sitemap;


class SiteMapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matnepal:sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        DestinationRepository $destinations,
        ContentRepository $contents,
        PackageRepository $packages
    )
    {
        parent::__construct();
        $this->destinations = $destinations;
        $this->contents = $contents;
        $this->packages = $packages;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(route('home'))
            ->add(route('gallery.index'))
            ->add(route('contact.index'))
            ->add(route('faq.index'))
            ->add(route('faq.index'))
        ;

        $contents = $this->contents->where('is_active', '=', '1')->get();
        $destinations = $this->destinations->where('is_active', '=', '1')->get();
        $packages = $this->packages->where('is_active', '=', '1')->get();

        $contents->each(function ($content) use ($sitemap) {
            $sitemap->add(route("content.show", $content->slug));
        });

        $destinations->each(function ($destination) use ($sitemap) {
            $sitemap->add(route("page.destination.details", $destination->slug));
        });

        $packages->each(function ($package) use ($sitemap) {
            $sitemap->add(route("page.package.details", $package->slug));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
