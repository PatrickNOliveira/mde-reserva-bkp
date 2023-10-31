<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemPedido;
use Carbon\Carbon;

class ItemPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemPedido::truncate();
        ItemPedido::insert([
            [
                'id' => 1,
                'codigo' => '20',
                'descricao' => 'Whisky Black Label(garrafa)',
                'foto' => null,
                'tipo' => 'bebida',
                'quantidade' => 1,
                'preco' => 70,
                'nota' => '',
                'pedido_id' => 1,
                'atendido' => Carbon::now(),
                'produto_id' => 1,
            ],[
                'id' => 2,
                'codigo' => '23',
                'descricao' => 'Suco de laranja',
                'foto' => null,
                'tipo' => 'bebida',
                'quantidade' => 1,
                'preco' => 15,
                'nota' => '',
                'atendido' => null,
                'pedido_id' => 2,
                'produto_id' => 4,
            ],[
                'id' => 3,
                'codigo' => '21',
                'descricao' => 'Almoço Executivo',
                'foto' => null,
                'tipo' => 'refeicao',
                'quantidade' => 1,
                'preco' => 30,
                'nota' => 'Para os dois pratos: carne ao ponto e ovo muito bem frito. Obrigado.',
                'atendido' => null,
                'pedido_id' => 2,
                'produto_id' => 2,
            ]
        ]);
        
        ItemPedido::insert([
            'id' => 4,
            'codigo' => '23',
            'descricao' => 'Suco de laranja',
            'foto' => null,
            'tipo' => 'bebida',
            'quantidade' => 1,
            'preco' => 5,
            'nota' => '',
            'atendido' => null,
            'pedido_id' => 3,
            'produto_id' => 4,
        ]);

        /*
        for ($i=4;$i<8;$i++){
            ItemPedido::insert([
                'id' => $i,
                'codigo' => '23',
                'descricao' => 'Suco de laranja',
                'foto' => null,
                'tipo' => 'bebida',
                'quantidade' => 1,
                'preco' => 5,
                'nota' => '',
                'atendido' => null,
                'pedido_id' => $i-1,
                'produto_id' => 4,
            ]);
        }
        */
    }
}
