<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulmonaryAnnualReport extends Model
{
    use HasFactory;
    protected $table = 'pulmonary_annual_test_report';
    protected $primaryKey = "id";
    public $timestamps = false;

    /*
    CREATE TABLE pulmonary_annual_test_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    l_id INT UNSIGNED NOT NULL,
    test_date DATE NOT NULL,
    severity_level ENUM('normal', 'Refer to Health Department') NOT NULL DEFAULT 'normal',
    remarks TEXT NULL,
    fy_year VARCHAR(9) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_pulmonary_labour
    FOREIGN KEY (l_id)
    REFERENCES labours(l_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    );
    */

    public function assignTo()
    {
        return $this->hasOne(Labour::class, "l_id", "l_id");
    }
}
