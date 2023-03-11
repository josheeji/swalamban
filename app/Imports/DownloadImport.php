<?php

namespace App\Imports;

use App\Models\Download;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;

class DownloadImport implements ToModel
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

        $data = Download::where('title', $title)->get();
        if ($data) {
            // if (Storage::disk('public')->exists('uploads/Downloads/' . $filename)) {
            //     if (!Storage::disk('public')->exists('download/' . $filename)) {
            //         Storage::copy('uploads/downloads/' . $filename, 'download/' . $filename);
            //     }
            // }
            foreach ($data as $model) {
                $model->file = 'download/' . $filename;
                $model->save();
            }
        }
    }
}
