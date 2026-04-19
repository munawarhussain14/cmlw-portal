<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PulmonaryAnnualReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'test_date' => ['required', 'date'],
            'severity_level' => ['required', Rule::in(['normal', 'Refer to Health Department'])],
            'remarks' => ['nullable', 'string'],
            'fy_year' => ['required', 'string', 'max:20'],
        ];
    }
}
