<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    use HasFactory;
    protected $table = 'work_types';
    protected $primaryKey = "w_id";
    public $timestamps = false;
}
