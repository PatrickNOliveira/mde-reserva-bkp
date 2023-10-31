<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

use App\ViewModels\PedidoViewModel;

use DateTime;

class PedidoController extends Controller
{

    private static $_CONSUMERS = [
            [
                'id' => 0,
                'orders' => [
                    [
                        'order' =>  1327,
                        'total' => 22.00,
                        'closed' => true,
                        'items' => [
                            [
                                'code' => 23,
                                'description' => 'Porção de fritas',
                                'quantity' => 1,
                                'price' => 12,
                                'note' => ''
                            ],[
                                'code' => 25,
                                'description' => 'Limpeza da Suíte',
                                'quantity' => 1,
                                'type' => 'service',
                                'price' => 0,
                                'note' => ''
                            ],[
                                'code' => 24,
                                'description' => 'Suco de laranja',
                                'quantity' => 2,
                                'price' => 5.00,
                                'note' => ''
                            ]
                        ]
                    ],[
                        'order' =>  1328,
                        'total' => 10.00,
                        'items' => [
                            [
                                'code' => 22,
                                'description' => 'Salada super',
                                'quantity' => 1,
                                'price' => 10.00,
                                'note' => ''
                            ]
                        ]
                    ],[
                        'order' =>  1329,
                        'total' => 17.00,
                        'items' => [
                            [
                                'code' => 23,
                                'description' => 'Salada mega',
                                'quantity' => 1,
                                'price' => 12.00,
                                'note' => ''
                            ],[
                                'code' => 30,
                                'description' => 'Coca-Cola(lata)',
                                'quantity' => 1,
                                'price' => 5.00,
                                'note' => ''
                            ]
                        ]
                    ]
               ]
            ]
    ];

    public function __construct()
    {
    }

    public function index($id = null) 
    {
        return response()->json(PedidoViewModel::getConta($id));
    } 

    public function foto($id) {
        $path = storage_path('/images/fotos/');
        $foto = $path . $id . '.png';
        if (!File::exists($foto)) {
            $foto = $path . $id . '.jpg';
            if (!File::exists($foto)) {
                $foto = $path . 'cardapio.jpg';
                if (!File::exists($foto)) abort(404);
            }
        }
        $file = File::get($foto);
        $type = File::mimeType($foto);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
    
    public function som($id) {
        $path = storage_path('/sons/');
        $som = $path . $id . '.mp3';
        if (!File::exists($som)) {
            $som = $path . 'ding.mp3';
            if (!File::exists($som)) abort(404);
        }
        $file = File::get($som);
        $type = File::mimeType($som);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function logotipo($id) {
        $path = storage_path('/images/logos/');
        $foto = $path . $id . '.png';
        if (!File::exists($foto)) {
            $foto = storage_path('images/camera.png');
            if (!File::exists($foto)) abort(404);
        }
        $file = File::get($foto);
        $type = File::mimeType($foto);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function pedidosSuite($suite_id = null, $status = 'TODOS') 
    {
        return response()->json(PedidoViewModel::getPedidosSuite($suite_id, $status));
    } 

    public function pedidosMesa($id, $mesa) 
    {
        return response()->json(PedidoViewModel::getPedidosMesa($id, $mesa));
    } 

    public function apartamentos($id) {
        return response()->json(PedidoViewModel::getApartamentos($id));
    }

    public function notificacoes($id) {
        return response()->json(PedidoViewModel::getNotificacoes($id));
    }

    public function alarmes($id){
        return response()->json(PedidoViewModel::getAlarmes($id));
    }

    public function baixaAlarme($id, $suite, $funcionario) {
        return response()->json(PedidoViewModel::baixaAlarme($id, $suite, $funcionario));
    }

    public function recuperarCardapios($id) 
    {
        return response()->json(PedidoViewModel::getCardapios($id));
    }

    public function trocarItemMesa($id, $item_id, $mesa_atual, $nova_mesa) 
    {
        return response()->json(PedidoViewModel::trocarItemMesa($id, $item_id, $mesa_atual, $nova_mesa));
    }
    
    public function insert(Request $request) 
    {
        return response()->json(PedidoViewModel::insereItem($request->getContent()));
    }

    public function login(Request $request) 
    {
        return response()->json(PedidoViewModel::entrar($request->getContent()));
    }

    public function fecharPedido(Request $request) 
    {
        return response()->json(PedidoViewModel::fecharPedido($request->getContent()));
    }

    public function carrinhoSuite($id, $suite_id, $item = null)
    {
        if ($item == null) return response()->json(PedidoViewModel::getCarrinhoSuite($id, $suite_id));
        return response()->json(PedidoViewModel::removeDoCarrinhoSuite($id, $suite_id, $item)); // Verificar
    }

    public function carrinhoMesa($id, $mesa, $item = null)
    {
        if ($item == null) return response()->json(PedidoViewModel::getCarrinhoMesa($id, $mesa));
        return response()->json(PedidoViewModel::removeDoCarrinhoMesa($id, $mesa, $item));
    }

    public function products($id, $cardapio) 
    {
        return response()->json(PedidoViewModel::getProdutos($id, $cardapio));
    } 
 
    public function photo($name = null){

        $file = null;
        $foto = storage_path('/images/produtos/' . $name);

        try {
            
            $file = File::get($foto);

        } catch (\Throwable $th) {
          
            $foto = storage_path('/images/produtos/camera.png');

            $file = File::get($foto);
            
        }

        $response = Response::make($file, 200);
        $response->header("Content-Type", File::mimeType($foto));
    
        return $response;

    }

    public function removePedidoSuite($id, $pedido = null, $item = null)
    {
        return response()->json(PedidoViewModel::removePedidoSuite($id, $pedido, $item));
    }

}
