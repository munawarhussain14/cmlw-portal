<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\FyScope;

class FyYear extends Model
{
    use HasFactory;
    protected $table = "fianancial_years";

    protected static function booted()
    {
        static::addGlobalScope(new FyScope);
    }

    public function getyearAttribute($value)
    {
        $year = $value;
        if (request()->session()->has('year')) {
            $year = request()->session()->get('year');
        }
        return $year;
    }
    
    public function getYear($value)
    {
        $year = $value;
        if (request()->session()->has('year')) {
            $year = request()->session()->get('year');
        }
        return $year;
    }
    
    public function getActualYear()
    {
        return $this->attributes['year'];
    }
}
