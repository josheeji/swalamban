<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository extends Repository
{
    public function __construct(Doctor $doctor)
    {
        $this->model = $doctor;
    }

    public function doctorListByDepartment($department)
    {
        return $this->model->where('department_id', $department)->where('is_active', '1')->get();
    }
}
