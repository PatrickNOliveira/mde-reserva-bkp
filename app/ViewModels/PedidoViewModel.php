<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Database\PDO;

use App\Models\Conta;
use App\Models\Comanda;
use App\Models\ItemComanda;
use App\Models\Suite;
use App\Models\Hospedagem;
use App\Models\Pedido;
use App\Models\ItemPedido;
use App\Models\Produto;
use App\Models\Funcionario;
use App\Models\Mesa;
use App\Models\Cardapio;

use App\Exceptions\SuiteNaoEncontradaException;
use App\Exceptions\HospedagemNaoEncontradaException;
use App\Exceptions\PedidoNaoEncontradoException;
use App\Exceptions\ItemPedidoNaoEncontradoException;
use App\Exceptions\PedidoEntregueException;
use App\Exceptions\PedidoNaoPertenceAHospedagemException;
use App\Exceptions\ItemNaoPertenceAoPedidoException;
use App\Exceptions\FuncionarioNaoEncontradoException;
use App\Exceptions\SenhaIncorretaException;
use App\Exceptions\MesaNaoEncontradaException;
use App\Exceptions\ItemDaComandaNaoEncontradoException;
use App\Exceptions\NenhumCardapioDefinidoException;
use App\Exceptions\ContaNaoEncontradaException;

class PedidoViewModel extends ViewModel
{
    public function __construct($id)
    {
    }

    private static function getSuite($where) {
        $suite = Suite::where($where)->first();
        if ($suite == null) {
            throw new SuiteNaoEncontradaException();
        }
        return $suite;
    }

    private static function getPedido($id)
    {
        $pedido = Pedido::where('id', $id)->first();
        if ($pedido == null) {
            throw new PedidoNaoEncontradoException();
        }
        return $pedido;
    }

    private static function getPedidoAberto($hospedagem)
    {
        $pedido = Pedido::where([
            [ 'hospedagem_id', '=', $hospedagem['hospedagem_id']],
            [ 'fechado', '=', null ]
        ])->first() ?? null;
        $pedido = $pedido ?? new Pedido($hospedagem['hospedagem_id']);
        $pedido->save();
        return $pedido;
    }

    private static function getItemPedido($id)
    {
        $item = ItemPedido::where('id', $id)->first();
        if ($item == null) {
            throw new ItemPedidoNaoEncontradoException();
        }
        return $item;
    }

    private static function getTotal($quantidade, $preco) {
        try {
            return $quantidade * $preco;
        }
        catch(Exception $e) {
            return 0;
        }
    }

    private static function getMesa($mesa, $conta) {

        $mesa = Mesa::where([
            ['numero', '=', $mesa],
            ['conta_id', '=', $conta['id']]
        ])->first();

        if ($mesa == null) {
            throw new MesaNaoEncontradaException();
        }
        return $mesa;

    }

    private static function getComandaAberta($mesa) {
        $comanda = Comanda::where([
            ['mesa_id', '=', $mesa['id']],
            ['fechada', '=', null]
        ])->first() ?? new Comanda($mesa['id']);
        $comanda->created_at = Carbon::now();
        $comanda->updated_at = Carbon::now();
        $comanda->save();
        return $comanda;
    }

    private static function getItemsDaComanda($comanda) {

        $total    = 0;
        $result   = array();
        $produtos = array();

        $itens = ItemComanda::where([
            ['comanda_id', '=', $comanda['id']]
        ])->get() ?? null;

        if ($itens !== null) {
            foreach($itens as $i) {
                $item['codigo'] = $i['codigo'];
                $item['descricao'] = $i['descricao'];
                $item['foto'] = $i['foto'];
                $item['tipo'] = $i['tipo'];
                $item['quantidade'] = $i['quantidade'];
                $item['preco'] = $i['preco'];
                $item['total'] = Self::getTotal($i['quantidade'], $i['preco']);
                $item['nota'] = $i['nota'];
                $item['funcionario'] = $i['funcionario'];
                $item['funcionario_id'] = $i['funcionario_id'];
                $item['id'] = $i['id'];
                $total +=  $item['total'];
                array_push($produtos, $item);
            }
        }

        return array(
            'total' => $total,
            'items' => $produtos
        );

    }

    public static function getCarrinhoMesa($id, $mesa) {
        $conta = Self::getConta($id);
        $mesa = Self::getMesa($mesa, $conta);
        $comanda = Self::getComandaAberta($mesa);
        return Self::getItemsDaComanda($comanda);
    }

    public static function removeDoCarrinhoMesa($id, $mesa, $item) {
        $conta = Self::getConta($id);
        $mesa = Self::getMesa($mesa, $conta);
        $comanda = Self::getComandaAberta($mesa);

        $where = [
            [ 'comanda_id', '=', $comanda->id ]
        ];


        if ($item !== 'todos') {
            array_push($where, [ 'id', '=', $item ]);
        }

        ItemComanda::where($where)->delete();

        if ($comanda->items()->count() == 0) {
            Comanda::where([
                ['id', '=', $comanda->id]
            ])->delete();
        }

        return Self::getItemsDaComanda($comanda);

    }

