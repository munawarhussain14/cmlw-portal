<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\District;
use App\Models\Bank;
use App\Models\Minerals;
use App\Models\WorkType;
use App\Http\Requests\LabourRequest;
use DB;
use App\Helper\DatatableHelper;
use Auth;
use App\Exports\LaboursExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\LabourCNICRequest;
use App\Http\Requests\DisableTestRequest;

class LabourController extends AdminController
{
    private $params = [
        "basic" => "admin/labours",
        "dir" => "admin.labours",
        "route" => "admin.labours",
        "model" => "labour",
        "singular_title" => "Mine Labour",
        "plural_title" => "Mine Labours",
        "module_name" => "labours",
        "upload_dir" => "labours",
        "columns" => [
            ["data" => 'l_id', "name" => 'l_id', "title" => "ID"],
            ["data" => 'name', "name" => 'name', "title" => "Name"],
            ["data" => 'father_name', "name" => 'father_name', "title" => "Father Name"],
            ["data" => 'cnic', "name" => 'cnic', "title" => "CNIC"],
            ["data" => 'labour_status', "name" => 'labour_status', "orderable" => "false", "searchable" => "false", "title" => "Status"],
            ["data" => 'action', "name" => 'action', "orderable" => "false", "searchable" => "false", "title" => "Action"],
        ]
    ];

    public function __construct()
    {
        parent::__construct($this->params["module_name"]);
    }

    function find($id = 0)
    {
        if ($id) {
            return Labour::withoutGlobalScopes()->find($id);
        } else {
            return new Labour;
        }
    }

    function allWithScope($columns = "*")
    {
        return Labour::select($columns);
    }

