<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Http\Traits\funcsTrait;

use App\Settings;

class SettingsUpdateRequest extends FormRequest
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
//        $requestData = $request->all();
        $tab_name_to_submit= $request->get('tab_name_to_submit');
//        $this->debToFile(print_r( $requestData,true),'  rules -1112233 $requestData::');
        $request->session()->put('tab_name_to_submit', $tab_name_to_submit);
        $retArray= [];
        $a= Settings::getValidationRulesArray( $tab_name_to_submit );
        foreach( $a as $next_field_name=>$next_value ) {
            $retArray[$tab_name_to_submit.'_'.$next_field_name]= $next_value;
/*     [site_content] => Array
        (
            [home_page_ref_items_per_pagination] => required|numeric
            [news_per_page] => required|numeric */
        }
        return $retArray;
    }
}
