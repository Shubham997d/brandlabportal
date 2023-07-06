<?php

namespace App\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
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
            'asset.asset_type' => 'nullable|string',
            'asset.asset_cost' => ['nullable','not_regex:/[e]/','numeric','between:0,100000'],
        ];
    }
}