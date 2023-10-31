<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    
    protected $connection = 'sqlite';
    
    use HasFactory;

    public function __construct($hospedagem_id = null)
    {
        parent::__construct();
        $this->hospedagem_id = $hospedagem_id;        
    }

    public function permiteExclusao($hospedagem)
    {
        return !isset($this->atendido) && 
                $this->hospedagem_id == $hospedagem['hospedagem_id'];
    }

    public function items()
    {
        return $this->hasMany(ItemPedido::class);
    }

}
