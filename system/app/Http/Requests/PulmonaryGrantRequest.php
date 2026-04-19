<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PulmonaryGrantRequest extends FormRequest
{

    protected $labour = [
        "hospital_name"=>["required"],
        "disease"=>["required"],
        "from_day"=>["required"],
        "from_month"=>["required"],
        "from_year"=>["required"],
        "to_day"=>["required"],
        "to_month"=>["required"],
        "to_year"=>["required"],
        "from_year"=>["required"],
    ];
    
    protected $rules = [];
    protected $messages = [
        "hospital_name.required"=>"<p><span class='eng'>Please provide Disability Factor</span><span class='urdu'></span></p>",
        "disease.required"=>"<p><span class='eng'>Please provide Disability Factor</span><span class='urdu'></span></p>",
        "from_date_day.required"=>"<p><span class='eng'>Work End Day Required</span><span class='urdu'></span></p>",
        "from_date_month.required"=>"<p><span class='eng'>Workend Month Required</span><span class='urdu'></span></p>",
        "from_date_year.required"=>"<p><span class='eng'>Workend Date Required</span><span class='urdu'></span></p>",
        "to_end_day.required"=>"<p><span class='eng'>Workend Day Required</span><span class='urdu'></span></p>",
        "to_end_month.required"=>"<p><span class='eng'>Workend Month Required</span><span class='urdu'></span></p>",
        "to_end_year.required"=>"<p><span class='eng'>Workend Date Required</span><span class='urdu'></span></p>",
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
        

        return $rules;
    }

    public function messages(){
        return $this->messages;
    }

}
