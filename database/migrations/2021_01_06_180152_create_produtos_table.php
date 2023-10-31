<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite')->create('produtos', 
        function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('descricao');
            $table->longText('nota')->nullable();
            $table->string('foto')->nullable();
            $table->string('tipo')->default('PR');
            $table->decimal('quantidade', $precision = 4, $scale = 2);
            $table->decimal('preco', $precision = 4, $scale = 2);
            $table->foreignId('cardapio_id')->constrained(); 
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
        Schema::dropIfExists('produtos');
    }
}
