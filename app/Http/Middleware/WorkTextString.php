<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Traits\funcsTrait;
use function PHPSTORM_META\type;

class WorkTextString
{

    use funcsTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $strip_tags_excluding= false )
    {
//        $this->debToFile(print_r( $strip_tags_excluding,true),'  TEXT  -0 $strip_tags_excluding::');
        $inputDataArray = $request->all();
        $stripTagsExcludingArray= $this->pregSplit('/ /',$strip_tags_excluding);
//        $this->debToFile(print_r( $stripTagsExcludingArray,true),'  TEXT  -1 $stripTagsExcludingArray::');
//        $this->debToFile(print_r( $inputDataArray,true),'  TEXT  -1 $inputDataArray::');
        foreach( $inputDataArray as $next_field_name=>$next_field_value ) {
//            $this->debToFile(print_r( $next_field_name,true),'  TEXT  -2 $next_field_name::');
//            $this->debToFile(print_r( $next_field_value,true),'  TEXT  -3 $next_field_value::');
            if ( !empty($next_field_value) and is_string($next_field_value) ) {
                $skip_strip_tags= in_array($next_field_name,$stripTagsExcludingArray);
                $request[$next_field_name] = $this->workTextString($next_field_value, $skip_strip_tags);
            }
        }
        return $next($request);
    }




}
