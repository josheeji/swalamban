<?php

namespace Database\Seeders;

use App\Models\Placement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlacementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $placecments = [
            ['title' => 'Right Sidebar'],
            ['title' => 'Left Sidebar']
        ];

        $records = Placement::pluck('title')->toArray();

        foreach ($placecments as $placement) {
            if (!in_array($placement['title'], $records)) {
                DB::table('placements')->insert($placement);
            }
        }
    }
}
