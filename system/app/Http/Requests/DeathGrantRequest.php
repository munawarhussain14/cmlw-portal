<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DeathGrantRequest extends FormRequest
{

    protected $labour = [
        "cause" => ["required"],
        "death_date_day" => ["required"],
        "death_date_month" => ["required"],
        "death_date_year" => ["required"],
    ];

    protected $rules = [];
    protected $messages = [
        "cause.required" => "<p><span class='eng'>Please provide Disability Factor</span><span class='urdu'></span></p>",
        "death_date_day.required" => "<p><span class='eng'>Death End Day Required</span><span class='urdu'></span></p>",
        "death_date_month.required" => "<p><span class='eng'>Death Month Required</span><span class='urdu'></span></p>",
        "death_date_year.required" => "<p><span class='eng'>Death Date Required</span><span class='urdu'></span></p>"
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


        foreach ($this->labour as $key => $rule) {
            $rules = array_merge($rules, [$key => $rule]);
        }


        return $rules;
    }

    public function messages()
    {
        return $this->messages;
    }
}
