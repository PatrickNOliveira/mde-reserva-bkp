<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/som/{id}', 'PedidoController@som'); // OK

Route::get('/logo/{id}', 'PedidoController@logotipo'); // OK
Route::get('/foto/{id}', 'PedidoController@foto'); // OK
Route::get('/order/info/{id}', 'PedidoController@index'); // OK

Route::get('/order/products/{id}/{cardapio}', 'PedidoController@products'); // OK
Route::get('/order/photo/{name}', 'PedidoController@photo'); // OK
Route::get('/order/photo/', 'PedidoController@photo'); // OK

//Route::delete('/order/consumer/{id}/{order}', 'PedidoController@removePedidoSuite'); // OK
//Route::delete('/order/consumer/{id}/{order}/{item}', 'PedidoController@removePedidoSuite'); // OK

Route::get('/order/consumer/{suite_id}', 'PedidoController@pedidosSuite'); // OK
Route::get('/order/consumer/{suite_id}/{status}', 'PedidoController@pedidosSuite'); // OK

Route::get('/order/consumer/waiter/{id}/{mesa}', 'PedidoController@pedidosMesa'); // OK

Route::get('/order/cart/{id}/{suite_id}', 'PedidoController@carrinhoSuite'); // OK
Route::delete('/order/cart/{id}/{suite_id}/{item}', 'PedidoController@carrinhoSuite'); // OK

Route::get('/order/cart/waiter/{id}/{mesa}', 'PedidoController@carrinhoMesa'); // OK
Route::delete('/order/cart/waiter/{id}/{mesa}/{item}', 'PedidoController@carrinhoMesa'); // OK

Route::get('/cardapios/{id}', 'PedidoController@recuperarCardapios');

Route::post('/order/cart', 'PedidoController@fecharPedido'); // OK

Route::post('/order', 'PedidoController@insert');  // OK

Route::post('/entrar', 'PedidoController@login');

Route::post('/mesa/{id}/{item}/{mesa_atual}/{nova_mesa}', 'PedidoController@trocarItemMesa');

Route::get('/rooms/{id}', 'PedidoController@apartamentos');

Route::get('/notify/{id}', 'PedidoController@notificacoes');

Route::get('/alarms/{id}', 'PedidoController@alarmes');

Route::post('/alarm/{id}/{suite}/{funcionario}', 'PedidoController@baixaAlarme');

Route::post('/suite/status/{id}/{suite}/{status}', 'ServicoController@alteraStatusSuite');


