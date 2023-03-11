<?php

namespace App\Repositories;

use App\Models\DoctorTimeSlots;

class DoctorTimeSlotRepository extends Repository
{
    public function __construct(DoctorTimeSlots $time_slot)
    {
        $this->model = $time_slot;
    }
}
