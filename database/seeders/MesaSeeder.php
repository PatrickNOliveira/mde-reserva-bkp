<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mesa;

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mesa::truncate();
        Mesa::insert([
            'id' => 1,
            'numero' => 10,
            'lotacao' => 4,
            'conta_id' => 1
        ]);
        Mesa::insert([
            'id' => 2,
            'numero' => 11,
            'lotacao' => 4,
            'conta_id' => 1
        ]);
        
    }
}
