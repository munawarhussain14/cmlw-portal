<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    
    protected $table = 'banks';
    protected $primaryKey = 'b_id';
    
    protected $fillable = [
        'name',
        'code',
        'active'
    ];
    
    protected $casts = [
        'active' => 'boolean'
    ];
}
