<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Http\Traits\funcsTrait;

use App\VoteCategory;

class VoteCategoryRequest extends FormRequest
{
    use funcsTrait;
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
        $request= Request();
        $requestData= $request->all();
        $this->debToFile(print_r($requestData,true),' VoteCategoryRequest  -01 $requestData::');
        return VoteCategory::getValidationRulesArray( $request->get('id') );
    }
}
