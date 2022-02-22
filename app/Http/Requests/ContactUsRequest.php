<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Http\Traits\funcsTrait;

use App\ContactUs;

class ContactUsRequest extends FormRequest
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
        $a= ContactUs::getValidationRulesArray( [ 'skip_acceptor_id', 'skip_accepted'] );
        return $a;
    }
}
