<?php

namespace App\Http\Requests;

use App\Models\FyYear;
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
        $allowedFyYears = array_column(FyYear::optionsForSelect(), 'value');

        return [
            'test_date' => ['required', 'date'],
            'severity_level' => ['required', Rule::in(['normal', 'Refer to Health Department'])],
            'remarks' => ['nullable', 'string'],
            'fy_year' => array_filter([
                'required',
                'string',
                'max:20',
                count($allowedFyYears) ? Rule::in($allowedFyYears) : null,
            ]),
        ];
    }
}
