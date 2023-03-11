<?php

namespace App\Imports;

use App\Helper\Helper;
use App\Models\AtmLocation;
use Maatwebsite\Excel\Concerns\ToModel;

class AtmImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (is_int($row[0])) {
            $data = [
                'title' => $row[1],
                'inside_valley' => $row[5],
                'address' =>  $row[3],
                'lat' => $row[6],
                'long' => $row[7],
                'url' => isset($row[8]) ? $row[8] : ''
            ];

            if (!AtmLocation::where('title', $row[1])->first()) {
                if ($atmLocation = AtmLocation::create($data)) {
                    $data = [
                        'existing_record_id' => $atmLocation->id,
                        'language_id' => 2,
                        'title' => $row[2],
                        'inside_valley' => $atmLocation->inside_valley,
                        'lat' => $atmLocation->lat,
                        'long' => $atmLocation->long,
                        'address' => $row[4],
                        'url' => isset($row[8]) ? $row[8] : '',
                        'created_by' => auth()->user()->id
                    ];

                    $atmMultiLocation = AtmLocation::create($data);
                }
            }
        }
    }

    public static function getProvinceID($province)
    {
        $province = Helper::slug($province);
        switch ($province) {
            case 'province-1':
                return 1;
            case 'province-2':
                return 2;
            case 'province-3':
                return 3;
            case 'gandaki-province':
                return 4;
            case 'province-5':
                return 5;
            case 'karnali-province':
                return 6;
            case 'sudurpaschim-province':
                return 7;
        }
    }
}
