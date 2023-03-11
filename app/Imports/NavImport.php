<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class NavImport implements ToModel
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        //
    }
}
