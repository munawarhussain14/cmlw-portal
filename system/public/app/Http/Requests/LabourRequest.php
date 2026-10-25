<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LabourRequest extends FormRequest
{

    protected $labour = [
        "purpose"=>["required"],
        "name"=>["required"],
        "fathername"=>["required"],
        "gender"=>["required"],
        "married"=>["required"],
        "dob_day"=>["required"],
        "dob_month"=>["required"],
        "dob_year"=>["required"],
        "eobi"=>["required"],
        "cell_no_primary"=>["required"],
        "domicile_district"=>["required"],
        "work_from_day"=>["required"],
        "work_from_month"=>["required"],
        "work_from_year"=>["required"],
        "work_type"=>["required"],
        "perm_address"=>["required"],
        "perm_district"=>["required"],
        "postal_address"=>["required"],
        "postal_district"=>["required"],
        "lease_owner_name"=>["required"],
        "mineral_title"=>["required"],
        // "lease_no"=>["required"],
        "lease_district"=>["required"],
        "mineral"=>["required"],
        "lease_address"=>["required"],
        // "confirm"=>["required"],
    ];

    protected $death = [
        "doa_day"=>["required"],
        "doa_month"=>["required"],
        "doa_year"=>["required"],
        "death_day"=>["required"],
        "death_month"=>["required"],
        "death_year"=>["required"],
    ];

    protected $accident = [
        "doa_day"=>["required"],
        "doa_month"=>["required"],
        "doa_year"=>["required"]
    ];

    protected $eobi = [
        "eobi_no"=>["required"]
    ];

    protected $bank = [
        "other_bank_name"=>["required"]
    ];

    protected $work = [
        "other_work_type"=>["required"]
    ];

    protected $mineral = [
        "other_mineral"=>["required"]
    ];

    protected $rules = [];
    protected $messages = [
        "purpose.required"=>"<p><span class='eng'>Please select purpose</span><span class='urdu'></span></p>",
        "name.required"=>"<p><span class='eng'>Labour Name Required</span><span class='urdu'></span></p>",
        "fathername.required"=>"<p><span class='eng'>Father Name of Labour Required</span><span class='urdu'></span></p>",
        "cnic.required"=>"<p><span class='eng'>Student Form-B/CNIC Required</span><span class='urdu'></span></p>",
        "cnic.unique"=>"<p><span class='eng'>Labour already Registred</span><span class='urdu'></span></p>",
        "cnic.regex"=>"<p><span class='eng'>Invalid CNIC</span><span class='urdu'></span></p>",
        "doa_day.required"=>"<p><span class='eng'>Accident Day Required</span><span class='urdu'></span></p>",
        "doa_month.required"=>"<p><span class='eng'>Accident Month Required</span><span class='urdu'></span></p>",
        "doa_year.required"=>"<p><span class='eng'>Accident Year Required</span><span class='urdu'></span></p>",
        "death_day.required"=>"<p><span class='eng'>Death Day Required</span><span class='urdu'></span></p>",
        "death_month.required"=>"<p><span class='eng'>Death Month Required</span><span class='urdu'></span></p>",
        "death_year.required"=>"<p><span class='eng'>Death Date of Birth</span><span class='urdu'></span></p>",
        "dob_day.required"=>"<p><span class='eng'>Day Required</span><span class='urdu'></span></p>",
        "dob_month.required"=>"<p><span class='eng'>Month Required</span><span class='urdu'></span></p>",
        "dob_year.required"=>"<p><span class='eng'>Date of Birth</span><span class='urdu'></span></p>",
        "gender.required"=>"<p><span class='eng'>Gender Required</span><span class='urdu'></span></p>",
        "cell_no_primary.required"=>"<p><span class='eng'>Labour Primary Cell No Required</span><span class='urdu'></span></p>",
        "domicile_district.required"=>"<p><span class='eng'>Labour domicile District Required</span><span class='urdu'></span></p>",
        "married.required"=>"<p><span class='eng'>Labour Married Status Required</span><span class='urdu'></span></p>",
        "eobi.required"=>"<p><span class='eng'>EOBI Status Required</span><span class='urdu'></span></p>",
        "eobi_no.required"=>"<p><span class='eng'>EOBI No Required</span><span class='urdu'></span></p>",
        "work_type.required"=>"<p><span class='eng'>Work type Required</span><span class='urdu'></span></p>",
        "other_work_type.required"=>"<p><span class='eng'>Work type Required</span><span class='urdu'></span></p>",
        "other_bank_name.required"=>"<p><span class='eng'>Please provide Bank Name</span><span class='urdu'></span></p>",
        "dob_day.required"=>"<p><span class='eng'>Work From Day Required</span><span class='urdu'></span></p>",
        "dob_month.required"=>"<p><span class='eng'>Work From Month Required</span><span class='urdu'></span></p>",
        "dob_year.required"=>"<p><span class='eng'>Work From Year Month</span><span class='urdu'></span></p>",
        "perm_address.required"=>"<p><span class='eng'>Labour permanent address required</span><span class='urdu'></span></p>",
        "perm_district.required"=>"<p><span class='eng'>Labour permanent district required</span><span class='urdu'></span></p>",
        "postal_address.required"=>"<p><span class='eng'>Labour postal address required</span><span class='urdu'></span></p>",
        "postal_district.required"=>"<p><span class='eng'>Labour postal district required</span><span class='urdu'></span></p>",
        "lease_owner_name.required"=>"<p><span class='eng'>Lease Holder Name Required</span><span class='urdu'></span></p>",
        "mineral_title.required"=>"<p><span class='eng'>Mineral Title Required</span><span class='urdu'></span></p>",
        // "lease_no.required"=>"<p><span class='eng'>Lease No Required</span><span class='urdu'></span></p>",
        "lease_district.required"=>"<p><span class='eng'>Lease District Required</span><span class='urdu'></span></p>",
        "mineral.required"=>"<p><span class='eng'>Lease Mineral Required</span><span class='urdu'></span></p>",
        "other_mineral.required"=>"<p><span class='eng'>Mineral Required</span><span class='urdu'></span></p>",
        "lease_address.required"=>"<p><span class='eng'>Lease Address Required</span><span class='urdu'></span></p>"
    ];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        $rules = [];


        if($request->route("labour")){
            $this->labour["cnic"] = ["required","regex:/[0-9]{5}-[0-9]{7}-[0-9]{1}/"];
        }else{
            $this->labour["cnic"] = ["required","unique:labours","regex:/[0-9]{5}-[0-9]{7}-[0-9]{1}/"];
        }

        foreach($this->labour as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }


        if($request->purpose=="deceased labour"){
            foreach($this->death as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        if($request->purpose=="permanent disabled"){
            foreach($this->accident as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        if($request->purpose=="occupational desease"){
            foreach($this->accident as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        if($request->eobi=="yes"){
            foreach($this->eobi as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        if($request->bank=="other"){
            foreach($this->bank as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        if($request->work_type=="other"){
            foreach($this->work as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        if($request->mineral=="other"){
            foreach($this->mineral as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        return $rules;
    }

    public function messages(){
        return $this->messages;
    }

    private function getOneClassRule(){
        $rules = [];
        foreach($this->class as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }
        return $rules;
    }

    private function getPrimaryRules(){
        $rules = [];
        foreach($this->class as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->marks as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        return $rules;
    }

    private function getMatricRules(){
        $rules = [];
        foreach($this->class as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->marks as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->subject as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        return $rules;
    }

    private function getFscRules(){
        $rules = [];
        foreach($this->class as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->marks as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->fsc_subject as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        return $rules;
    }

    private function getDaeRules(){
        $rules = [];
        foreach($this->class as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->marks as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->specialization as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        return $rules;
    }

    private function getHigherRules(){
        $rules = [];
        foreach($this->class as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->marks as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->higher_edu as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        return $rules;
    }

}
