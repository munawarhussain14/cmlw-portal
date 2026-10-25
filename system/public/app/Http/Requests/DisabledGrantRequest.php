<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DisabledGrantRequest extends FormRequest
{

    protected $labour = [
        "work_end_day"=>["required"],
        "work_end_month"=>["required"],
        "work_end_year"=>["required"],
        "disability_factor"=>["required"],
        "work_from_day"=>["required"],
        "work_from_month"=>["required"],
        "work_from_year"=>["required"],
    ];

    protected $accident = [
        "doa_day"=>["required"],
        "doa_month"=>["required"],
        "doa_year"=>["required"]
    ];
    

    protected $rules = [];
    protected $messages = [
        "work_end_day.required"=>"<p><span class='eng'>Workend Day Required</span><span class='urdu'></span></p>",
        "work_end_month.required"=>"<p><span class='eng'>Workend Month Required</span><span class='urdu'></span></p>",
        "work_end_year.required"=>"<p><span class='eng'>Workend Date Required</span><span class='urdu'></span></p>",
        "disability_factor.required"=>"<p><span class='eng'>Please provide Disability Factor</span><span class='urdu'></span></p>",
        "doa_day.required"=>"<p><span class='eng'>Accident Day Required</span><span class='urdu'></span></p>",
        "doa_month.required"=>"<p><span class='eng'>Accident Month Required</span><span class='urdu'></span></p>",
        "doa_year.required"=>"<p><span class='eng'>Accident Year Required</span><span class='urdu'></span></p>",
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

        foreach($this->labour as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }
        

        if($request->purpose=="permanent disabled"){
            foreach($this->accident as $key => $rule){
                $rules = array_merge($rules,[$key=>$rule]);
            }
        }

        return $rules;
    }

    public function messages(){
        return $this->messages;
    }

}
