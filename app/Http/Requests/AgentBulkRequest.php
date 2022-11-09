<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class AgentBulkRequest extends BaseRequest
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
            'roster_file' => ['required', 'mimes:csv,txt']
        ];
    }
}
