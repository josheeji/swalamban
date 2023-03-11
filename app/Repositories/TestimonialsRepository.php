<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/7/18
 * Time: 10:33 AM
 */

namespace App\Repositories;

use App\Models\Store;
use App\Models\Testimonials;

class TestimonialsRepository extends Repository
{
    public  function __construct(Testimonials $testimonials)
    {
        $this->model = $testimonials;
    }
    public function update($id,$inputs){
        $update = $this->model->findOrFail($id);
        $update->fill($inputs)->save();
        return $update;
    }
}