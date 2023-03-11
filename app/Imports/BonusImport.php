<?php

namespace App\Imports;

use App\Models\Bonus;
use App\Models\BonusCategory;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\ToModel;

class BonusImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // ini_set('max_execution_time', 1800);

        // $title = Request::get('title');
        // if (is_numeric($row[0])) {
        //     if (!$category = BonusCategory::where('title', $title)->first()) {
        //         $category = BonusCategory::create(['title' => $title, 'is_active' => 1]);
        //     }
        //     $data['category_id'] = $category->id;
        //     $data['boid'] = isset($row[1]) ? $row[1] : '';
        //     $data['name'] = isset($row[2]) ? $row[2] : '';
        //     $data['type'] = isset($row[3]) ? $row[3] : '';
        //     $data['actual_bonus'] = isset($row[4]) ? $row[4] : '';
        //     $data['tax_amount'] = isset($row[5]) ? $row[5] : '';
        //     if (!$bonus = Bonus::where('category_id', $category->id)->where('boid', $data['boid'])->where('name', $data['name'])->first()) {
        //         $bonus = Bonus::create($data);
        //     }
        // }
    }
}
