<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class QEScholarshipRequest extends FormRequest
{

    protected $student = [
        // "name"=>["required"],
        // "reg_no"=>["required","unique:children","regex:/[0-9]{5}-[0-9]{7}-[0-9]{1}/"],
        // "dob_day"=>["required"],
        // "dob_month"=>["required"],
        // "dob_year"=>["required"],
        // "gender"=>["required"]
    ];

    protected $class = [
        "class"=>["required"],
        "institute"=>["required"],
        "other_apply"=>["required"]
    ];

    protected $marks = [
        "obtained_marks"=>["required","lt:total_marks","numeric"],
        "total_marks"=>["required","gt:obtained_marks","numeric"],
        "passing_year"=>["required"]
    ];
    
    protected $board = [
        "roll_no"=>["required","numeric"],
        "board"=>["required"]
    ];

    protected $subject = [
        "subject"=>["required"]
    ];

    protected $fsc_subject = [
        "fsc_subject"=>["required"]
    ];

    protected $higher_edu = [
        "bachlor_discipline"=>["required"],
        "semester"=>["required"]
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

        if(in_array($request->class,["MBBS","BDS"]))
        {
            $rules = $this->getMedicalRules();
        }
        else if(in_array($request->class,["BE","MS"]))
        {
            $rules = $this->getHigherRules();
        }

        if(!$request->has("student_id")){
            foreach($this->student as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        if(($request->class=="BE"&&($request->semester==1||$request->semester==2))){
            foreach($this->board as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
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
            "class.required"=>"<p><span class='eng'>Please select Class</span><span class='urdu'></span></p>",
            "institute.required"=>"<p><span class='eng'>Please provide name of School/Institute/University</span><span class='urdu'></span></p>",
            "other_apply.required"=>"<p><span class='eng'>Please select option</span><span class='urdu'></span></p>",
            "obtained_marks.required"=>"<p><span class='eng'>Please mentioned Obtained Mark</span><span class='urdu'></span></p>",
            "obtained_marks.lt"=>"<p><span class='eng'>Obtained Mark must be less then Total Marks</span><span class='urdu'></span></p>",
            "obtained_marks.numeric"=>"<p><span class='eng'>Invalid Obtained Mark</span><span class='urdu'></span></p>",
            "total_marks.required"=>"<p><span class='eng'>Please mentioned Total Marks</span><span class='urdu'></span></p>",
            "total_marks.lt"=>"<p><span class='eng'>Total Mark must be less then Obtained Marks</span><span class='urdu'></span></p>",
            "total_marks.numeric"=>"<p><span class='eng'>Invalid Total Mark</span><span class='urdu'></span></p>",
            "passing_year.required"=>"<p><span class='eng'>Please mention Passing Year</span><span class='urdu'></span></p>",
            "passing_year.min"=>"<p><span class='eng'>Invalid Passing Year</span><span class='urdu'></span></p>",
            "passing_year.max"=>"<p><span class='eng'>Invalid Passing Year</span><span class='urdu'></span></p>",
            "roll_no.required"=>"<p><span class='eng'>Roll No Required</span><span class='urdu'></span></p>",
            "board.required"=>"<p><span class='eng'>Board Required</span><span class='urdu'></span></p>",
            "subject.required"=>"<p><span class='eng'>Please select Subject</span><span class='urdu'></span></p>",
            "fsc_subject.required"=>"<p><span class='eng'>Please select Subject</span><span class='urdu'></span></p>",
            "discipline.required"=>"<p><span class='eng'>Please mention Specialization/Discipline</span><span class='urdu'></span></p>",
            "dae_year.required"=>"<p><span class='eng'>Please select DAE Year</span><span class='urdu'></span></p>",
            "bachlor_discipline.required"=>"<p><span class='eng'>Please mention Specialization/Discipline</span><span class='urdu'></span></p>",
            "semester.required"=>"<p><span class='eng'>Please select Semester</span><span class='urdu'></span></p>"
        ];
    }

    private function getMedicalRules(){
        $rules = [];
        foreach($this->class as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        foreach($this->marks as $key => $rule){
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
