<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Http\Traits\funcsTrait;

use App\VoteItem;

class VoteItemRequest extends FormRequest
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
        //     public static function getValidationRulesArray($vote_id, $vote_item_id = null): array
        $requestData= $request->all();
        return VoteItem::getValidationRulesArray( $request->get('vote_id'), $request->get('id') );
    }
}
