<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class pt_request extends Request
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
            "platform_name"=>"required",
            "start_date" => "required",
            "end_date" => "required",
            "excel-file" => "required|mimes:xls,xlsx",
        ];
    }
    public function messages(){
        return [
            "platform_name.required" => "Platform name cann't be empty ",
            "start_date.required" => "Start date  cann't be empty",
            "end_date.required" => "End date  cann't be empty",
            "excel-file.required" => "File  cann't be empty",
        ];
    }
}
