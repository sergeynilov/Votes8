<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
//use App\Http\Traits\funcsTrait;

//use App\Video;

class VideoRequest extends FormRequest
{
//    use funcsTrait;
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
        return [];
//        $a= [
//            'name' => [
//                'required',
//                'string',
//                'max:255',
////                Rule::unique(with(new Tag)->getTable())->ignore($tag_id),
//            ],
//            'order_column'         => 'nullable|integer',
//        ];
//        \Log::info( '<pre>rules $::'.print_r($a,true).'</pre>' );
//        return $a;
        return Video::getValidationRulesArray( $request->get('id') );
    }
}
