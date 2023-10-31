<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    protected $table = 'ClientesLetoh';
    protected $primaryKey = 'PkCliente';
    public $timestamps = false;

    public function __construct($nome = null)
    {
        parent::__construct();
        $this->Nome = $nome;
        $this->CodCli = 0;
        $this->NrSerie = 0;
        $this->DtNasc = '';
    }

    private function formataData($data)
    {
        $ano = substr($data,0,4);
        $mes = substr($data,5,2);
        $dia = substr($data,8,2);

        return $ano . '-' . $mes . '-' . $dia;
    }

    public function dtnasc(){
        return $this->formataData($this->DtNasc);
    }

    public function insert()
    {
        $id = DB::table($this->table)->InsertGetId($this->toArray());
        return self::where($this->primaryKey, $id)->first();
    }
}
