<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\GrantLabourScope;

class DisableLabour extends Model
{
    use HasFactory;

    protected $table = "disabled_labour";

    protected $fillable = [
    	"disability",
    	"l_id"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new GrantLabourScope);
    }

    public function labour()
    {
        return $this->hasOne(Labour::class,"l_id","l_id")->withoutGlobalScopes();
    }
}
