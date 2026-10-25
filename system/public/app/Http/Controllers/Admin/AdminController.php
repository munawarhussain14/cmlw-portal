<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\DisableLabour;
use App\Models\PulmonaryLabour;
use App\Models\MarriageGrant;
use App\Exports\ScholarshipExport;
use App\Exports\DiplomaExport;
use App\Models\DeceasedLabour;
use Maatwebsite\Excel\Facades\Excel;
//use Excel;

class AdminController extends Controller
{
    private $module;
    function __construct($module = "General")
    {
        $this->module = $module;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $name = Route::currentRouteName();
            if (str_contains($name, "index")) {
                if (!auth()->user()->can("read-" . $this->module)) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    function ChangeFyYear(Request $request)
    {
        if ($request->has("fy_year")) {
            $request->session()->put('year', $request->fy_year);
        }

        return redirect(route("admin.dashboard"));
    }

    function exportScholarship(Request $request, $scheme)
    {
        $name = "sheet.xlsx";
        if ($scheme == "General") {
            $name = "General Scholarship.xlsx";
        } elseif ($scheme == "Special") {
            $name = "Special Children.xlsx";
        } elseif ($scheme == "Top") {
            $name = "Top 50.xlsx";
        } elseif ($scheme == "Quality-Education") {
            $name = "Quality Education.xlsx";
        }

        $status = null;
        $districts = [];

        if ($request->has("status")) {
            $status = $request->status;
        }

        if ($request->has("districts")) {
            $districts = $request->districts;
        }

        return Excel::download(new ScholarshipExport($request->columns, $status, $districts, $scheme), $name);
    }

    function exportDiploma(Request $request, $scheme)
    {
        $name = "sheet.xlsx";
        if ($scheme == "gems-and-gemology") {
            $name = "Gems and Gemology.xlsx";
        } elseif ($scheme == "lapidary") {
            $name = "Lapidary.xlsx";
        }

        $status = null;
        $districts = [];

        if ($request->has("status")) {
            $status = $request->status;
        }

        if ($request->has("districts")) {
            $districts = $request->districts;
        }

        return Excel::download(new DiplomaExport($request->columns, $status, $districts, $scheme), $name);
    }

    public function setTitle($title)
    {
        $module = $title;
    }

    function status($status)
    {
        $response = "";
        if ($status == "pending") {
            $response .= "<i class='fa fa-pause-circle text-warning'></i>";
        } else if ($status == "in process") {
            $response .= "<i class='fa fa-clock text-primary'></i>";
        } else if ($status == "in-progress") {
            $response .= "<i class='fa fa-clock text-primary'></i>";
        } else if ($status == "approved") {
            $response .= "<i class='fa fa-check text-success'></i>";
        }else if ($status == "resolved") {
            $response .= "<i class='fas fa-clipboard-check text-success'></i>";
        } else if ($status == "rejected") {
            $response .= "<i class='fa fa-times text-danger'></i>";
        } else if ($status == "document verified") {
            $response .= "<i class='fas fa-clipboard-check text-success'></i>";
        } else if ($status == "complete") {
            $response .= "<i class='fas fa-clipboard-check text-success'></i>";
        }else if ($status == "overage") {
            $response .= "<i class='fa fa-exclamation-triangle text-danger'></i>";
        }

        return $response;
    }

    function changeStatus(Request $request)
    {
        $cat = $request->category;
        $id = $request->id;
        if ($cat == "General") {
            return redirect(route("admin.scholarships.general.edit", ["general" => $id]))->with("warning", "Update form and submit to change status");
        } elseif ($cat == "Engineering" || $cat == "Medical") {
            return redirect(route("admin.scholarships.quality-education.edit", ["quality_education" => $id]))->with("warning", "Update form and submit to change status");
        } elseif ($cat == "Special") {
            return redirect(route("admin.scholarships.special-education.edit", ["special_education" => $id]))->with("warning", "Update form and submit to change status");
        } elseif ($cat == "Top") {
            return redirect(route("admin.scholarships.top-position.edit", ["top_position" => $id]))->with("warning", "Update form and submit to change status");
        }
    }

    public function updateDisableAction(Request $request, $id)
    {
        // dd($request->all());
        $user_id = Auth::getUser()->id;
        $rules = [
            'status' => 'required|max:255',
            "cnic_status" => "required",
            "appointment_letter_status" => "required",
            "schedule_a_status" => "required",
            "enquiry_report_status" => "required",
            "xray_status" => "required",
            "disability_cert_status" => "required",
            "disability_percent" => "required",
        ];

        if ($request->status == "rejected") {
            $rules["remarks"] = "required|max:50";
        }

        $validated = $request->validate($rules);


        /*
         LabourLog::create([
             "user_id"=>$user_id,
             "apply_id"=>$id,
             "purpose"=>"scholarship",
             "action"=>$request->status,
             "remarks"=>$request->remarks
            ]);*/

        $scholar = DisableLabour::withoutGlobalScopes()->find($id);
        $scholar->status = $request->status;
        $scholar->cnic_status = $request->cnic_status;
        $scholar->appointment_letter_status = $request->appointment_letter_status;
        $scholar->schedule_a_status = $request->schedule_a_status;
        $scholar->enquiry_report_status = $request->enquiry_report_status;
        $scholar->xray_status = $request->xray_status;
        $scholar->disability_cert_status = $request->disability_cert_status;
        $scholar->disability_percent = $request->disability_percent;
        $scholar->doc_verify_by = $user_id;

        if ($request->status != "pending") {
            $scholar->form_received = true;
        }

        if ($request->has("remarks")) {
            $scholar->remarks = $request->remarks;
        }

        $scholar->save();

        return redirect()->back()->with("success", "Application Status Updated!");
    }

    public function updatePulmonaryAction(Request $request, $id)
    {
        $user_id = Auth::getUser()->id;
        $rules = [
            'status' => 'required|max:255',
            "cnic_status" => "required",
            "appointment_letter_status" => "required",
            "schedule_a_status" => "required",
            "enquiry_report_status" => "required",
            "hospital_status" => "required",
            "category" => "required",
        ];

        if ($request->status == "rejected") {
            $rules["remarks"] = "required|max:50";
        }

        $validated = $request->validate($rules);


        /*
         LabourLog::create([
             "user_id"=>$user_id,
             "apply_id"=>$id,
             "purpose"=>"scholarship",
             "action"=>$request->status,
             "remarks"=>$request->remarks
            ]);*/
        $row = PulmonaryLabour::withoutGlobalScopes()->find($id);
        $row->status = $request->status;
        $row->cnic_status = $request->cnic_status;
        $row->appointment_letter_status = $request->appointment_letter_status;
        $row->schedule_a_status = $request->schedule_a_status;
        $row->enquiry_report_status = $request->enquiry_report_status;
        $row->hospital_status = $request->hospital_status;
        $row->category = $request->category;
        $row->doc_verify_by = $user_id;

        if ($request->status != "pending") {
            $row->form_received = true;
        }

        if ($request->has("remarks")) {
            $row->remarks = $request->remarks;
        }

        $row->save();

        return redirect()->back()->with("success", "Application Status Updated!");
    }

    public function updateMarriageAction(Request $request, $id)
    {
        $user_id = Auth::getUser()->id;
        $rules = [
            'status' => 'required|max:255',
            "cnic_status" => "required",
            "frc_status" => "required",
            "father_cnic_status" => "required",
            "husband_cnic_status" => "required",
            "marriage_certificate" => "required",
            "affidavit" => "required",
            "schedule_a_status" => "required",
            "appointment_letter_status" => "required"
        ];

        if ($request->status == "rejected") {
            $rules["remarks"] = "required|max:50";
        }

        $validated = $request->validate($rules);

        /*
         LabourLog::create([
             "user_id"=>$user_id,
             "apply_id"=>$id,
             "purpose"=>"scholarship",
             "action"=>$request->status,
             "remarks"=>$request->remarks
            ]);*/
        $row = MarriageGrant::withoutGlobalScopes()->find($id);
        $row->status = $request->status;
        $row->cnic_status = $request->cnic_status;
        $row->frc_status = $request->frc_status;
        $row->father_cnic_status = $request->father_cnic_status;
        $row->husband_cnic_status = $request->husband_cnic_status;
        $row->marriage_certificate = $request->marriage_certificate;
        $row->affidavit = $request->affidavit;
        $row->schedule_a_status = $request->schedule_a_status;
        $row->appointment_letter_status = $request->appointment_letter_status;
        $row->doc_verify_by = $user_id;

        if ($request->status != "pending") {
            $row->form_received = true;
        }

        if ($request->has("remarks")) {
            $row->remarks = $request->remarks;
        }

        $row->save();

        return redirect()->back()->with("success", "Application Status Updated!");
    }

    public function updateDeceasedAction(Request $request, $id)
    {
        $row = DeceasedLabour::withoutGlobalScopes()->find($id);
        $user_id = Auth::getUser()->id;
        $rules = [
            'status' => 'required|max:255',
            "cnic_status" => "required",
            "death_cert_status" => "required",
            "succession_status" => "required",
            "schedule_a_status" => "required",
            "leaseholder_report_status" => "required",
            "inquiry_report_status" => "required",
        ];

        if ($row->labour->married === "yes") {
            $rules["form_b_status"] = "required";
        }

        if ($request->status == "rejected") {
            $rules["remarks"] = "required|max:50";
        }

        $validated = $request->validate($rules);

        $row->status = $request->status;
        $row->cnic_status = $request->cnic_status;
        $row->death_cert_status = $request->death_cert_status;
        $row->succession_status = $request->succession_status;
        $row->schedule_a_status = $request->schedule_a_status;
        $row->leaseholder_report_status = $request->leaseholder_report_status;
        $row->inquiry_report_status = $request->inquiry_report_status;
        $row->doc_verify_by = $user_id;

        if ($request->status != "pending") {
            $row->form_received = true;
        }

        if ($request->has("form_b_status")) {
            $row->form_b_status = $request->form_b_status;
        }

        if ($request->has("remarks")) {
            $row->remarks = $request->remarks;
        }

        $row->save();

        return redirect()->back()->with("success", "Application Status Updated!");
    }

    /*function exportExcel($title,$data){
        Excel::create($title, function($excel) {

            // Set the title
            $excel->setTitle('My awesome report 2016');

            // Chain the setters
            $excel->setCreator('Me')->setCompany('Our Code World');

            $excel->setDescription('A demonstration to change the file properties');

            $data = [12,"Hey",123,4234,5632435,"Nope",345,345,345,345];

            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray($data, NULL, 'A3');
            });

        })->download('xlsx');
    }*/
}
