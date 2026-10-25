<?php

namespace App\Exports;

use App\Models\PulmonaryLabour;
use App\Models\FyYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PulmonaryLaboursExport implements FromCollection, WithHeadings
{
    private $columns;
    private $status;
    private $districts;

    function __construct($columns = [], $status = null, $districts = null)
    {
        $this->columns = $columns;
        $this->status = $status;
        $this->districts = $districts;
    }

    public function headings(): array
    {
        $header = [];
        foreach ($this->columns as $key => $column) {
            $head = "";
            if ($column == "pulmonary_labour.id") {
                $head = "ID";
            } elseif ($column == "labours.name") {
                $head = "Name";
            } elseif ($column == "labours.father_name") {
                $head = "Father Name";
            } elseif ($column == "labours.dob") {
                $head = "Date of Birth";
            } elseif ($column == "labours.cnic") {
                $head = "CNIC";
            } elseif ($column == "labours.gender") {
                $head = "Gender";
            } elseif ($column == "labours.domicile_district") {
                $this->columns[$key] = "(select name from districts where d_id = labours.domicile_district) as domicile_district";
                $head = "Domicile District";
            } elseif ($column == "labours.cell_no_primary") {
                $head = "Cell No Primary";
            } elseif ($column == "pulmonary_labour.hospital_name") {
                $head = "Hospital Name";
            } elseif ($column == "pulmonary_labour.from_date") {
                $head = "From Date";
            } elseif ($column == "pulmonary_labour.disease") {
                $head = "Disease";
            } elseif ($column == "pulmonary_labour.to_date") {
                $head = "To Date";
            } elseif ($column == "pulmonary_labour.category") {
                $head = "Cetegory";
            } elseif ($column == "labours.mineral_title") {
                $head = "Mineral Title";
            } elseif ($column == "pulmonary_labour.fy_year") {
                $head = "Fy Year";
            } else {
                $head = $column;
            }

            array_push($header, $head);
        }

        return $header;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $fy = FyYear::first();
        $status = null;
        $districts = null;

        if ($this->status != "all") {
            $status = $this->status;
        }

        $districts = $this->districts;
        $data = [];
        if (count($this->columns) > 0) {
            $data = PulmonaryLabour::leftJoin("labours", "labours.l_id", "=", "pulmonary_labour.l_id")
                ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->selectRaw(join(",", $this->columns))
                ->when($status, function ($query, $status) {
                    return $query->where("status", $status);
                })
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("labours.lease_district_id", explode(",", $districts));
                })
                ->where("pulmonary_labour.fy_year", $fy->year)->get();
        } else {
            $data = [];
        }
        return $data;
    }
}