    // suite_id = suite_id ou numero
    public static function getCarrinhoSuite($id, $suite_id) {

        //$suite = Self::getSuite([
        //    ['id', '=', $suite_id]
        //]);

        $conta = Self::getConta($id);

        $sql = "EXEC DadosDoCarrinho " .
            " '" . $conta['codigo'] . "'," .
            " '" . $suite_id . "'";

        $rs = Self::callStoredProcedure($sql);

        // Log::Debug("============= RESPONSE =============");
        // Log::Debug($rs);
        // Log::Debug("============= RESPONSE =============");

        $produtos = array(
            'total' => 0,
            'items' => array()
        );

        if (!$rs) return $produtos;

        $total = 0;
        foreach($rs[0] as $item) {
            $produtos['total'] += Self::getTotal($item['Qtde'], $item['Preco']);
            $produtos['items'][] = array(
                'codigo' => $item['PkProduto'],
                'descricao' => $item['NomeProduto'],
                'foto' => '',
                'tipo' => 'produto',
                'quantidade' => $item['Qtde'],
                'preco' => $item['Preco'],
                'total' => Self::getTotal($item['Qtde'], $item['Preco']),
                'nota' => $item['DescObs'],
                'item_id' => $item['PkProduto'],
                'id' => $item['Item'],
                //'atendido' = $i['atendido'];
                //'created_at' = $i['created_at'];
                //'updated_at' = $i['updated_at'];
            );
        }

        return $produtos; // Produto::where('cardapio_id', $cardapio)->get();

        $suite = Suite::where([['id', '=', $suite_id]])
                    ->orWhere([
                        ['numero', '=', $suite_id],
                        ['conta_id', '=', $conta['id']]
                    ])->first();

        if ($suite == null) {
            throw new SuiteNaoEncontradaException();
        }

        $hospedagem = Self::getHospedagem($suite);
        $pedido = Self::getPedidoAberto($hospedagem);
        return Self::getItemsDoPedido([
            ['pedido_id', '=', $pedido['id']]
        ]);
    }

    public static function removeDoCarrinhoSuite($id, $suite_id, $item){

        //$suite = Self::getSuite([['id', '=', $suite_id]]);
        $conta = Self::getConta($id);

        $item = $item !== 'todos' ? $item : '0';

        $sql = "EXEC ExcluirItemDoCarrinho " .
            " '" . $conta['codigo'] . "'," .
            " '" . $suite_id . "'," .
            " '" . $item . "'";

        $rs = Self::callStoredProcedure($sql);

        //Log::Debug("============= RESPONSE =============");
        //Log::Debug($rs);
        //Log::Debug("============= RESPONSE =============");

        return Self::getCarrinhoSuite($id, $suite_id);

        return array(
            'total' => $total,
            'items' => []
        );

        $suite = Suite::where([['id', '=', $suite_id]])
                    ->orWhere([
                        ['numero', '=', $suite_id],
                        ['conta_id', '=', $conta['id']]
                    ])->first();

        if ($suite == null) {
            throw new SuiteNaoEncontradaException();
        }

        //$suite = Suite::where([['id', '=', $suite_id]])
        //            ->orWhere([['numero', '=', $suite_id]])->first();

        $hospedagem = Self::getHospedagem($suite);
        $pedido = Self::getPedidoAberto($hospedagem);

        $where = [
            [ 'pedido_id', '=', $pedido->id ]
        ];

        if ($item !== 'todos') {

            array_push($where, [ 'id', '=', $item ]);

        }

        ItemPedido::where($where)->delete();

        if ($pedido->items()->count() == 0) {
            Pedido::where([
                ['id', '=', $pedido->id]
            ])->delete();
        }

        return Self::getItemsDoPedido([
            ['pedido_id', '=', $pedido->id]
        ]);

    }

    public static function getApartamentos($id)
    {
        $conta = Self::getConta($id);

        $sql = 'EXEC ListaDeApartamentos "' . $conta['codigo'] . '"';

        $rs = Self::callStoredProcedure($sql);

        //Log::Debug("============= RESPONSE =============");
        //Log::Debug($rs);
        //Log::Debug("============= RESPONSE =============");

        $result = array();
        foreach ($rs[0] as $item) {
            if ($item['NumApto'] == 'NE') continue;
            $result[] = array(
                'id' => $item['NumApto'],
                'NrReserva' => $item['NrReserva'],
                'NrHospede' => $item['NrHospede'],
                'SitAtual' => $item['SitAtual'],
                'SitFutura' => $item['SitFutura'],
                'Notificacao' => $item['Notificacao']
            );
        }

        //Log::Debug($result);

        return $result;

    }

