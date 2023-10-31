<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelDados extends Model
{
    //use HasFactory;

    protected $table = 'ClientesDados';
    protected $primaryKey = 'PkClientesDados';
    public $incrementing = false;
    public $timestamps = false;
    
    public function __construct()
    {
    }

}
