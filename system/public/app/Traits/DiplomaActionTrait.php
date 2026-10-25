<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Auth;
use App\Models\Diploma;

trait DiplomaActionTrait {
    public function updateAction(Request $request, $id)
    {
        $user_id = Auth::getUser()->id;
        $rules = [
            'status' => 'required|max:255',
            "cnic_status" => "required",
            "form_b_status" => "required",
            // "appointment_letter_status" => "required",
            "schedule_a_status" => "required",
            "education_cert_status" => "required",
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
            
        $scholar = Diploma::find($id);
        $scholar->status = $request->status;
        $scholar->cnic_status = $request->cnic_status;
        $scholar->form_b_status = $request->form_b_status;
        $scholar->schedule_a_status = $request->schedule_a_status;
        $scholar->education_cert_status = $request->education_cert_status;
        $scholar->doc_verify_by = $user_id;
        
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