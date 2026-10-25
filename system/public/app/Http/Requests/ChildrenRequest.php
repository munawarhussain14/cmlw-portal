<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ChildrenRequest extends FormRequest
{

    protected $child = [
        "name"=>["required"],
        "gender"=>["required"],
        "dob_day"=>["required"],
        "dob_month"=>["required"],
        "dob_year"=>["required"],
    ];


    protected $rules = [];
    protected $messages = [
        "name.required"=>"<p><span class='eng'>Labour Name Required</span><span class='urdu'></span></p>",
        "cnic.required"=>"<p><span class='eng'>Student Form-B/CNIC Required</span><span class='urdu'></span></p>",
        "cnic.unique"=>"<p><span class='eng'>Labour already Registred</span><span class='urdu'></span></p>",
        "cnic.regex"=>"<p><span class='eng'>Invalid CNIC</span><span class='urdu'></span></p>",
        "dob_day.required"=>"<p><span class='eng'>Day Required</span><span class='urdu'></span></p>",
        "dob_month.required"=>"<p><span class='eng'>Month Required</span><span class='urdu'></span></p>",
        "dob_year.required"=>"<p><span class='eng'>Date of Birth</span><span class='urdu'></span></p>",
        "gender.required"=>"<p><span class='eng'>Gender Required</span><span class='urdu'></span></p>"
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

        foreach($this->child as $key => $rule){
            $rules = array_merge($rules,[$key=>$rule]);
        }

        return $rules;
    }

    public function messages(){
        return $this->messages;
    }

}
