<?php

namespace App\Imports;

use App\Helper\Helper;
use App\Models\AtmLocation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CheckBankGuaranteeImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
    }

    public function startRow(): int
    {
        return 2;
    }
}
