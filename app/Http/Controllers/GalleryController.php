<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\MenuItems;
use Illuminate\Http\Request;

use App\Repositories\GalleryRepository;
use App\Repositories\GalleryImageRepository;
use App\Repositories\GalleryVideoRepository;
use Spatie\SchemaOrg\Schema;

class GalleryController extends Controller
{
    public function __construct(GalleryRepository $gallery, GalleryImageRepository $galleryImage, GalleryVideoRepository $video)
    {
        $this->gallery = $gallery;
        $this->galleryImage = $galleryImage;
        $this->video = $video;
    }

    protected function locale()
    {
        return Helper::locale();
    }

    public function index()
    {
        $galleries = $this->gallery->where('is_active', 1)
            ->where('language_id', $this->locale())->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->paginate(9);
        $menu = MenuItems::where('link_url',request()->path())->orWhere('link_url','/'.request()->path())->first();
        // dd($galleries);
        return view('gallery.index')->withGalleries($galleries)->withMenu($menu);
    }

    public function show($slug)
    {
        $gallery = $this->gallery->where('is_active', 1)->where('slug', $slug)
            ->where('language_id', $this->locale())->first();
        if (!$gallery) {
            abort('404');
        }
        // $schema = Schema::imageGallery()->about($gallery->title)
        //     ->creator(Helper::schemaCreator())
        //     ->dateCreated($gallery->created_at)
        //     ->dateModified($gallery->updated_at)
        //     ->name($gallery->title)
        //     ->url(url()->full());
        // $schema = $schema->toScript();
        $refId = (isset($gallery->existing_record_id) && !empty($gallery->existing_record_id)) ? $gallery->existing_record_id : $gallery->id;
        $galleryImages = $this->galleryImage
            ->orderBy('created_at', 'desc')
            ->where('gallery_id', $refId)->paginate(9);
        return view('gallery.show')->withGalleryImages($galleryImages)
            ->withGallery($gallery);
    }

    public function video()
    {
        $videos = $this->video->where('language_id', $this->locale())
            ->where('is_active', 1)
            ->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')
            ->paginate(12);
            $menu = MenuItems::where('link_url',request()->path())
            ->orWhere('link_url','/'.request()->path())->first();

        return view('gallery.video')->withVideos($videos)->withMenu($menu);
    }
}