    public static function getNotificacoes($id)
    {

        return [];

        $conta = Self::getConta($id);

        $sql = 'EXEC ListaDeApartamentos "' . $conta['codigo'] . '"';

        $rs = Self::callStoredProcedure($sql);

        if (count($rs) < 2) return [];

        //Log::Debug("============= RESPONSE =============");
        //Log::Debug($rs);
        //Log::Debug("============= RESPONSE =============");

        $result = array();
        foreach ($rs[1] as $item) {
            if ($item['NumApto'] == 'NE') continue;
            $result[] = array(
                'suite' => $item['NumApto'],
                'data' => $item['DataNotificacao'],
                'notificacao' => $item['DescNotificacao']
            );
        }

        //Log::Debug($result);

        return $result;

    }

    public static function baixaAlarme($id, $suite, $funcionario) {
        $conta = Self::getConta($id);
        $sql = "EXEC BaixaAlerta " .
                " '" . $conta['codigo'] . "'," .
                " '" . str_pad($suite, 3, STR_PAD_LEFT) . "'," .
                " '" . $funcionario . "'";
        $rs = Self::callStoredProcedure($sql);
        return array();
    }

    public static function getAlarmes($id)
    {
        // return array(
        //     array(
        //     'suite' => 10,
        //     'funcionario' => 10,
        //     'nome' => 'Carlos',
        //     'mensagem' => 'Suíte 20 liberada para limpeza!'
        //     )
        // );

        $conta = Self::getConta($id);

        $sql = 'EXEC ListaDeApartamentos "' . $conta['codigo'] . '"';

        $rs = Self::callStoredProcedure($sql);

        // Log::Debug("============= RESPONSE =============");
        // Log::Debug($rs);
        // Log::Debug("============= RESPONSE =============");

        if (count($rs) < 3) return [];

        $result = array();
        foreach ($rs[2] as $item) {
            if ($item['NumApto'] == 'NE') continue;
            $result[] = array(
                'suite' => $item['NumApto'],
                'funcionario' => $item['PkCadFunc'],
                'nome' => $item ['Nome'],
                'mensagem' => $item['Mensagem']
            );
        }

        //Log::Debug($result);

        return $result;

    }

    public static function getCardapios($id)
    {
        $conta = Self::getConta($id);

        $sql = 'EXEC ListaDePDVs "' . $conta['codigo'] . '"';

        $rs = Self::callStoredProcedure($sql,
            new NenhumCardapioDefinidoException()
        );

        // Log::Debug("============= RESPONSE =============");
        // Log::Debug($rs);
        // Log::Debug("============= RESPONSE =============");

        $result = array();
        foreach ($rs[0] as $item) {
            $result[] = array('id' => $item['PkPDV'], 'descricao' => $item['NomePDV']);
        }

        return $result;

        $pdvs = Cardapio::where('conta_id', $conta->id)->get() ?? null;
        if ($pdvs == null) {
            throw new NenhumCardapioDefinidoException();
        }

        return $pdvs;

    }

    public static function trocarItemMesa($id, $item_id, $mesa_atual, $nova_mesa) {
        $item = ItemComanda::find($item_id);

        $comanda = Self::getComandaAberta(Self::getMesa($nova_mesa, Self::getConta($id)));
        $comanda->fechada = Carbon::now();
        $comanda->updated_at = Carbon::now();
        $comanda->save();

        $item->comanda_id = $comanda->id;
        $item->save();

        $comanda = Self::getComandaAberta(Self::getMesa($mesa_atual, Self::getConta($id)));
        return Self::getItemsDaComanda($comanda);
    }

    private static function getItemDaComanda($item_id) {
        $item = ItemComanda::find($item_id);
        if ($item == null) {
            throw new ItemDaComandaNaoEncontradoException();
        }
        return $item;
    }

    private static function fecharPedidoMesa($data) {
        $conta = Self::getConta($data['uuid']);
        $mesa = Self::getMesa($data['mesa'], $conta);
        $comanda = Self::getComandaAberta($mesa);
        $comanda->updated_at = Carbon::now();
        $comanda->fechada = Carbon::now();
        $comanda->save();
        return Self::getCarrinhoMesa($data['uuid'], $data['mesa']);
    }

