<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulmonaryAnnualReport extends Model
{
    use HasFactory;

    /**
     * Table name must match your database (e.g. pulmonary_anual_test_report if you used that spelling).
     */
    protected $table = 'pulmonary_annual_test_report';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'l_id',
        'test_date',
        'severity_level',
        'remarks',
        'fy_year',
    ];

    public function labour()
    {
        return $this->belongsTo(Labour::class, 'l_id', 'l_id');
    }
}
