<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospedagemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite')->create('hospedagems', 
        function (Blueprint $table) {
            $table->id();
            $table->string('hospede');
            $table->string('cpf');
            $table->string('reserva');
            $table->dateTime('checkin', $precision = 0);
            $table->dateTime('checkout', $precision = 0);
            $table->foreignId('suite_id')->constrained();   
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospedagems');
    }
}
