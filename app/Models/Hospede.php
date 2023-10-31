<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospede extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'PkHospedes';

    public function __construct()
    {
        parent::__construct();
        $this->NrHospede = 0;
        $this->NomeHospede = null;
    }


    public function NomeHospede(): string
    {
        $nome = explode(' ', $this->NomeHospede);
        if (count($nome) >= 2) return $nome[0] . ' ' . $nome[1];
        return $nome[0];
    }

    public function semNome(): bool 
    {
        return $this->NomeHospede == null;
    }
}
