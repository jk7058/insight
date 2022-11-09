<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class HierarchyRequest extends FormRequest
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
            'c1' => 'required',
            'c2' => 'required',
            'c3' => 'required',
            'c4' => 'required',     
        ];
    }
    
    // public function all($keys = null){
    //     if(empty($keys)){
    //         return parent::json()->all();
    //     }

    //     return collect(parent::json()->all())->only($keys)->toArray();
    // }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'   => 400,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
