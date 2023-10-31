<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Comanda extends Model
{
    use HasFactory;
    protected $connection = 'sqlite';
    protected $table = 'comandas';

    public function __construct($mesa_id = null)
    {
        parent::__construct();
        $this->mesa_id = $mesa_id;
    }

    public function items()
    {
        return $this->hasMany(ItemComanda::class);
    }
    
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
}