    public static function fecharPedido($data) {

        $data = json_decode($data, true);

        //Log::Debug($data);

        if ($data['mesa']) {
            return Self::fecharPedidoMesa($data);
        }

        $conta = Self::getConta($data['uuid']);

        $sql = "EXEC FecharPedidoDoCarrinho " .
            " '" . $conta['codigo'] . "'," .
            " '" . $data['suite_id'] . "'," .
            " '" . $data['localizacao']['codigo'] . "'," .
            " '" . $data['observacao'] . "'"; //$data['localizacao']['descricao'] . "'";

        $rs = Self::callStoredProcedure($sql);

        // Log::Debug("============= RESPONSE =============");
        // Log::Debug($rs);
        // Log::Debug("============= RESPONSE =============");

        return Self::getCarrinhoSuite($data['uuid'], $data['suite_id']);

        $suite = Suite::where([ ['id', '=', $data['suite_id']] ])
                    ->orWhere([
                        ['numero', '=', $data['suite_id']],
                        ['conta_id', '=', $conta['id']]
                    ])->first();

        if ($suite == null) {
            throw new SuiteNaoEncontradaException();
        }

        //$suite = Self::getSuite([
        //    ['id', '=', $data['suite_id']]
        //]);

        $hospedagem = Self::getHospedagem($suite);
        $pedido = Self::getPedidoAberto($hospedagem);
        $pedido->updated_at = Carbon::now();
        $pedido->localizacao = $data['localizacao'];
        $pedido->observacao = $data['observacao'];
        $pedido->fechado = Carbon::now();
        $pedido->save();

        return Self::getCarrinhoSuite($data['uuid'], $data['suite_id']);
    }

    private static function getFuncionario($where) {
        $funcionario = Funcionario::where($where)->first();
        if ($funcionario == null) {
            throw new FuncionarioNaoEncontradoException();
        }
        return $funcionario;
    }

    private static function entrarFuncionario($login) {

        $conta = Self::getConta($login['id']);

        $sql = 'EXEC DadosDoFuncionario "' . $conta['codigo'] . '","' . $login['senha'] . '"';

        $rs = Self::callStoredProcedure(
            $sql,
            new FuncionarioNaoEncontradoException()
        );

        // Log::Debug("============= RESPONSE =============");
        // Log::Debug($rs);
        // Log::Debug("============= RESPONSE =============");

        if (count($rs) == 0) throw new FuncionarioNaoEncontradoException();

        $base = $rs[0][0];

        $result = array();
        $result['login'] = $base['Nome']; //$login['login'];
        $result['uuid'] = $login['id'];
        $result['conta_id'] = 0; // $conta['id'];
        $result['funcionario_id'] = $base['CodFunc']; //$funcionario['id'];
        $result['perfil'] = strtolower($base['Descricao']); //$funcionario['perfil'];
        $result['cardapios'] = $base['PDVs']; //0; // -1=nenhum cardapio; 0=todos; > 0=específicos

        return $result;

        $funcionario = Self::getFuncionario([
            ['conta_id','=', $conta['id']],
            ['login', '=', $login['login']]
        ]);

        if ($funcionario['senha'] !== $login['senha'])
        {
            throw new SenhaIncorretaException();
        }

        $result = array();
        $result['login'] = $login['login'];
        $result['uuid'] = $login['id'];
        $result['conta_id'] = $conta['id'];
        $result['funcionario_id'] = $funcionario['id'];
        $result['perfil'] = $funcionario['perfil'];
        $result['cardapios'] = 0; // -1=nenhum cardapio; 0=todos; > 0=específicos

        return $result;

    }

    private static function segundoNome($nome) {
        $nomes = explode(" ", $nome);
        // Log::Debug('============');
        // Log::Debug($nomes);
        // Log::Debug('============');
        if (count($nomes) == 0 || $nomes[0] == '') return '** SEM NOME **';
        return count($nomes) < 2 ? $nomes[0] : $nomes[0] . ' ' . $nomes[1];
    }

    private static function entrarHospede($login) {

        $conta = Self::getConta($login['id']);

        $sql = 'EXEC DadosDoHospede "' . $conta['codigo'] . '","' . $login['cpf'] .
            '","' . $login['suite'] . '"';

        $rs = Self::callStoredProcedure(
            $sql,
            new HospedagemNaoEncontradaException()
        );

        // Log::Debug("============= RESPONSE =============");
        // Log::Debug($rs);
        // Log::Debug("============= RESPONSE =============");

        try {

            $base = $rs[0][0];

        } catch (\Throwable $th) {

            throw new HospedagemNaoEncontradaException();

        }

        // $base = $rs[0][0];

        if ($login['suite'] !== $base['NumApto']) {
            throw new HospedagemNaoEncontradaException();
        }

        return array(
            'uuid' => $login['id'],
            'suite_id' => 0, //$suite['id'],
            'hospede' => Self::segundoNome($base['NomeHospede']),
            'NrHospede' => $base['NrHospede'],
            'reserva' => $base['NrReserva'],
            'NrReserva' => $base['NrReserva'],
            'cardapios' => !$base['PDVs'] ? 0 : $base['PDVs'],
            'cpf' => $base['Documento'],
            'suite' => $base['NumApto'],
            'numero' => $login['suite'],
            'checkin' => Self::getData($base['DtCheckin']),
            'checkout' => Self::getData($base['DtCheckout']),
            'hospedagem_id' => $base['NrReserva'], //0 //$hospedagem['id']
            'diarias' => $base['Diarias'],
            'extras' => $base['Extras'],
            'antecipado' => $base['Antecipado'],
            'taxas' => $base['TxServ'],
            'saldo' => $base['Saldo'],
        );

        $hospedagem = array();
        $hospedagem['uuid'] = $login['id'];
        $hospedagem['conta_id'] = 0;

        return $result;

        $suite = Self::getSuite([
            ['conta_id','=', $conta['id']],
            ['numero', '=', $login['suite']]
        ]);

        $hospedagem = Self::getHospedagem($suite);

        if ($hospedagem['cpf'] == $login['cpf']) {
            $hospedagem['uuid'] = $login['id'];
            $hospedagem['conta_id'] = $conta['id'];
            return $hospedagem;
        }

        abort(401);

        return;


    }

