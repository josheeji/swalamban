<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 1/3/19
 * Time: 2:53 PM
 */

namespace App\Repositories;

use App\Models\EmailSubscription;

class EmailSubscriptionRepository extends Repository
{
    public function __construct(EmailSubscription $email)
    {
        $this->model = $email;
    }
}