<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemComanda extends Model
{
    use HasFactory;

    protected $connection = 'sqlite';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function comanda()
    {
        return $this->belongsTo(Comanda::class);
    }

}
