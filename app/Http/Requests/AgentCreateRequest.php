<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class AgentCreateRequest extends BaseRequest
{
    
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
            'user_id' => ['required', 'min:3', 'max:25'],
            'user_name' => ['required', 'min:1', 'max:25'],
            'doj' => ['required'],
            'custom1' => ['required'],
            'custom2' => ['required'],
            'custom3' => ['required'],
            'custom4' => ['required'],
            'supervisor_id' => ['required'],
            'manager2_id' => ['required'],
            'manager1_id' => ['required'],
            'manager3_id' => ['required'],
        ];
    }
}
