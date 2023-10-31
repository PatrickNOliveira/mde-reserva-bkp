<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Hotel extends Model
{
    protected $table = 'Clientes';
    protected $primaryKey = 'PkCliente';
    public $incrementing = false;
    public $timestamps = false;
    
    //public $Fone;
    //public $Email;

    public function __construct()
    {
    }

    function nome()
    {
        return $this->NomeFantasia;
    }

    function fone()
    {
        return $this->Fone;
    }

    function email()
    {
        return $this->Email;
    }

    function logo(){
        $file = storage_path('images/logos/' . $this->NrSerie . '.png');
        return File::exists($file) ? "/logo/" . $this->NrSerie . ".png"  : null;
    }

}