    function all($columns = "*")
    {
        return Labour::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {

        $user_id = Auth::getUser()->id;
        $table = $this->find($id);

        $table->name = $request->name;
        $table->cnic = $request->cnic;
        $table->father_name = $request->fathername;

        $table->dob = "$request->dob_year-$request->dob_month-$request->dob_day";

        $table->cell_no_primary = $request->cell_no_primary;
        $table->cell_no_secondary = $request->cell_no_secondary;
        $table->gender = $request->gender;
        $table->domicile_district = $request->domicile_district;
        $table->married = $request->married;

        $table->eobi = $request->eobi;
        if ($request->has("eobi") && $request->eobi == "yes") {
            $table->eobi_no = $request->eobi_no;
        }

        // Handle bank information
        if ($request->has("bank_id")) {
            $table->bank_id = $request->bank_id;
            // if ($request->bank_id == "other") {
            //     $table->other_bank_id = $request->other_bank_id;
            // }
        }

        if ($request->has("iban")) {
            $table->iban = $request->iban;
        }

        $table->work_from = "$request->work_from_year-$request->work_from_month-$request->work_from_day";
        if($request->has("work_end_date_year")&&$request->has("work_end_date_month")&&$request->has("work_end_date_day")){
            $table->work_end_date = "$request->work_end_date_year-$request->work_end_date_month-$request->work_end_date_day";
        }

        $table->work_id = $request->work_type;
        if ($request->work_type == "other") {
            $work_type = WorkType::where("title", $table->other_work_type)->first();
            if (!$work_type) {
                $work_type = new WorkType();
                $work_type->title = $request->other_work_type;
                $work_type->add_by = $user_id;
                $work_type->save();
            }
            $table->work_id = $work_type->wt_id;
        }

        $table->perm_address = $request->perm_address;
        $table->perm_district_id = $request->perm_district;
        $table->postal_address = $request->postal_address;
        $table->postal_district_id = $request->postal_district;
        $table->lease_owner_name = $request->lease_owner_name;
        $table->lease_no = "Other";
        $table->mineral_title = $request->mineral_title;
        $table->lease_district_id = $request->lease_district;

        $table->mineral_id = $request->mineral;
        if ($request->mineral == "other") {
            $row = Minerals::where("name", $table->mineral)->first();
            if (!$row) {
                $row = new Minerals();
                $row->name = $request->other_mineral;
                $row->add_by = $user_id;
                $row->save();
            }
            $table->mineral_id = $row->m_id;
        }

        $table->purpose = $request->purpose;

        if ($request->purpose == "deceased labour") {
            $table->doa = "$request->doa_year-$request->doa_month-$request->doa_day";
            $table->death_date = "$request->death_year-$request->death_month-$request->death_day";
        }

        if ($request->purpose == "permanent disabled" || $request->purpose == "occupational desease") {
            $table->doa = "$request->doa_year-$request->doa_month-$request->doa_day";
        }

        $table->lease_address = $request->lease_address;

        $table->save();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd(District::all());
        if ($request->ajax()) {
            $status = null;
            $districts = null;
            $card_printed = null;
            $card_issued = null;
            if ($request->has("status")) {
                $status = $request->status;
            }

            if ($status=="card_printed") {
                $card_printed = $status;
                $status = null;
            }else if ($status=="card_not_printed") {
                $card_printed = $status;
                $status = null;
            }else{
                $card_printed = false;
            }
            
            if ($status=="card_issued") {
                $card_issued = $status;
                $status = null;
            }else if ($status=="card_not_issued") {
                $card_issued = $status;
                $status = null;
            }else{
                $card_issued = false;
            }

            if ($request->has("districts")) {
                $districts = $request->districts;
            }

            $data = $this->allWithScope(["l_id", "name", "father_name", "cnic", "labour_status","card_printed"])
                ->when($status, function ($query, $status) {
                    return $query->where("labour_status", $status);
                })
                ->when($card_printed, function ($query, $card_printed) {
                    return $query->where("card_printed", $card_printed=="card_printed");
                })
                ->when($card_issued, function ($query, $card_issued) {
                    return $query->where("card_printed", $card_issued=="card_issued");
                })
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("lease_district_id", explode(",", $districts));
                });


            $table = new DatatableHelper($data, $this->params, "l_id");
            return $table->custom_response(["action", "labour_status"])
                ->addColumn('labour_status', function (Labour $model) {
                    return $this->status($model->labour_status);
                })
                ->addColumn('card_printed', function (Labour $model) {
                    return $model->card_printed ? 1 : 0;
                })
                ->addColumn('card_issued', function (Labour $model) {
                    return $model->card_issued ? 1 : 0;
                })
                ->make(true);
        }

