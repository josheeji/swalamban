<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/30/18
 * Time: 10:47 AM
 */

namespace App\Repositories;

use App\Models\GalleryImage;

class GalleryImageRepository extends Repository
{
    public function __construct(GalleryImage $image)
    {
        $this->model = $image;
    }
}