<?php

namespace App\Imports;

use App\Models\FinancialReport;
use Maatwebsite\Excel\Concerns\ToModel;

class FinancialReportImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $title = $row[0];
        $file = $row[1];
        $ext = !empty($row[2]) ? $row[2] : '.pdf';
        $filename = $file . $ext;

        $data = FinancialReport::where('title', trim($title))->get();
        if ($data) {
            foreach ($data as $model) {
                $model->file = 'financial-report/' . $filename;
                $model->save();
            }
        }
    }
}
