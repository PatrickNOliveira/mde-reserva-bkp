<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Conta;

class ContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conta::truncate();
        Conta::insert([
            'id' => 1,
            'hotel' => 'WinResta',
            'sistema' => 'WINRESTA',
            'codigo' => '00001',
            'uuid' => 'e7886e3d-c81c-4829-8a57-7768d8148754',
        ]);
        Conta::insert([
            'id' => 2,
            'hotel' => 'WinLetoh',
            'sistema' => 'WINLETOH',
            'codigo' => '00002',
            //'uuid' => (string)Str::uuid(),
            'uuid' => 'd88b870b-5ac4-4836-9c31-47d91f94e648',
        ]);

        Conta::insert([
            'id' => 3,
            'hotel' => 'WinLetom',
            'sistema' => 'WINLETOM',
            'codigo' => '00003',
            //'uuid' => (string)Str::uuid(),
            'uuid' => 'd88b870b-5ac4-4836-9c31-47d91f94e650',
        ]);
    }
}
