<?php

namespace App\Chat\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreateWorkspaceRequest extends FormRequest
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
            'name'       => 'string|required|unique:App\Chat\Models\Workspace,name',  /* allow only unique workspace names */
            'meta'       => 'nullable',
        ];
    }
}
