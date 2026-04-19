<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralTitle extends Model
{
    use HasFactory;
    protected $table = 'mineral_title';
    protected $primaryKey = "id";
    public $timestamps = false;
}
