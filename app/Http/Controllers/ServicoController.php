<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ViewModels\ServicoViewModel;

class ServicoController extends Controller
{
    public function __construct()
    {
    }

    public function alteraStatusSuite($id, $suite, $status) 
    {
        return response()->json(ServicoViewModel::alteraStatusSuite($id, $suite, $status));
    } 

}
