<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemComanda;
use Carbon\Carbon;

class ItemComandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemComanda::truncate();
        ItemComanda::insert([
            'id' => 1,
            'codigo' => '20',
            'descricao' => 'Whisky Black Label(garrafa)',
            'funcionario' => 'carlos',
            'foto' => null,
            'tipo' => 'bebida',
            'quantidade' => 1,
            'preco' => 70,
            'nota' => '',
            'comanda_id' => 1,
            'atendido' => Carbon::now(),
            'produto_id' => 1,
            'funcionario_id' => 1,
        ]);
        ItemComanda::insert([
            'id' => 2,
            'codigo' => '23',
            'descricao' => 'Suco de laranja',
            'funcionario' => 'carlos',
            'foto' => null,
            'tipo' => 'bebida',
            'quantidade' => 1,
            'preco' => 15,
            'nota' => '',
            'atendido' => null,
            'comanda_id' => 2,
            'produto_id' => 4,
            'funcionario_id' => 1,
        ]);
        ItemComanda::insert([
            'id' => 3,
            'codigo' => '21',
            'descricao' => 'Almoço Executivo',
            'funcionario' => 'carlos',
            'foto' => null,
            'tipo' => 'refeicao',
            'quantidade' => 1,
            'preco' => 30,
            'nota' => 'Para os dois pratos: carne ao ponto e ovo muito bem frito. Obrigado.',
            'atendido' => null,
            'comanda_id' => 3,
            'produto_id' => 2,
            'funcionario_id' => 1,
        ]);

    }
}