    public static function entrar($login) {

        $login = json_decode($login, true);

        if (array_key_exists('suite', $login)) return Self::entrarHospede($login);
        if (array_key_exists('login', $login)) return Self::entrarFuncionario($login);

        abort(400);

        return;

    }

    public static function insereItemMesa($data) {
        $item = json_decode($data, true);

        $conta = Self::getConta($item['uuid']);
        $mesa = Self::getMesa($item['mesa'], $conta);
        $comanda = Self::getComandaAberta($mesa);

        $comanda->updated_at = Carbon::now();
        $comanda->save();

        $itemComanda = new ItemComanda();
        $itemComanda->codigo = $item['codigo'];
        $itemComanda->descricao = $item['descricao'];
        $itemComanda->foto = $item['foto'];
        $itemComanda->preco = $item['preco'];
        $itemComanda->quantidade = $item['quantidade'];
        $itemComanda->nota = $item['nota'];
        $itemComanda->tipo = $item['tipo'];
        $itemComanda->funcionario = $item['funcionario'];
        $itemComanda->comanda_id = $comanda->id;
        $itemComanda->produto_id = $item['produto_id'];
        $itemComanda->funcionario_id = $item['funcionario_id'];
        $itemComanda->save();

        return $itemComanda;

    }

    public static function insereItem($data) {

        $item = json_decode($data, true);

        $conta = Self::getConta($item['uuid']);

        $sql = 'EXEC AdicionarItemNoCarrinho' .
            " '" . $conta['codigo'] . "'," .
            " '" . $item['suite'] . "'," .
            '' . $item['produto_id'] . ',' .
            '' . $item['preco'] . ',' .
            '' . $item['quantidade'] . ',' .
            '' . $item['funcionario_id'] . ',' .
            " 'O'," .
            " '" . $item['nota'] . "'," .
            '' . $item['hospede'] . ',' .
            '' . $item['cardapio'] . '';

        $rs = Self::callStoredProcedure($sql);

        // Log::Debug("============= RESPONSE =============");
        // Log::Debug($rs);
        // Log::Debug("============= RESPONSE =============");

        return array();

        if ($item['mesa']) {
            return Self::insereItemMesa($data);
        }

        $suite = null;

        if ($item['suite_id']) {
            $suite = Self::getSuite([
                ['id', '=', $item['suite_id']]
            ]);
        } else if ($item['suite']){
            $conta = Self::getConta($item['uuid']);
            $suite = Self::getSuite([
                ['numero', '=', $item['suite']],
                ['conta_id', '=', $conta['id']]
            ]);
        }

        /*
        $suite = Self::getSuite([
            ['id', '=', $item['suite_id']]
        ]);
        */

        $hospedagem = Self::getHospedagem($suite);
        $pedido = Self::getPedidoAberto($hospedagem);
        $pedido->updated_at = Carbon::now();
        //$pedido->funcionario = array_key_exists('funcionario', $item) ?
        //                            $item['funcionario'] : '';
        $pedido->save();

        $itemPedido = new ItemPedido();
        $itemPedido->codigo = $item['codigo'];
        $itemPedido->descricao = $item['descricao'];
        $itemPedido->foto = $item['foto'];
        $itemPedido->pedido_id = $pedido->id;
        $itemPedido->produto_id = $item['produto_id'];
        $itemPedido->preco = $item['preco'];
        $itemPedido->quantidade = $item['quantidade'];
        $itemPedido->nota = $item['nota'];
        $itemPedido->tipo = $item['tipo'];
        $itemPedido->save();

        return $itemPedido;

    }

    private static function getItemsDoPedido($where) {

        $total    = 0;
        $result   = array();
        $produtos = array();

        $itens = ItemPedido::where($where)->get() ?? null;

        if ($itens !== null) {
            foreach($itens as $i) {

                $item['codigo'] = $i['codigo'];
                $item['descricao'] = $i['descricao'];
                $item['foto'] = $i['foto'];
                $item['tipo'] = $i['tipo'];
                $item['quantidade'] = $i['quantidade'];
                $item['preco'] = $i['preco'];
                $item['total'] = Self::getTotal($i['quantidade'], $i['preco']);
                $item['nota'] = $i['nota'];
                $item['item_id'] = $i['id'];
                $item['id'] = $i['id'];
                $item['atendido'] = $i['atendido'];
                $item['created_at'] = $i['created_at'];
                $item['updated_at'] = $i['updated_at'];

                $total +=  $item['total'];
                array_push($produtos, $item);
            }
        }

        return array(
            'total' => $total,
            'items' => $produtos
        );
    }

