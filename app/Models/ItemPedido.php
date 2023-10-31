<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    protected $connection = 'sqlite';
    public $timestamps = false;
    
    use HasFactory;

    public function __construct()
    {
        parent::__construct();
    }

}
