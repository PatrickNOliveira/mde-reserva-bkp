<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suite extends Model
{
    use HasFactory;
    
    protected $connection = 'sqlite';
    public $timestamps = false;
    
    public function __construct()
    {
        parent::__construct();
    }

}
