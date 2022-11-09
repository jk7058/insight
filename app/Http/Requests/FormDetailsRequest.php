<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class FormDetailsRequest extends FormRequest
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
            'client_id' => 'required',
            'form_name' => 'required',
            'form_version' => 'required',  
            'form_attributes' => 'required',           
            'category_count' => 'required',
            'tb_name' => 'required',  
            'display_name' => 'required',  
            'form_status' => 'required',           
            'custom1' => 'required',           
            'custom2' => 'required', 
            'custom3' => 'required',
            'custom4' => 'required', 
            'channels' => 'required',
            'effective' => 'required', 
            'pass_rate' => 'required', 
            'form_weightage' => 'required',           
            'user_type' => 'required',
            'user_id' => 'required',
           // 'custom_meta' => 'required',                        
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

