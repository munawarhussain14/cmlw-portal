<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DiplomaRequest extends FormRequest
{

    protected $student = [
        "name"=>["required"],
        // "reg_no"=>["required","unique:children","regex:/[0-9]{5}-[0-9]{7}-[0-9]{1}/"],
        "dob_day"=>["required"],
        "dob_month"=>["required"],
        "dob_year"=>["required"],
        "gender"=>["required"]
    ];

    protected $general = [
        "qualification"=>["required"],
        "matric_obtained_marks"=>["required","lt:matric_total_marks","numeric"],
        "matric_total_marks"=>["required","gt:matric_obtained_marks","numeric"],
        "passing_year"=>["required"],
        "roll_no"=>["required","numeric"],
        "board"=>["required"],
        "other_apply"=>["required"]
    ];    

    protected $rules = [];
    protected $messages = [];
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

        if(!$request->has("student_id")){
            foreach($this->student as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        foreach($this->general as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        return $rules;
    }

    public function messages(){
        return [
            "name.required"=>"<p><span class='eng'>Student Name Required</span><span class='urdu'></span></p>",
            "reg_no.required"=>"<p><span class='eng'>Student Form-B/CNIC Required</span><span class='urdu'></span></p>",
            "reg_no.unique"=>"<p><span class='eng'>Child already Registred</span><span class='urdu'></span></p>",
            "reg_no.regex"=>"<p><span class='eng'>Invalid CNIC</span><span class='urdu'></span></p>",
            "dob_day.required"=>"<p><span class='eng'>Day Required</span><span class='urdu'></span></p>",
            "dob_month.required"=>"<p><span class='eng'>Month Required</span><span class='urdu'></span></p>",
            "dob_year.required"=>"<p><span class='eng'>Date of Birth</span><span class='urdu'></span></p>",
            "gender.required"=>"<p><span class='eng'>Gender Required</span><span class='urdu'></span></p>",
            "qualification.required"=>"<p><span class='eng'>Please select Qualification</span><span class='urdu'></span></p>",
            "other_apply.required"=>"<p><span class='eng'>Please select option</span><span class='urdu'></span></p>",
            "matric_obtained_marks.required"=>"<p><span class='eng'>Please mentioned Matric Obtained Mark</span><span class='urdu'></span></p>",
            "matric_obtained_marks.lt"=>"<p><span class='eng'>Obtained Mark must be less then Matric Total Marks</span><span class='urdu'></span></p>",
            "matric_obtained_marks.numeric"=>"<p><span class='eng'>Invalid Matric Obtained Mark</span><span class='urdu'></span></p>",
            "matric_total_marks.required"=>"<p><span class='eng'>Please mentioned Total Matric Marks</span><span class='urdu'></span></p>",
            "matric_total_marks.lt"=>"<p><span class='eng'>Total Mark must be less then Matric Obtained Marks</span><span class='urdu'></span></p>",
            "matric_total_marks.numeric"=>"<p><span class='eng'>Invalid Matric Total Mark</span><span class='urdu'></span></p>",
            "passing_year.required"=>"<p><span class='eng'>Please mention Passing Year</span><span class='urdu'></span></p>",
            "passing_year.min"=>"<p><span class='eng'>Invalid Passing Year</span><span class='urdu'></span></p>",
            "passing_year.max"=>"<p><span class='eng'>Invalid Passing Year</span><span class='urdu'></span></p>",
            "roll_no.required"=>"<p><span class='eng'>Roll No Required</span><span class='urdu'></span></p>",
            "board.required"=>"<p><span class='eng'>Board Required</span><span class='urdu'></span></p>"
        ];
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
