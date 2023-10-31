<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite')->create('item_pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('descricao');
            $table->string('foto')->nullable();
            $table->datetime('atendido')->nullable();
            $table->string('tipo')->default('produto');
            $table->decimal('quantidade', $precision = 4, $scale = 2);
            $table->decimal('preco', $precision = 4, $scale = 2);
            $table->longText('nota')->nullable();
            $table->foreignId('produto_id')->constrained();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
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
        Schema::connection('sqlite')->dropIfExists('item_pedidos');
    }
}
