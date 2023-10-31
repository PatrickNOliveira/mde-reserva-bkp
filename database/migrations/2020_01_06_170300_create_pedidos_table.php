<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite')->create('pedidos', 
        function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->nullable();
            $table->datetime('fechado')->nullable();
            $table->string('localizacao')->nullable();
            $table->string('observacao')->nullable();
            $table->string('funcionario')->nullable();
            $table->foreignId('hospedagem_id')->constrained();
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
        Schema::connection('sqlite')->dropIfExists('pedidos');
    }
}