    private static function getData($data) {
        if ($data == null) return null;
        return Carbon::parse($data)->format('d/m/Y H:i:s');
    }

    private static function removeConsumo($id) {
        $hospedagem = Self::getHospedagem($id);
        $excluidos = Pedido::where('hospedagem_id', $hospedagem['hospedagem_id'])->delete();
        return [ 'excluidos' =>  $excluidos ];
    }

    private static function removePedido($id, $hospedagem){

        $pedido = Self::getPedido($id);

        if (Self::atendidoEm($pedido['id']) !== null)
            throw new PedidoEntregueException();

        if($pedido['hospedagem_id'] != $hospedagem['hospedagem_id'])
            throw new PedidoNaoPertenceAHospedagemException();

        return [ 'excluidos' => Pedido::where('id', $id)->delete() ];

    }

    private static function removeItemPedido($id, $pedido_id, $hospedagem){

        $item = Self::getItemPedido($id);
        $pedido = Self::getPedido($item['pedido_id']);

        if ($pedido['id'] != $pedido_id)
            throw new ItemNaoPertenceAoPedidoException();

        if ($pedido['hospedagem_id'] != $hospedagem['hospedagem_id'])
            throw new PedidoNaoPertenceAHospedagemException();

        $excluidos = ItemPedido::where('id', $id)->delete();

        if (ItemPedido::where('pedido_id', $item['pedido_id'])->count() == 0)
            Pedido::where('id', $item['pedido_id'])->delete();

        return [ 'excluidos' =>  $excluidos ];
    }

    // ###################################################################################

    public static function callStoredProcedure($sql, $error = NULL) {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->query($sql);
        $rs = [];
        try {
            do {
                $rows = $stmt->fetchAll($pdo::FETCH_ASSOC);
                if ($rows) {
                    $rs[] = $rows;
                }
            } while ($stmt->nextRowset());

            if ($error && count($rs) == 0) throw $error;

        } catch (\Throwable $th) {

            Log::Debug('>>>>>>>>>>>>>>>>>>>>>>>>>>');
            Log::Debug($sql);
            Log::Debug('<<<<<<<<<<<<<<<<<<<<<<<<<<');

            throw $th;

        }

        return $rs;

    }

    public static function getConta($id) {

        $sql =  'EXEC DadosDoCliente "' . $id . '"';

        $rs = Self::callStoredProcedure($sql,
            new ContaNaoEncontradaException()
        );

        $conta = array();
        $conta['id'] = $id;
        $conta['sistema'] = strtoupper($rs[0][0]['Sistema']);
        $conta['hotel'] = $rs[0][0]['Nome'];
        $conta['codigo'] = $rs[0][0]['NrSerie'];
        $conta['ativo'] = $rs[0][0]['Ativo'];
        $conta['uuid'] = $id;
        $conta['obsLocal'] = $rs[0][0]['ObsLocalizacao'];
        $conta['locais_entrega'] = array();

        if (count($rs) == 1) return $conta;

        foreach($rs[1] as $item) {
            $conta['locais_entrega'][] = array(
                'codigo' => $item['Codigo'],
                'descricao' => $item['ObsLocalizacao'],
                'texto' => $item['TextoExplicativo'],
                'obrigatorio' => $item['Obrigatorio']
            );
        }

        if (count($rs) == 2) return $conta;

        foreach($rs[2] as $item) {

            $situacaoAtual = $item['SituacaoAtualCod'];

            if (!array_key_exists('status_suites', $conta)) {
                $conta['status_suites'] = array();
            }

            if (!array_key_exists($situacaoAtual, $conta['status_suites'])) {
                $conta['status_suites'][$situacaoAtual] = array();
            }

            $conta['status_suites'][$situacaoAtual][] = array(
                'id' => $item['SituacaoFuturaCod'],
                'descricao' => $item['DescBotao'],
                'mensagem' => $item['Pergunta']
            );

        }

        // Log::Debug("============= RESPONSE =============");
        // Log::Debug($conta);
        // Log::Debug("============= RESPONSE =============");

        return $conta;

        $conta = Conta::where('uuid', $id)->first();
        if ($conta == null) {
            throw new ContaNaoEncontradaException();
        }

        $conta['locais_entrega'] = [
            [ 'codigo' => '1', 'descricao' => 'Local 1'],
            [ 'codigo' => '2', 'descricao' => 'Local 2'],
            [ 'codigo' => '3', 'descricao' => 'Local 3'],
            [ 'codigo' => '4', 'descricao' => 'Local 4'],
        ];

        return $conta;
    }

