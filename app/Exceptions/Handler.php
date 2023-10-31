<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

/**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception) {
            Log::Debug($exception);
            //return redirect('/');
        }
        return parent::render($request, $exception);
    }
    
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (SuiteNaoEncontradaException $e, $request) {
            return response(['erro' => 'Suíte não encontrada!'], 400);
        });    

        $this->renderable(function (HospedagemNaoEncontradaException $e, $request) {
            return response(['erro' => 'Hospedagem não encontrada!'], 400);
        });    
    
        $this->renderable(function (PedidoNaoEncontradoException $e, $request) {
            return response(['erro' => 'Pedido não encontrado!'], 400);
        });    

        $this->renderable(function (ItemPedidoNaoEncontradoException $e, $request) {
            return response(['erro' => 'Item não encontrado!'], 400);
        });    

        $this->renderable(function (PedidoEntregueException $e, $request) {
            return response(['erro' => 'Pedido entregue!'], 400);
        });    

        $this->renderable(function (PedidoNaoPertenceAHospedagemException $e, $request) {
            return response(['erro' => 'Pedido não pertence a hospedagem!'], 400);
        });    

        $this->renderable(function (ItemNaoPertenceAoPedidoException $e, $request) {
            return response(['erro' => 'Item não pertence ao pedido!'], 400);
        });    

        $this->renderable(function (ContaNaoEncontradaException $e, $request) {
            return response(['erro' => 'Conta não encontrada!'], 400);
        });    
        
        $this->renderable(function (FuncionarioNaoEncontradoException $e, $request) {
            return response(['erro' => 'Funcionário não encontrado!'], 400);
        });    

        $this->renderable(function (SenhaIncorretaException $e, $request) {
            return response(['erro' => 'Senha incorreta!'], 401);
        });    

        $this->renderable(function (MesaNaoEncontradaException $e, $request) {
            return response(['erro' => 'Mesa não encontrada!'], 400);
        });    

        $this->renderable(function (ItemDaComandaNaoEncontradoException $e, $request) {
            return response(['erro' => 'Item da comanda não encontrado!'], 400);
        });    

        $this->renderable(function (NenhumCardapioDefinidoException $e, $request) {
            return response(['erro' => 'Nenhum cardapio definido para essa conta!'], 400);
        });    

    }

    
}
