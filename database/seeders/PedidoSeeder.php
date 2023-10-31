<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use Carbon\Carbon;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pedido::truncate();

        Pedido::insert([
            [
                'id' => 1,
                'hospedagem_id' => 1,
                'numero' => 1326,
                'fechado' => Carbon::now(),
                'created_at' => Carbon::now(),                
                'updated_at' => Carbon::now(),                
            ],[
                'id' => 2,
                'hospedagem_id' => 1,
                'numero' => 1327,
                'fechado' => null,
                'created_at' => Carbon::now(),                
                'updated_at' => Carbon::now(),
            ]
        ]);

        Pedido::insert([
            'id' => 3,
            'hospedagem_id' => 2,
            'numero' => 1328,
            'fechado' => null,
            'created_at' => Carbon::now(),                
            'updated_at' => Carbon::now(),                
        ]);

        /*
        for ($i=3;$i<7;$i++){
            Pedido::insert([
                'id' => $i,
                'hospedagem_id' => 2,
                'numero' => 1327 + (3 - $i + 1),
                'fechado' => null,
                'created_at' => Carbon::now(),                
                'updated_at' => Carbon::now(),                
            ]);
        }
        */
    
    }
}