    public static function getHospedagem($suite) {
        $hospedagem = Hospedagem::where([
            [ 'suite_id', '=', $suite['id'] ],
            [ 'checkout', '>=', Carbon::now() ]
        ])->first() ?? null;

        if ($hospedagem == null)
        {
            throw new HospedagemNaoEncontradaException();
        }

        return array(
            'suite_id' => $suite['id'],
            'reserva' => $hospedagem['reserva'],
            'hospede' => $hospedagem['hospede'],
            'cpf' => $hospedagem['cpf'],
            'suite' => $suite['numero'],
            'checkin' => Self::getData($hospedagem['checkin']),
            'checkout' => Self::getData($hospedagem['checkout']),
            'hospedagem_id' => $hospedagem['id']
        );

    }

    private static function atendidoEm($pedido_id) {
        $itens = ItemPedido::where('pedido_id', $pedido_id)->get() ?? null;
        $atendido = null;
        foreach($itens as $item) {
            if ($item->atendido == null) return null;
            if ($atendido == null) $atendido = $item->atendido;
            $d1 = DateTime::createFromFormat('Y-m-d H:i:s', $atendido);
            $d2 = DateTime::createFromFormat('Y-m-d H:i:s', $item->atendido);
            $atendido = $d1 > $d2 ? $d1 : $d2;
        }
        return Carbon::parse($atendido)->format('d/m/Y H:i:s');
    }

    public static function getPedidosMesa($id, $mesa){

        $conta = Self::getConta($id);
        $mesa = Self::getMesa($mesa, $conta);

        $comandas = Comanda::where([
            ['mesa_id', '=', $mesa['id']],
            ['fechada', '<>', null]
        ])->get(); // first() ?? null;

        if (!$comandas) return array(
            'numero' => $mesa['numero'],
            'mesa_id' => $mesa['id'],
            'total' => 0,
            'items' => array()
        );

        $result = array( 'mesa_id' => $mesa['id'], 'total' => 0, 'items' => array());

        foreach($comandas as $comanda){
            $items = Self::getItemsDaComanda($comanda);
            foreach($items['items'] as $item)
                array_push($result['items'], $item);
            $result['total'] += $items['total'];
        }

        return $result;

        $itens = Self::getItemsDaComanda($comanda);

        $comanda['numero'] = $mesa['numero']; // comanda não tem número!
        $comanda['realizado'] = Self::getData($comanda['created_at']);
        $comanda['fechada'] = ''; //Self::getData($comanda['fechado']);
        $comanda['atendido']  = ''; // comanda não controla atendimento de item por item!
        $comanda['mesa_id'] = $mesa['id'];
        $comanda['total'] = $itens['total'];
        $comanda['items'] = $itens['items'];

        return $comanda;

    }

    private static function adicionaNovoItem($pedidos, $pedido, &$total){

        $novo = array(
            'id' => $pedido['id'],
            'data' => $pedido->created_at->toDateString(),
            'items' => array()
        );

        $items = Self::getItemsDoPedido([
            ['pedido_id','=', $pedido['id']]
        ]);

        $total = 0;

        foreach($items['items'] as $item){
            array_push(
                $novo['items'],
                array(
                    'codigo' => $item['codigo'],
                    'descricao' => $item['descricao'],
                    'id' => $item['id'],
                    'nota' => $item['nota'],
                    'preco' => $item['preco'],
                    'quantidade' => $item['quantidade'],
                    'total' => $item['preco'] * $item['quantidade'],
                    'atendido' => $item['atendido'] ?? ''
                )
            );

            $total += ($item['preco'] * $item['quantidade']);

        }

        if (count($novo['items']) > 0) {
            array_push($pedidos, $novo);
        }

        return $pedidos;

    }

    private static function adicionaItemNaData($pedidos, $pedido, &$total) {

        $items = Self::getItemsDoPedido([
            ['pedido_id','=', $pedido['id']]
        ]);

        $total = 0;

        for ($i=0; $i < count($pedidos); $i++) {
            $p = $pedidos[$i];
            if ($p['data'] == $pedido['created_at']->toDateString()){
                foreach($items['items'] as $item) {
                    array_push($pedidos[$i]['items'], array(
                        'codigo' => $item['codigo'],
                        'descricao' => $item['descricao'],
                        'id' => $item['id'],
                        'nota' => $item['nota'],
                        'preco' => $item['preco'],
                        'quantidade' => $item['quantidade'],
                        'total' => $item['preco'] * $item['quantidade'],
                        'atendido' => $item['atendido'] ?? ''
                    ));
                    $total += $item['preco'] * $item['quantidade'];
                }

                return $pedidos;

            }

            $pedidos = Self::adicionaNovoItem($pedidos, $pedido, $total);
            return $pedidos;

        }


    }

