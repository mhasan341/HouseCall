<?php

namespace App\Http\Requests;

use App\Models\Drug;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDrugRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('drug_edit');
    }

    public function rules()
    {
        return [
            'rxcui' => [
                'string',
                'required',
            ],
            'name' => [
                'string',
                'required',
            ],
            'synonym' => [
                'string',
                'nullable',
            ],
            'language' => [
                'string',
                'nullable',
            ],
            'psn' => [
                'string',
                'nullable',
            ],
        ];
    }
}
