<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

use App\Models\Reserva;
use App\Models\Hotel;
use App\Models\HotelDados;
use App\Models\Hospede;
use App\Models\Cliente;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

use Exception;

class ReservaViewModel extends ViewModel
{

    public function __construct($id = 0, $idHospede = 0, $pos = 0)
    {
        
        $this->Pos = $pos;

        $this->reserva = Reserva::where('ChaveIdentificacao', $id)->first() ?? null;

        if ($this->reserva == null)
        {
            throw new Exception('Reserva não encontrada!');
            return;
        }

        $this->hotel =  Hotel::where('NrSerie', $this->reserva->NrSerie)->first() ?? null;

        if ($this->hotel == null)
        {
            throw new Exception('Hotel não encontrado!');
            return;
        }

        $this->hotelDados = HotelDados::where('NrSerie', $this->reserva->NrSerie)->first() ?? null;

        if ($this->hotelDados !== null){
            $this->hotel->Fone = $this->hotelDados->FoneCom;
            $this->hotel->Email = $this->hotelDados->EmailPreCheckin;
        }

        $this->hospedes = $this->getHospedes($this->reserva->NrReserva);

        $this->hospede = $this->getHospede($idHospede, $this->reserva->NrSerie);
        
        $this->cliente = $this->getCliente($this->hospede, $this->reserva->NrSerie);

    }

    private function getHospede($id, $nrSerie): Hospede {
        return Hospede::where([
            [ 'NrHospede', '=', $id ],
            [ 'NrSerie', '=', $nrSerie ]
        ])->first() ?? new Hospede();
        //return Hospede::where('NrHospede', $id)->first() ?? new Hospede();
    }

    private function getCliente($hospede, $nrSerie): Cliente {

        $cliente = Cliente::where([
            [ 'CodCli', '=', $hospede->CodCliHosp ],
            [ 'NrSerie', '=', $nrSerie ]
        ])->first() ?? null;

        //$cliente = $hospede->CodCliHosp ? 
        //           Cliente::where("CodCli", $hospede->CodCliHosp)->first() : null;

        if ($cliente) return $cliente;

        return new Cliente($hospede->NomeHospede);
    }

    private function getHospedes($idReserva): Collection
    {
        
        $hospedes = Hospede::where('NrReserva', $idReserva)->get();

        for ($i = $hospedes->count() + 1; $i <= $this->reserva->QtdAdultos; $i++)
        {
            $hospedes->add(new Hospede());
        }

        return $hospedes;

    }

    public function reserva(): Reserva
    {
        return $this->reserva;
    }

    public function hotel(): Hotel
    {
        return $this->hotel;
    }

    public function hospedes(): Collection
    {
        return $this->hospedes;
    }

    public function hospede(): Hospede
    {
        return $this->hospede;
    }

    public function cliente(): Cliente
    {
        return $this->cliente;
    }
}
