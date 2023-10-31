<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();
    }

    function numero()
    {
        return $this->NrReserva;
    }
    
    function ocupacao()
    {
        $adultos = $this->QtdAdultos . ' adulto(s).';
        $criancas = $this->QtdCriancas > 0 ? ',' . $this->QtdCriancas . ' criança(s)' : '';
        return '1 quarto(s), ' . $adultos . $criancas;
    }

    private function formataData($data)
    {
        $ano = substr($data,0,4);
        $mes = substr($data,5,2);
        $dia = substr($data,8,2);
        $hora = substr($data, 11,2);
        $min = substr($data,14,2);

        return $dia . '/' . $mes . '/' . $ano . ' às ' . $hora . ':' . $min;
    }

    function checkin()
    {
        return $this->formataData($this->DtPrevInicio);
    }

    function checkout() 
    {
        return $this->formataData($this->DtPrevTermino);
    }
}
