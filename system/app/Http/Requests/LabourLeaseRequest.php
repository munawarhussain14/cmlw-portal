<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LabourLeaseRequest extends FormRequest
{

    protected $rules = [
        "mineral_title" => ["required"],
        "start" => ["required", "date"],
        "end" => []
    ];
    protected $messages = [
        "mineral_title.required" => "<p><span class='eng'>Please select Mineral Title</span><span class='urdu'></span></p>",
        "start.required" => "<p><span class='eng'>Please select labour work start date in selected lease</span><span class='urdu'></span></p>",
        "start.date" => "<p><span class='eng'>Invalid Date</span><span class='urdu'></span></p>",
        "end.required" => "<p><span class='eng'>Please select labour work end date in selected lease</span><span class='urdu'></span></p>",
        "end.date" => "<p><span class='eng'>Invalid Date</span><span class='urdu'></span></p>",
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
        return $this->rules;
    }

    public function messages()
    {
        return $this->messages;
    }
}
