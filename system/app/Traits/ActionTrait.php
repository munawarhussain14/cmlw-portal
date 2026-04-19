<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Auth;
use App\Models\Scholarship;
use App\Models\Staff;

trait ActionTrait {
    public function updateAction(Request $request, $id)
    {
        $user_id = Auth::getUser()->id;
        $rules = [
            'status' => 'required|max:255',
            "cnic_status" => "required",
            "form_b_status" => "required",
            // "appointment_letter_status" => "required",
            "schedule_a_status" => "required",
            "marks_sheet_status" => "required",
        ];
     
        if($request->status=="rejected")
         {
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
            
        $scholar = Scholarship::withoutGlobalScopes()->find($id);
        $scholar->status = $request->status;
        $scholar->cnic_status = $request->cnic_status;
        $scholar->form_b_status = $request->form_b_status;
        // $scholar->appointment_letter_status = $request->appointment_letter_status;
        $scholar->schedule_a_status = $request->schedule_a_status;
        $scholar->marks_sheet_status = $request->marks_sheet_status;
        $scholar->doc_verify_by = $user_id;
        
        if($request->status=="approved")
         {
            $temp = Auth::getUser()->name;
            
            if(Auth::getUser()->staff){
                $temp .= ", ".Auth::getUser()->staff->post->short_designation;
                $temp .= ", office ".Auth::getUser()->staff->office->officeDistrict->name;
                $temp .= " at ".date("D d M Y h:i:sa");
            }
            
            $scholar->approved_by = $temp;
         }

        
        if($request->status!="pending")
        {
           $scholar->form_received = true;
        }

        if($request->has("remarks"))
        {
           $scholar->remarks = $request->remarks;
        }

        $scholar->save();

        return redirect()->back()->with("message","Application Status Updated!");
    }    
}