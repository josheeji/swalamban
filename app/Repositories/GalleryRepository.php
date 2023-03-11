<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/30/18
 * Time: 10:46 AM
 */

namespace App\Repositories;

use App\Models\Gallery;

class GalleryRepository extends Repository
{
    public function __construct(Gallery $gallery)
    {
        $this->model = $gallery;
    }
}