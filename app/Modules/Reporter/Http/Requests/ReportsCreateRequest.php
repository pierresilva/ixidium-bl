<?php

namespace App\Modules\Reporter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportsCreateRequest extends FormRequest
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
            //
            'name' => 'required|unique:repo_reports,name',
            'connection' => 'required',
            'description' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'sql' => 'required',
            'fields' => 'required|array',
            'options' => 'nullable',
        ];
    }
}
