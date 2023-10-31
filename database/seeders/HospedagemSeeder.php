<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospedagem;
use Carbon\Carbon;

class HospedagemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hospedagem::truncate();
        Hospedagem::insert([
            'id' => 1,
            'reserva' => '1001',
            'hospede' => 'Donizete',
            'cpf' => '11111111111',
            'checkin' => Carbon::now(),
            'checkout' => Carbon::now()->addDays(30),
            'suite_id' => 1,
        ]);
        Hospedagem::insert([
            'id' => 2,
            'reserva' => '1002',
            'hospede' => 'George',
            'cpf' => '22222222222',
            'checkin' => Carbon::now(),
            'checkout' => Carbon::now()->addDays(30), // Carbon::now()->addDays(rand(5,10)),
            'suite_id' => 2,
        ]);
        Hospedagem::insert([
            'id' => 3,
            'reserva' => '1003',
            'hospede' => 'Fabricio',
            'cpf' => '33333333333',
            'checkin' => Carbon::now(), //Carbon::now()->subDays(5),
            'checkout' => Carbon::now()->addDays(30),
            'suite_id' => 3,
        ]);
        Hospedagem::insert([
            'id' => 4,
            'reserva' => '1003',
            'hospede' => 'Ricardo',
            'cpf' => '44444444444',
            'checkin' => Carbon::now(),
            'checkout' => Carbon::now()->addDays(30),
            'suite_id' => 4,
        ]);
    }
}
