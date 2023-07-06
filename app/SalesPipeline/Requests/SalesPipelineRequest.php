<?php

namespace App\SalesPipeline\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesPipelineRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            // 'name'        => 'required|string',
            // 'description' => 'required|string',
            // 'deadline'    => 'required',
            // 'office_id'   => 'integer',
            // 'team_id'     => 'integer',
        ];
    }
}
