<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;

class Minerals extends Model
{
    use HasFactory;
    protected $table = 'minerals';
    protected $primaryKey = "m_id";
    public $timestamps = false;

}