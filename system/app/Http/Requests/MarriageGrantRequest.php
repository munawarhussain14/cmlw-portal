<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class MarriageGrantRequest extends FormRequest
{

    protected $labour = [
        "husband_name"=>["required"],
        "husband_cnic"=>["required"],
        "marriage_held_day"=>["required"],
        "marriage_held_month"=>["required"],
        "marriage_held_year"=>["required"]
    ];
    
    protected $rules = [];
    protected $messages = [
        "husband_name.required"=>"<p><span class='eng'>Please provide Husband Name</span><span class='urdu'></span></p>",
        "husband_name.required"=>"<p><span class='eng'>Please provide Husband CNIC</span><span class='urdu'></span></p>",
        "marriage_held_day.required"=>"<p><span class='eng'>Marriage Held Day Required</span><span class='urdu'></span></p>",
        "marriage_held_month.required"=>"<p><span class='eng'>Marriage Held Month Required</span><span class='urdu'></span></p>",
        "marriage_held_year.required"=>"<p><span class='eng'>Marriage Held Date Required</span><span class='urdu'></span></p>",
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
