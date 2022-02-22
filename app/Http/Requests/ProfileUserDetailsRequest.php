<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Http\Traits\funcsTrait;

use App\User;

class ProfileUserDetailsRequest extends FormRequest
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
        $a= User::getUserRegisterValidationRulesArray(['hide_username', 'hide_email', 'hide_password', 'hide_password_conf']);
        return $a;
    }
}
