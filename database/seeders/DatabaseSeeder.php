<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ContaSeeder::class,
            FuncionarioSeeder::class,
            MesaSeeder::class,
            SuiteSeeder::class,
            HospedagemSeeder::class,
            CardapioSeeder::class,
            ProdutoSeeder::class,
            //PedidoSeeder::class,
            //ItemPedidoSeeder::class,
            //ComandaSeeder::class,
            //ItemComandaSeeder::class,
        ]);
    }
}
