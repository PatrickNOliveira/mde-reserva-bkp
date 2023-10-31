<?php

namespace App\ViewModels;

use Illuminate\Support\Facades\Log;

use Spatie\ViewModels\ViewModel;

class ServicoViewModel extends ViewModel
{
    public function __construct()
    {
    }
    
    public static function alteraStatusSuite($id, $suite, $status)
    {

        $conta = PedidoViewModel::getConta($id);

        $sql = 'EXEC AlteraSituacaoApartamento  "' . $conta['codigo'] . '","' . $suite . '","' . $status . '"';
        
        $rs = PedidoViewModel::callStoredProcedure($sql);

        Log::Debug("============= RESPONSE =============");
        Log::Debug($rs);
        Log::Debug("============= RESPONSE =============");
        
        return [];

    }
}