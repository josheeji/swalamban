<?php

namespace App\Imports;

use App\Helper\Helper;
use App\Models\BranchDirectory;
use App\Models\District;
use Maatwebsite\Excel\Concerns\ToModel;

class BranchImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row[0]);
        if (is_int($row[0])) {

            $data = [
                'title' => $row[1],
                // 'is_headoffice' => $row[3],
                'type' => $row[3],
                'lat' => $row[4],
                'long' => $row[5],
                'address' => $row[6],
                'phone' => $row[10],
                'email' => $row[12],
                'fullname' => $row[13],
                'mobile' => $row[15],
                'fax' => isset($row[17]) ? $row[17] : '',
                'url' => isset($row[19]) ? $row[19] : null,
                'created_by' => auth()->user()->id
            ];

            if (!BranchDirectory::where('title', $row[0])->first()) {
                if ($branch = BranchDirectory::create($data)) {
                    $data = [
                        'existing_record_id' => $branch->id,
                        'language_id' => 2,
                        'title' => $row[2],
                        // 'is_headoffice' => $row[3],
                        'type' => $row[3],
                        'lat' => $row[4],
                        'long' => $row[5],
                        'address' => $row[7],
                        'ward_no' => $row[9],
                        'phone' => $row[11],
                        'email' => $row[12],
                        'fullname' => $row[14],
                        'mobile' => $row[16],
                        'fax' => isset($row[18]) ? $row[18] : '',
                        'url' => isset($row[19]) ? $row[19] : null,
                        'created_by' => auth()->user()->id
                    ];
                    $multiBranch = BranchDirectory::create($data);
                }
            }
        }
    }

    public static function getDistrictID($title)
    {
        $title = trim($title);
        $district = District::where('title', $title)->first();
        if ($district) {
            return $district->id;
        }
        return null;
    }

    public static function getProvinceID($province)
    {
        $province = Helper::slug($province);
        switch ($province) {
            case 'province-no-1':
                return 1;
            case 'province-no-2':
                return 2;
            case 'province-no-3':
                return 3;
            case 'gandaki-province':
                return 4;
            case 'province-no-5':
                return 5;
            case 'karnali-province':
                return 6;
            case 'sudur-spaschim-province':
                return 7;
        }
    }
}
