<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ScholarshipScope;

class Diploma extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new ScholarshipScope);
    }

    public function scheme()
    {
        return $this->hasOne(Scheme::class,"id","scheme_id");
    }

    public function student()
    {
        return $this->hasOne(Children::class,"id","child_id");
    }
}