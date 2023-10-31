<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\ViewModels\ReservaViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Hospede;
use App\Models\Cliente;
use App\Models\Reserva;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DB;

class ReservaController extends Controller
{
    
    public function __construct()
    {
    }

    private function getViewModel($idReserva = 0, $idHospede = 0, $pos = 0): ReservaViewModel {
        try {

            return new ReservaViewModel($idReserva, $idHospede, $pos);

        } catch (Exception $e) {

            return view('home')->with(array('msg' => $e->getMessage()));

        }
    }

    private function getCliente($idCliente) {

    }

    public function home()
    {
        return view('home');
    }

    public function index($idReserva = 0) 
    {
        
        //Cliente::truncate();
        //Hospede::truncate();
        //Hospede::whereIn('PkHospedes', [2,3,4])->delete();
        //Reserva::whereIn('PkReserva', [2,3,4,5])->delete();

        $viewModel = $this->getViewModel($idReserva);
        return view('reserva', compact('viewModel'));

    } 

    public function hospedes($idReserva = 0)
    {
        $viewModel = $this->getViewModel($idReserva);
        return view('hospedes', compact('viewModel'));        
    }

    public function cadastro($idReserva = 0, $idHospede = 0, $pos = 1) 
    {
        $viewModel = $this->getViewModel($idReserva, $idHospede, $pos);
        return view('cadastro', compact('viewModel'));
    }  

    private function getHospedeMaster($reserva)
    {
        return Hospede::where('NrSerie', $reserva->NrSerie)
                        ->where('NrReserva', $reserva->NrReserva)
                        ->where('Hosp', 'M')->first();
    }

    private function getCPF($data)
    {
        return preg_replace('/[^0-9]/', '', $data);
    }

    private function filter($data, $filter, $cleanup = true)
    {
        $obj = array();
        foreach($data as $key => $value){
            if (strpos($key, $filter) === FALSE) continue;
            $key = $cleanup ? str_replace($filter, '', $key) : $key;
            $obj[$key] = $value;
        }
        return $obj;
    }

    private function update($obj, $data)
    {
        foreach($data as $key => $value)
            $obj[$key] = $value;
        return $obj;
    }

    private function salvarFoto($request, $idCliente) {

        $image = $request->file('imageUpload');
        if (!$image) return null;

        $request->validate([
            'imageUpload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $folder = storage_path('images/fotos/');
        $name =  $request->input('id') . '_' . 
                 $idCliente  . '.' . 
                 $image->getClientOriginalExtension();

        $request->imageUpload->move($folder, $name);
        
        return $name;

    }

    public function salvar(Request $request)
    {

        $viewModel = $this->getViewModel($request->id, $request->hospede);

        $reserva = $viewModel->reserva;
        $hospede = $viewModel->hospede;
        $cliente = Cliente::where("Cgccpf", $request->cliente_CgcCpf)->first() ?? $viewModel->cliente;

        $cliente = $this->update($cliente, $this->filter($request->input(), 'cliente_'));
        $cliente->Status = 'E';

        $cliente->NrSerie = $viewModel->reserva()->NrSerie;
        if ($cliente->CodCli == 0) {
            $cliente = $cliente->insert();
        } else {
            $cliente->save();
        }

        $cliente->Foto = $this->salvarFoto($request, $cliente->CodCli) ?? $cliente->Foto;
        $cliente->save();

        $hospede = $this->update($hospede, $this->filter($request->input(), 'hospede_'));

        $hospede->CodCliHosp  = $cliente->CodCli;
        $hospede->NrReserva   = $reserva->NrReserva;
        $hospede->NrSerie     = $reserva->NrSerie;
        $hospede->NrControle  = $reserva->NrControle;
        $hospede->NumApto     = $reserva->NumApto;
        $hospede->NomeHospede = $cliente->Nome;
        $hospede->Status      = 'E';
        
        $hospede->save();
        
        return redirect('hospedes/' . $request->id);

    }

    public function camera($idCliente = null, $idReserva = null){

        $path = storage_path('/images/fotos/');
        $cliente = Cliente::where("CodCli", $idCliente)->first();
        $foto = $cliente ? $path . $cliente->Foto : null;

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

}
