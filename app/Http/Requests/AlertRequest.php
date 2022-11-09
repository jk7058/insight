<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AlertRequest extends FormRequest
{
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
    public function rules()
    {
        return [
            'alert_name' => 'required',
            'evaluator_affiliation' => 'required',
            'alert_status' => 'required|in:Enable,Disable',
            'alert_type' => 'required|in:notification,isEmail',  
            'alert_by' => 'required|in:measure,attribute',           
            'alert_frequency' => 'required',
            'form_name' => 'required',  
            'form_attributes' => 'required',  
            'measure_equals_y_n' => 'required',           
            'custom1' => 'required',           
            'custom2' => 'required', 
            'custom3' => 'required',
            'custom4' => 'required', 
            'empid' => 'required',
            'created_by' => 'required|numeric', 
            'created_by_type' => 'required',                 
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'   => 400,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    public function messages(){
        return [
            'alert_status.in'=>"Only Enable or Disable is allowed.",
            'alert_type.in'=>"Only notification or isEmail is allowed.",
            'alert_by.in'=>"Only attribute or measure is allowed.",
        ];
    }
}
