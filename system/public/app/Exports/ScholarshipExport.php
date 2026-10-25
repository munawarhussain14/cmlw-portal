<?php

namespace App\Exports;

use App\Models\Scholarship;
use App\Models\FyYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScholarshipExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $columns;
    private $status;
    private $districts;
    private $scheme;

    function __construct($columns = [], $status = null, $districts = null, $scheme = null)
    {
        $this->columns = $columns;
        $this->status = $status;
        $this->districts = $districts;
        $this->scheme = $scheme;
    }

    public function headings(): array
    {
        $header = [];
        foreach ($this->columns as $column) {
            $head = "";
            if ($column == "children.id") {
                $head = "ID";
            } elseif ($column == "children.name") {
                $head = "Name";
            } elseif ($column == "children.reg_no") {
                $head = "Form-B/Reg No";
            } elseif ($column == "children.dob") {
                $head = "Date of Birth";
            } elseif ($column == "children.gender") {
                $head = "Gender";
            } elseif ($column == "labours.name as father_name") {
                $head = "Father Name";
            } elseif ($column == "labours.cell_no_primary") {
                $head = "Cell No";
            } elseif ($column == "labours.cnic") {
                $head = "Father CNIC";
            }elseif ($column == "domicile_district.name as domicile_district") {
                $head = "Domicile District";
            }elseif ($column == "scholarship_apply.class") {
                $head = "Class";
            } elseif ($column == "districts.name as lease_district") {
                $head = "Lease District";
            }elseif ($column == "scholarship_apply.status") {
                $head = "Status";
            } else {
                $head = $column;
            }
            array_push($header, $head);
        }

        array_push($header, "Fy Year");
        array_push($this->columns, "scholarship_apply.fy_year");

        return $header;
    }

    public function collection()
    {
        $fy = FyYear::first();

        $status = null;
        $districts = null;

        if ($this->status != "all") {
            $status = $this->status;
        }

        $districts = $this->districts;

        $scheme = $this->scheme;

        $data = Scholarship::leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->leftJoin("districts as domicile_district", "labours.domicile_district", "=", "domicile_district.d_id")
            ->selectRaw(join(",", $this->columns))
            ->when($status, function ($query, $status) {
                return $query->where("status", $status);
            })
            ->when($districts, function ($query, $districts) {
                return $query->whereIn("labours.lease_district_id", explode(",", $districts));
            })
            ->when($scheme, function ($query, $scheme) {
                if ($scheme == "Quality-Education") {
                    return $query->where("category", ["Medical", "Engineering"]);
                } else {
                    return $query->where("category", $scheme);
                }
            })
            ->where("scholarship_apply.fy_year", $fy->year)->get();

        return $data;
    }
}
