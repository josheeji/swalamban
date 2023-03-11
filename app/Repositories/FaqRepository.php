<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 1/22/19
 * Time: 2:47 PM
 */

namespace App\Repositories;

use App\Models\Faq;

class FaqRepository extends Repository
{
    public function __construct(Faq $faq)
    {
        $this->model = $faq;
    }
}