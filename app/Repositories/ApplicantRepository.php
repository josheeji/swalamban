<?php

namespace App\Repositories;

use App\Models\Applicant;

class ApplicantRepository extends Repository
{
    public function __construct(Applicant $applicant)
    {
        $this->model = $applicant;
    }
}
