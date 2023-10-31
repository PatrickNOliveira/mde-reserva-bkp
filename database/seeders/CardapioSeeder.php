<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cardapio;

class CardapioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cardapio::truncate();
        Cardapio::insert([
            [
                'id' => 1,
                'descricao' => 'Salão',
                'conta_id' => 1, // WINRESTA
                'padrao' => false,
                'localizacao' => false,
            ],[
                'id' => 2,
                'descricao' => 'Suíte',
                'conta_id' => 2, // WINLETOH,
                'padrao' => true,
                'localizacao' => false,
            ],[

                'id' => 3,
                'descricao' => 'Restaurante',
                'conta_id' => 2, // WINLETOH
                'padrao' => false,
                'localizacao' => true,
            ],[
                'id' => 4,
                'descricao' => 'Piscina',
                'conta_id' => 2, // WINLETOH
                'padrao' => false,
                'localizacao' => true,
            ],[
                'id' => 5,
                'descricao' => 'Suíte',
                'conta_id' => 3, // WINLETOM
                'padrao' => false,
                'localizacao' => false,
            ],[
                'id' => 6,
                'descricao' => 'Frigobar',
                'conta_id' => 3, // WINLETOM
                'padrao' => false,
                'localizacao' => false,
            ]
    ]);

    }
}
