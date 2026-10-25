<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisableTestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            // Test Date
            'test_date' => ['required', 'date'],

            // Test Result Value
            'test_result_value' => ['required', 'numeric'],

            // Severity Level (normal, moderate, serious)
            'severity_level' => ['required', 'in:normal,moderate,serious'],

            // Remarks (optional)
            'test_remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [

            'test_date.required' => 'Test date is required.',
            'test_date.date' => 'Test date must be a valid date.',

            'test_result_value.required' => 'Test result value is required.',
            'test_result_value.numeric' => 'Test result must be numeric.',

            'severity_level.required' => 'Severity level is required.',
            'severity_level.in' => 'Severity must be normal, moderate, or serious.',

        ];
    }
}