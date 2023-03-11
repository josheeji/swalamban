<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/30/18
 * Time: 3:26 PM
 */

namespace App\Repositories;

use App\Models\GalleryVideo;

class GalleryVideoRepository extends Repository
{
    public function __construct(GalleryVideo $video)
    {
        $this->model = $video;
    }

}