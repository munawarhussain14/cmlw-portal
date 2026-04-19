<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\SchemeScope;


class Scheme extends Model
{
    use HasFactory;

    // protected static function booted()
    // {
    //     static::addGlobalScope(new SchemeScope);
    // }

    public function type()
    {
        return $this->hasOne(SchemeType::class, "id", "scheme_type_id");
    }

    // protected function lastDate(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $this->date($value)
    //     );
    // }

    private function date($value)
    {
        $date = date_create($value);
        return date_format($date, "d-m-Y");
    }
}
