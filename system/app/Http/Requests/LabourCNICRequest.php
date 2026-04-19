<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LabourCNICRequest extends FormRequest
{

    protected $labour = [
        "issue_date_cnic" => ["nullable","date"],
        "expire_date_cnic" => ["nullable", "date", "after:issue_date_cnic"],
        "lifetime_cnic" => ["nullable", "boolean"],
        // Removed 'image' validation rule to avoid MIME type detection
        "cnic_front" => ["nullable", "image", "max:2048"],
        "cnic_back" => ["nullable", "image", "max:2048"],
        "profile_image" => ["nullable", "image", "max:2048"],
        "urdu_name" => ["required", "string", "max:255"],
        "urdu_father_name" => ["required", "string", "max:255"],
        "urdu_perm_address" => ["required", "string", "max:1000"],
        "info_acknowledgment" => ["required", "accepted"],
    ];
    
    protected $rules = [];
    protected $messages = [
        "issue_date_cnic.required" => "<p><span class='eng'>Please provide CNIC Issue Date</span><span class='urdu'></span></p>",
        "issue_date_cnic.date" => "<p><span class='eng'>Please provide a valid date for CNIC Issue Date</span><span class='urdu'></span></p>",
        "expire_date_cnic.date" => "<p><span class='eng'>Please provide a valid date for CNIC Expiry Date</span><span class='urdu'></span></p>",
        "expire_date_cnic.after" => "<p><span class='eng'>CNIC Expiry Date must be after Issue Date</span><span class='urdu'></span></p>",
        "cnic_front.image" => "<p><span class='eng'>CNIC Front must be a valid image file</span><span class='urdu'></span></p>",
        "cnic_front.max" => "<p><span class='eng'>CNIC Front image size must not exceed 2MB</span><span class='urdu'></span></p>",
        "cnic_back.image" => "<p><span class='eng'>CNIC Back must be a valid image file</span><span class='urdu'></span></p>",
        "cnic_back.max" => "<p><span class='eng'>CNIC Back image size must not exceed 2MB</span><span class='urdu'></span></p>",
        "profile_image.image" => "<p><span class='eng'>Profile Image must be a valid image file</span><span class='urdu'></span></p>",
        "profile_image.max" => "<p><span class='eng'>Profile Image size must not exceed 2MB</span><span class='urdu'></span></p>",
        "urdu_name.required" => "<p><span class='eng'>Urdu Name is required</span><span class='urdu'>اردو نام ضروری ہے</span></p>",
        "urdu_name.string" => "<p><span class='eng'>Urdu Name must be a valid text</span><span class='urdu'>اردو نام درست متن ہونا چاہیے</span></p>",
        "urdu_name.max" => "<p><span class='eng'>Urdu Name must not exceed 255 characters</span><span class='urdu'>اردو نام 255 حروف سے زیادہ نہیں ہو سکتا</span></p>",
        "urdu_father_name.required" => "<p><span class='eng'>Urdu Father Name is required</span><span class='urdu'>اردو والد کا نام ضروری ہے</span></p>",
        "urdu_father_name.string" => "<p><span class='eng'>Urdu Father Name must be a valid text</span><span class='urdu'>اردو والد کا نام درست متن ہونا چاہیے</span></p>",
        "urdu_father_name.max" => "<p><span class='eng'>Urdu Father Name must not exceed 255 characters</span><span class='urdu'>اردو والد کا نام 255 حروف سے زیادہ نہیں ہو سکتا</span></p>",
        "urdu_perm_address.required" => "<p><span class='eng'>Urdu Permanent Address is required</span><span class='urdu'>اردو مستقل پتہ ضروری ہے</span></p>",
        "urdu_perm_address.string" => "<p><span class='eng'>Urdu Permanent Address must be a valid text</span><span class='urdu'>اردو مستقل پتہ درست متن ہونا چاہیے</span></p>",
        "urdu_perm_address.max" => "<p><span class='eng'>Urdu Permanent Address must not exceed 1000 characters</span><span class='urdu'>اردو مستقل پتہ 1000 حروف سے زیادہ نہیں ہو سکتا</span></p>",
        "info_acknowledgment.required" => "<p><span class='eng'>Please provide Information Acknowledgment</span><span class='urdu'></span></p>",
        "info_acknowledgment.accepted" => "<p><span class='eng'>You must accept the information acknowledgment</span><span class='urdu'></span></p>",
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

        // Add conditional validation for expire_date_cnic
        if (!$request->has('lifetime_cnic') || $request->lifetime_cnic != 1) {
            // $rules['expire_date_cnic'] = ['required', 'date', 'after:issue_date_cnic'];
        }

        foreach($this->labour as $key => $rule){
            if ($key !== 'expire_date_cnic') { // Skip expire_date_cnic as we handle it conditionally above
                $rules = array_merge($rules, [$key => $rule]);
            }
        }

        return $rules;
    }

    public function messages(){
        return $this->messages;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Additional custom validation logic if needed
        });
    }
}