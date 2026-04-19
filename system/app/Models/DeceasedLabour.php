<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\GrantLabourScope;


class DeceasedLabour extends Model
{
    use HasFactory;

    protected $fillable = [
        "cause",
    ];

    protected static function booted()
    {
        static::addGlobalScope(new GrantLabourScope);
    }

    protected $table = "death_grants";

    public function labour()
    {
        return $this->hasOne(Labour::class, "l_id", "labour_id")->withoutGlobalScopes();
    }
}