        $params = $this->params;
        $summary = $this->getSummary();
        return view($this->params['dir'] . ".index", compact("params", "summary"));
    }

    function getSummary()
    {
        $data["total"] = $this->all(["l_id", "name", "father_name", "cnic", "labour_status"])->count();

        $data["approved"] = $this->all(["l_id", "name", "father_name", "cnic", "labour_status"])->where("labour_status", "approved")->count();

        $data["rejected"] = $this->all(["l_id", "name", "father_name", "cnic", "labour_status"])->where("labour_status", "rejected")->count();

        $data["card_issued"] = $this->all(["l_id", "name", "father_name", "cnic", "labour_status"])->where("issued", true)->count();

        $data["card_not_issued"] = $this->all(["l_id", "name", "father_name", "cnic", "labour_status"])->where("issued", false)->count();

        $data["card_printed"] = $this->all(["l_id", "name", "father_name", "cnic", "labour_status"])->where("card_printed", true)->count();

        $data["card_not_printed"] = $this->all(["l_id", "name", "father_name", "cnic", "labour_status"])->where("card_printed", false)->count();


        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $params = $this->params;
        $districts = District::all();
        $minerals = Minerals::all();
        $worktypes = WorkType::all();
        $banks = Bank::all();
        $title = "New " . $params["singular_title"];
        return view($this->params['dir'] . ".create", compact("title", "params", "districts", "minerals", "worktypes", "banks"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(LabourRequest $request)
    {
        $data = [];
        $this->onHandleOperation($request);

        return redirect(route($this->params['route'] . ".index"));
    }

    function uploadFile($file, $destinationPath)
    {
        //Display File Name
        $fileName = time() . '_' . $file->getClientOriginalName();

        //Display File Extension
        $ext = $file->getClientOriginalExtension();

        //Display File Real Path
        $realPath = $file->getRealPath();

        //Display File Size
        $size = $file->getSize();

        //Display File Mime Type
        $mimeType = $file->getMimeType();

        //Move Uploaded File
        // $destinationPath = 'uploads/Auction/attachment';

        $path = $file->move($destinationPath, $fileName);

        return $destinationPath . "/" . $fileName;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd(\Carbon\Carbon::now()->subYears(60)->toDateString());
        // $data = Labour::withoutGlobalScopes()
        // ->where('dob', '<', \Carbon\Carbon::now()->subYears(60)->toDateString())
        // ->where('labour_status', '!=', 'overage')->get();
        
        // dd($data);
        
        $row = $this->find($id);

        // Load necessary relationships for the card display
        $row->load(['district', 'work', 'mineral', 'bank', 'account', 'perm_district']);

        $parm[$this->params['model']] = $id;
        $params = $this->params;
        return view($this->params['dir'] . ".show", compact("row", "parm", "params"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = $this->find($id);
        // dd($row);
        $parm[$this->params['model']] = $id;
        $title = "Edit " . $this->params['singular_title'];
        $params = $this->params;
        $districts = District::withoutGlobalScopes()->get();
        $minerals = Minerals::all();
        $worktypes = WorkType::all();
        $banks = Bank::all();
        return view($this->params['dir'] . ".create", compact("row", "title", "params", "parm","banks", "districts", "minerals", "worktypes"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(LabourRequest $request, $id)
    {
        $data = [];

        $this->onHandleOperation($request, $id);

        return redirect()->back()->with("success", "Updated Successfully!");
        // return redirect(route($this->params['route'] . ".index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $row = $this->find($id);

        $childrens = $row->children();
        //scholarships
        foreach ($childrens as $child) {
            $child->scholarships()->delete();
        }
        //children
        $row->children()->delete();
        //wifes
        $row->wife()->delete();
        //deceased grants
        $row->deceasedGrants()->delete();
        //disable grants
        $row->disableGrants()->delete();
        //marriage grants
        $row->marriageGrants()->delete();
        //pulmonary grants
        $row->pulmonaryGrants()->delete();


        $row->delete();
        if ($request->ajax()) {

            return Response()->json(["status" => "ok", "message" => "Delete Successfully"]);
        }

        return redirect(route($this->params['dir'] . ".index"));
    }

    public function updateStatus(Request $request, $id)
    {
        $user_id = Auth::getUser()->id;
        $rules = [
            'status' => 'required|max:255'
        ];

        if ($request->status == "rejected") {
            $rules["remarks"] = "required|max:50";
        }

        $validated = $request->validate($rules);
        $data = [
            "labour_status" => $request->status,
            "doc_verify_by" => $user_id
        ];

        if ($request->has("remarks")) {
            $data["remarks"] = $request->remarks;
        }

        if ($request->status != "pending") {
            $data["form_received"] = 1;
        }

        /*LabourLog::create([
"user_id"=>$user_id,
"labour_id"=>$id,
"action"=>$request->status,
"purpose"=>"labour",
"remarks"=>$request->remarks
]);*/

        Labour::where("l_id", $id)->update($data);
        return redirect()->back()->with("success", "Status Updated!");
    }

    function export(Request $request)
    {
        $status = null;
        $districts = [];
        $card_printed = null;
        if ($request->has("status")) {
            $status = $request->status;
        }

        if ($status =="card_printed") {
            $card_printed = "print";
            $status = null;
        }else if ($status =="card_not_printed") {
            $card_printed = "no-print";
            $status = null;
        }

        if ($request->has("districts")) {
            $districts = $request->districts;
        }

        if (!$request->columns) {
            echo "Please select at least one Field";
            return;
        }

        return Excel::download(new LaboursExport($request->columns, $status, $districts, $card_printed), "labour.xlsx");
    }

    function labourHistory(Request $request, $labour)
    {
        $row = $this->find($labour);
        $parm[$this->params['model']] = $labour;
        $title = "Labour History";
        $params = $this->params;
        $history = $row->history()->orderBy("created_at", "desc")->get();
        return view($this->params['dir'] . ".history", compact("row", "title", "params", "parm", "history"));
    }

    /**
     * Update CNIC information for labour
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateCNIC(LabourCNICRequest $request, $id)
    {
        $labour = $this->find($id);

        $user_id = Auth::getUser()->id;
        // Update CNIC dates
        $labour->issue_date_cnic = $request->issue_date_cnic;

        if ($request->has('lifetime_cnic') && $request->lifetime_cnic == 1) {
            $labour->expire_date_cnic = null;
        } else {
            $labour->expire_date_cnic = $request->expire_date_cnic;
        }

        // Debug: Check what files are being received
        \Log::info('Files received:', [
            'has_cnic_front' => $request->hasFile('cnic_front'),
            'has_cnic_back' => $request->hasFile('cnic_back'),
            'has_profile_image' => $request->hasFile('profile_image'),
            'all_files' => $request->allFiles(),
            'request_method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'all_input' => $request->all()
        ]);

        // Ensure upload directories exist
        $cnicUploadPath = public_path('uploads/labours/cnic');
        $profileUploadPath = public_path('uploads/labours/profile');

        if (!file_exists($cnicUploadPath)) {
            mkdir($cnicUploadPath, 0755, true);
        }
        if (!file_exists($profileUploadPath)) {
            mkdir($profileUploadPath, 0755, true);
        }

        // Handle file uploads

        if ($request->hasFile('cnic_front')) {
            $cnicFront = $request->file('cnic_front');
            $cnicFrontName = 'cnic_front_' . $id . '_' . time() . '.' . $cnicFront->getClientOriginalExtension();
            $cnicFront->move($cnicUploadPath, $cnicFrontName);
            $labour->cnic_front = 'uploads/labours/cnic/' . $cnicFrontName;
            \Log::info('CNIC Front uploaded:', ['filename' => $cnicFrontName]);
        }
        if ($request->hasFile('cnic_back')) {
            $cnicBack = $request->file('cnic_back');
            $cnicBackName = 'cnic_back_' . $id . '_' . time() . '.' . $cnicBack->getClientOriginalExtension();
            $cnicBack->move($cnicUploadPath, $cnicBackName);
            $labour->cnic_back = 'uploads/labours/cnic/' . $cnicBackName;
            \Log::info('CNIC Back uploaded:', ['filename' => $cnicBackName]);
        }

        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $profileImageName = 'profile_' . $id . '_' . time() . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->move($profileUploadPath, $profileImageName);
            $labour->profile_image = 'uploads/labours/profile/' . $profileImageName;
            \Log::info('Profile Image uploaded:', ['filename' => $profileImageName]);
        }


        // Update Urdu fields
        $labour->urdu_name = $request->urdu_name;
        $labour->urdu_father_name = $request->urdu_father_name;
        $labour->urdu_perm_address = $request->urdu_perm_address;

        // Update card issue fields
        if ($request->has('card_issue_date')) {
            $labour->card_issue_date = $request->card_issue_date;
        }
        $labour->issued = $request->has('issued') ? 1 : 0;
        if($request->issued) {
            $labour->issued_by = $user_id;
        }
        $labour->card_printed = $request->has('card_printed') ? 1 : 0;

        $labour->info_acknowledgment = $request->has('info_acknowledgment') ? 1 : 0;
        $labour->save();

        return redirect()->back()->with("success", "CNIC Information Updated Successfully!");
    }

    /**
     * QR Code Verification for Labour Card
     */
    public function qrVerify($id)
    {
        $labour = $this->find($id);

        if (!$labour) {
            return response()->json([
                'error' => 'Labour not found',
                'valid' => false
            ], 404);
        }

        // Load relationships
        $labour->load(['district', 'work', 'mineral', 'bank']);

        // Create verification data
        $verificationData = [
            'valid' => true,
            'labour' => [
                'id' => $labour->l_id,
                'name' => $labour->name,
                'father_name' => $labour->father_name,
                'cnic' => $labour->cnic,
                'dob' => $labour->dob,
                'gender' => $labour->gender,
                'district' => $labour->district->name ?? 'N/A',
                'work_type' => $labour->work->name ?? 'N/A',
                'mineral' => $labour->mineral->name ?? 'N/A',
                'phone' => $labour->cell_no_primary,
                'bank' => $labour->bank->name ?? 'N/A',
                'iban' => $labour->iban,
                'issue_date' => $labour->issue_date_cnic,
                'expire_date' => $labour->expire_date_cnic,
                'profile_image' => $labour->profile_image ? asset('uploads/labours/' . $labour->profile_image) : null,
                'card_front' => file_exists(public_path('uploads/labours/card/front.png')) ? asset('uploads/labours/card/front.png') : null,
                'card_back' => file_exists(public_path('uploads/labours/card/back.png')) ? asset('uploads/labours/card/back.png') : null,
            ],
            'verified_at' => now()->toISOString(),
            'verification_url' => route('admin.labours.show', $labour->l_id)
        ];

        return response()->json($verificationData);
    }

    /**
     * Show QR Verification Page
     */
    public function qrVerifyPage($id)
    {
        $labour = $this->find($id);

        if (!$labour) {
            abort(404, 'Labour not found');
        }

        // Load relationships
        $labour->load(['district', 'work', 'mineral', 'bank']);

        return view('admin.labours.qr-verify', compact('labour'));
    }

    /**
     * Print Labour Card
     */
    public function printCard($id)
    {
        $labour = $this->find($id);

        if (!$labour) {
            return redirect()->back()->with('error', 'Labour not found');
        }

        // Load relationships
        $labour->load(['district', 'work', 'mineral', 'bank']);

        return view('admin.layouts.partials.card.print-card', compact('labour'));
    }

    /**
     * Print Bulk Labour Cards by District
     */
    public function printBulkCards(Request $request)
    {
        $districtId = $request->query('district');
        $cardPrintedStatus = $request->query('card_printed', 'all'); // Default to 'all'


        if (!$districtId) {
            return redirect()->back()->with('error', 'District not selected');
        }

        // Build query for issued labours from the selected district
        $query = Labour::withoutGlobalScopes()
            ->where('lease_district_id', $districtId)
            ->where('issued', 1);

        // Filter by card_printed status
        if ($cardPrintedStatus !== 'all') {
            $query->where('card_printed', (int)$cardPrintedStatus);
        }

        // Fetch labours with relationships
        $labours = $query->with(['district', 'work', 'mineral', 'bank', 'perm_district'])
            ->orderBy('l_id', 'asc')
            ->get();

        if ($labours->isEmpty()) {
            $statusMessage = $cardPrintedStatus === 'all' ? 'issued' :
                           ($cardPrintedStatus == '1' ? 'printed' : 'unprinted');
            return redirect()->back()->with('error', 'No ' . $statusMessage . ' issued cards found for this district');
        }

        $district = District::find($districtId);

        return view('admin.labours.print-bulk-cards', compact('labours', 'district'));
    }
    
    

    public function updateTestReport(DisableTestRequest $request, $id)
    {
        $data = [];

        $user_id = Auth::getUser()->id;

        $disable = $this->find($id);
        
        $disable->test_date = $request->test_date;
        $disable->test_result_value = $request->test_result_value;
        $disable->severity_level = $request->severity_level;
        $disable->test_remarks = $request->test_remarks;
        $disable->performed_by_user_id = $user_id;
        $disable->test_last_update_date = \Carbon\Carbon::now();
        
        $disable->save();

        return redirect()->back()->with("success", "Test Record Updated!");
        // return redirect()->route($this->params['route'] . ".index")->with("message", "Disabled Labour Update!");
    }
}
