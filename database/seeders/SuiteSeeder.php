<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Suite;

class SuiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Suite::truncate();
        Suite::insert([
            'id' => 1,
            'numero' => 20,
            'conta_id' => 2,
        ]);
        Suite::insert([
            'id' => 2,
            'numero' => 21,
            'conta_id' => 2,
        ]);
        Suite::insert([
            'id' => 3,
            'numero' => 30,
            'conta_id' => 3,
        ]);
        Suite::insert([
            'id' => 4,
            'numero' => 31,
            'conta_id' => 3,
        ]);
    }
}
