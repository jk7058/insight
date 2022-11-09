<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class FeedbackSettingRequest extends BaseRequest
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
            'feedback_by' => 'required_without_all:feedback_tat,acknowledged_by',
            'feedback_tat' => 'required_without_all:feedback_by,acknowledged_by',
            'acknowledged_by' => 'required_without_all:feedback_by,feedback_tat'
        ];
    }
}
