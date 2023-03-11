<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 11/19/18
 * Time: 4:33 PM
 */

namespace App\Repositories;

use App\Models\Inquiry;

class InquiryRepository extends Repository
{
    public function __construct(Inquiry $inquiry)
    {
        $this->model = $inquiry;
    }
}