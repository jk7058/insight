<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EscalationRequest extends FormRequest
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
            'escalation_by' => ['required'],
            'authorizer_level' => ['required'],
            'authorize_level_1' => ['required'],
            'authorize_level_2' => ['required'],
            'authorize_1_tat' => ['required'],
            'authorize_2_tat' => ['required'],
            'resolver_by' => ['required'],
            'resolution_tat' => ['required'],
            'reescalation_required' => ['required'],
            'reescalation_by' => ['required'],
            'reescalation_level' => ['required'],
            'reescalation_authorized' => ['required'],
            'reescalation_authorize_tat' => ['required'],
            'reescalation_resolver' => ['required'],
            'reescalation_resolution_tat' => ['required']
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
}
