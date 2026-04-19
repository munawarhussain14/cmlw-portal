<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningArea extends Model
{
    use HasFactory;
    protected $table = 'mining_area';
    protected $primaryKey = "id";
    public $timestamps = false;
}