    private static function adicionaItensNaData($pedidos, $pedido, &$total) {
        if (count($pedidos) == 0) {
            $pedidos = Self::adicionaNovoItem($pedidos, $pedido, $total);
            return $pedidos;
        }
        $pedidos = Self::adicionaItemNaData($pedidos, $pedido, $total);
        return $pedidos;
    }

    public static function getPedidosSuite($suite_id, $status = 'TODOS') {

        $suite = Self::getSuite([
            ['id', '=', $suite_id]
        ]);

        $hospedagem = Self::getHospedagem($suite);

        $pedidos = Pedido::where(
            'hospedagem_id', $hospedagem['hospedagem_id']
        )->get() ?? null;

        $result = array( 'suite_id' => $suite_id, 'total' => 0, 'pedidos' => array());

        if (!$pedidos) return $result;

        $total = 0;

        foreach($pedidos as $pedido) {

            $result['pedidos'] = Self::adicionaItensNaData(
                $result['pedidos'],
                $pedido,
                $total
            );

            $result['total'] += $total;

        }

        return $result;

        return array(
            'suite_id' => 10,
            'total' => 70,
            'pedidos' => array(
                [
                    'data' => '03 de abril',
                    'items' => array(
                        [
                            'codigo' => '20',
                            'descricao' => 'Produto 20',
                            'id' => 1,
                            'nota' => '',
                            'preco' => 70,
                            'quantidade' => 1,
                            'total' => 70,
                            'atendido' => Carbon::now()
                        ],[
                            'codigo' => '21',
                            'descricao' => 'Produto 21',
                            'id' => 2,
                            'nota' => '',
                            'preco' => 70,
                            'quantidade' => 1,
                            'total' => 70,
                            'atendido' => Carbon::now()
                        ]
                    ),
                ],[
                    'data' => '04 de abril',
                    'items' => array(
                        [
                            'codigo' => '22',
                            'descricao' => 'Produto 22',
                            'id' => 1,
                            'nota' => '',
                            'preco' => 70,
                            'quantidade' => 1,
                            'total' => 70,
                            'atendido' => null
                        ]
                    )
                ]
            )
        );

        $status = strtoupper($status);

        $suite = Self::getSuite([
            ['id', '=', $suite_id]
        ]);

        $hospedagem = Self::getHospedagem($suite);

        $pedidos =  Pedido::where('hospedagem_id', $hospedagem['hospedagem_id'])->get() ?? null;

        $result = array( 'suite_id' => $suite_id, 'total' => 0, 'pedidos' => array());

        foreach($pedidos as $p) {
            if ($p['fechado'] == null) continue;
            $atendidoEm = Self::atendidoEm($p['id']);
            if ($status == 'ABERTO' && $atendidoEm !== null) continue;
            if ($status == 'ATENDIDO' && $atendidoEm == null) continue;
            $pedido = array();
            $pedido['id'] = $p['id'];
            $pedido['numero'] = $p['numero'];
            $pedido['realizado'] = Self::getData($p['created_at']);
            $pedido['fechado'] = Self::getData($p['fechado']);
            $pedido['atendido']  = $atendidoEm;
            $itens = Self::getItemsDoPedido([
                ['pedido_id','=', $p['id']]
            ]);

            $pedido['total'] = $itens['total'];
            $pedido['items']  = $itens['items'];

            array_push($result['pedidos'], $pedido);

        }

        $total = 0;

        foreach($result['pedidos'] as $p){
            $total += $p['total'];
        }
        $result['total'] = $total;

        return $result;

    }

    public static function getProdutos($id, $cardapio) {

        $conta = Self::getConta($id);

        $sql = 'EXEC ListaDeProdutos "' . $conta['codigo'] . '","' . $cardapio . '"';

        $rs = Self::callStoredProcedure($sql);

        Log::Debug("============= RESPONSE =============");
        Log::Debug($rs);
        Log::Debug("============= RESPONSE =============");

        $produtos = [];

        if (count($rs) === 0) {
            return $produtos;
        }

        foreach($rs[0] as $item) {
            $produtos[] = array(
                'id' => $item['PkProduto'],
                'descricao' => $item['NomeProduto'],
                'preco' => $item['Preco'],
                'tipo' => $item['Nome'],
                'codigo' => $item['PkProduto'],
                'nota' => $item['Nota'],
                'imagem' => $item['Imagem']
            );
        }

        return $produtos; // Produto::where('cardapio_id', $cardapio)->get();
    }

    public static function removePedidoSuite($id, $pedido_id = null, $item_id = null) {
       if ($pedido_id == null && $item_id == null) return Self::removeConsumo($id);
       $hospedagem = Self::getHospedagem($id);
       if ($item_id == null) return Self::removePedido($pedido_id, $hospedagem);
       return Self::removeItemPedido($item_id, $pedido_id, $hospedagem);
    }

}
