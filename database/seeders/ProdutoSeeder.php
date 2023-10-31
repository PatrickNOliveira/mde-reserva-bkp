<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Produto::truncate();
        Produto::insert([ // WINRESTA - Salão
            [
                'id' => 1,
                'codigo' => 20,
                'descricao' => 'Whisky Black Label(garrafa)',
                'nota' => 'Garrafa.',
                'foto' => '',
                'tipo' => 'bebida',
                'quantidade' => rand(1,100),
                'preco' => 70,
                'cardapio_id' => 1 
            ],[
                'id' => 2,
                'codigo' => 21,
                'descricao' => 'Almoço Executivo',
                'nota' => 'Arroz, feijão, contra-file, farofa, fritas e ovo frito. Serve duas pessoas.',
                'foto' => '',
                'tipo' => 'refeicao',
                'quantidade' => rand(1,100),
                'preco' => 30,
                'cardapio_id' => 1 
            ],[
                'id' => 3,
                'codigo' => 22,
                'descricao' => 'Porção de Salada',
                'nota' => 'Alface, tomate, cebola, ovos cozidos, mussarela e azeitonas.',
                'foto' => '',
                'tipo' => 'salada',
                'quantidade' => rand(1,100),
                'preco' => 12,
                'cardapio_id' => 1 
            ],[
                'id' => 4,
                'codigo' => 23,
                'descricao' => 'Suco de laranja',
                'nota' => 'Jarra. Acompanha pote de gelo.',
                'foto' => '',
                'tipo' => 'bebida',
                'quantidade' => rand(1,100),
                'preco' => 15,
                'cardapio_id' => 1 
            ],[
                'id' => 5,
                'codigo' => 24,
                'descricao' => 'Porção de fritas',
                'nota' => '',
                'foto' => 'fritas.jpg',
                'tipo' => 'produto',
                'quantidade' => rand(1,100),
                'preco' => 17,
                'cardapio_id' => 1 
            ],[
                'id' => 6,
                'codigo' => 25,
                'descricao' => 'Suco de manga',
                'nota' => 'Copo.',
                'foto' => '',
                'tipo' => 'bebida',
                'quantidade' => rand(1,100),
                'preco' => 5,
                'cardapio_id' => 1 
            ]
        ]);

        Produto::insert([ 
            [
                'id' => 7, // WINLETOH - Suíte
                'codigo' => 23,
                'descricao' => 'Suco de laranja',
                'nota' => 'Jarra. Acompanha pote de gelo.',
                'foto' => '',
                'tipo' => 'bebida',
                'quantidade' => rand(1,100),
                'preco' => 15,
                'cardapio_id' => 2
            ],[
                'id' => 8, // WINLETOH - Suíte
                'codigo' => 24,
                'descricao' => 'Whisky Black Label',
                'nota' => 'Garrafa.',
                'foto' => '',
                'tipo' => 'bebida',
                'quantidade' => rand(1,100),
                'preco' => 70,
                'cardapio_id' => 2 
            ],[
                'id' => 9, // WINLETOH - Restaurante
                'codigo' => 25,
                'descricao' => 'Almoço Executivo',
                'nota' => 'Arroz, feijão, contra-file, farofa, fritas e ovo frito. Serve duas pessoas.',
                'foto' => '',
                'tipo' => 'refeicao',
                'quantidade' => rand(1,100),
                'preco' => 30,
                'cardapio_id' => 3
            ],[
                'id' => 10, // WINLETOH - Suíte
                'codigo' => 26,
                'descricao' => 'Porção de fritas',
                'nota' => '',
                'foto' => '',
                'tipo' => 'porção',
                'quantidade' => 0,
                'preco' => 10,
                'cardapio_id' => 4 
            ]
        ]);

        Produto::insert([ // WINLETOM - Suíte
            [
                'id' => 11,
                'codigo' => 23,
                'descricao' => 'Suco de laranja',
                'nota' => 'Jarra. Acompanha pote de gelo.',
                'foto' => '',
                'tipo' => 'bebida',
                'quantidade' => rand(1,100),
                'preco' => 15,
                'cardapio_id' => 5
            ],[
                'id' => 12,
                'codigo' => 24,
                'descricao' => 'Whisky Black Label',
                'nota' => 'Garrafa.',
                'foto' => '',
                'tipo' => 'bebida',
                'quantidade' => rand(1,100),
                'preco' => 70,
                'cardapio_id' => 5 
            ],[
                'id' => 13,
                'codigo' => 25,
                'descricao' => 'Almoço Executivo',
                'nota' => 'Arroz, feijão, contra-file, farofa, fritas e ovo frito. Serve duas pessoas.',
                'foto' => '',
                'tipo' => 'refeicao',
                'quantidade' => rand(1,100),
                'preco' => 30,
                'cardapio_id' => 5
            ]
        ]);

        Produto::insert([ // WINLETOM - Figobar
            [
                'id' => 14,
                'codigo' => 26,
                'descricao' => 'Suco de laranja',
                'nota' => 'caixinha',
                'foto' => '',
                'tipo' => 'bebida',
                'quantidade' => rand(1,100),
                'preco' => 5,
                'cardapio_id' => 6
            ]
        ]);

    }
}
