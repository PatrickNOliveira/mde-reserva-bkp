<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Funcionario;

class FuncionarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Funcionario::truncate();
        Funcionario::insert([[
            'id' => 1,
            'login' => 'carlos',
            'senha' => '123',
            'conta_id' => 1, // WINRESTA
            'perfil' => 'garcom'
        ],[
            'id' => 2,
            'login' => 'maria',
            'senha' => '123',
            'conta_id' => 2, // WINLETOH
            'perfil' => 'camareira'
        ],[
            'id' => 3,
            'login' => 'joana',
            'senha' => '123',
            'conta_id' => 3, // WINLETOM
            'perfil' => 'camareira'
        ],[
            'id' => 4,
            'login' => 'joao',
            'senha' => '123',
            'conta_id' => 3, // WINLETOM
            'perfil' => 'garcom'
        ]]);
    }
}
