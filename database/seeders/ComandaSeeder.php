<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comanda;
use Carbon\Carbon;

class ComandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comanda::truncate();
        Comanda::insert([
            'id' => 1,
            'mesa_id' => 1,
            'fechada' => Carbon::now()
        ]);

        Comanda::insert([
            'id' => 2,
            'mesa_id' => 1,
            'fechada' => null
        ]);

        Comanda::insert([
            'id' => 3,
            'mesa_id' => 3,
            'fechada' => null
        ]);
    }
}
