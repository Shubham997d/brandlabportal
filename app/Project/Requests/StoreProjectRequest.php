<?php

namespace App\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
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
            // 'project_manager_id' => 'nullable||integer',
            // 'deadline'    => 'nullable',
            // 'status'      => 'nullable|integer',
            // 'completed_at'   => 'nullable',
            // 'cost'   => ['nullable','not_regex:/[e]/','numeric'],
            // 'monthly_usage_fee'   => ['nullable','not_regex:/[e]/','numeric'],
            // 'currency_code'   => ['required', Rule::in(array_column(config('constants.miscellaneous.currrencies'),'code')) ],  
            // 'contact_term_length'      => 'nullable|integer',          
            // 'office_id'   => 'nullable|integer',
            // 'team_id'     => 'nullable|integer',
        ];
    }
}
