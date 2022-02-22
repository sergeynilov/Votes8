<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Intervention\Image\Facades\Image as Image;
//use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use File;
use Validator;
//use Filesystem;
//use League\Flysystem\Filesystem;

use App\Http\Traits\funcsTrait;
use App\Settings;

class MyAppModel extends Model {
    protected $table = '';
    protected $primaryKey = '';

//    protected static $validationMessages = [];
//    protected $checkNonUpdatableFieldsArray = [];
//    protected $checkNonInsertableFieldsArray = [];
//    protected $listFieldsArray = [];
//    protected $itemFieldsArray = [];
//	public static $mysqlYesNoValueArray = ['N' => 'No', 'Y' => 'Yes'];
//	public static $mysqlSortDirectionValueArray = ['asc' => 'ascending', 'desc' => 'descending'];
    use funcsTrait;

    public static function getValueLabelKeys(array $arr) : string
    {
        $keys= array_keys($arr);
        $ret_str= '';
        foreach( $keys as $next_key ) {
            $ret_str.= $next_key. ',';
        }
        return  with(new MyAppModel)->trimRightSubString( $ret_str, ',' );
    }

    public static function checkValidImgName(string $filename, int $max_length=0, bool $check_valid_chars= false) : string
    {
        $ret_str= $filename;
        if ( !empty($max_length) and with(new MyAppModel)->isPositiveNumeric($max_length)) {
            if ( strlen($filename) > $max_length ) {
//                echo '<pre>$filename::'.print_r($filename,true).'</pre>';
                $basename= with(new MyAppModel)->getFilenameBasename($filename);
//                echo '<pre>$basename::'.print_r($basename,true).'</pre>';
                $extension= with(new MyAppModel)->getFilenameExtension($filename);
//                echo '<pre>$extension::'.print_r($extension,true).'</pre>';
                $index= $max_length - strlen('.'.$extension);
//                echo '<pre>$index::'.print_r($index,true).'</pre>';
                $ret_str= substr($basename,0,$index) . '.'.$extension;
            }
        }
        if ( $check_valid_chars ) {
            $ret_str= str_replace(' ','_',$ret_str);
        }
        return $ret_str;
    }


    public static function myStrLower($value, $with_single_quote, $with_percent) : string
    {
        $percent= $with_percent ? '%' : '';
        if ( $with_single_quote ) {
            $ret = "LOWER('" . $percent . $value . $percent . "')";
        } else {
            $ret= "LOWER(" . $percent . $value . $percent . ")";
        }
        return $ret;

    }

    public static function getErrorsList($validator) : array {
        if ( empty($validator) or !is_object($validator) ) return [];
        return array_combine($validator->messages()->keys(), $validator->messages()->all() );
    }

    protected static function checkNonModifiedFields(array $dataArray, bool $is_insert) : array
    {
        $errorsFieldsArray= [];
        if ($is_insert) {
            foreach (with(new MyAppModel)->checkNonInsertableFieldsArray as $nextCheckNonInsertableField) {
                if (isset($dataArray[$nextCheckNonInsertableField])) {
                    $errorsFieldsArray[$nextCheckNonInsertableField] = "Field '" . $nextCheckNonInsertableField . "' can not be inserted";
                }
            }
        }
        else {
            foreach (with(new MyAppModel)->checkNonUpdatableFieldsArray as $nextCheckNonUpdatableField) {
                if (isset($dataArray[$nextCheckNonUpdatableField])) {
                    $errorsFieldsArray[$nextCheckNonUpdatableField] = "Field '" . $nextCheckNonUpdatableField . "' can not be updated";
                }
            }
        }
        return [ 'error_code' => count($errorsFieldsArray) > 0, 'errorsList' => $errorsFieldsArray, 'success_message' => "" ];
    }

    
    /*protected static function getImageProps(string $image_path, array $image_props_array= []) : array
    {
        if ( !file_exists($image_path) ) return[];
        $file_size= Image::make($image_path)->filesize();
        $file_size_label= with(new MyAppModel)->getFileSizeAsString($file_size);
        $file_width= Image::make($image_path)->width();
        $file_height= Image::make($image_path)->height();
        $retArray= [];
        foreach( $image_props_array as $nextImageProp=>$nextImagePropValue ) {
            $retArray[$nextImageProp]= $nextImagePropValue;
        }
        $retArray['file_size']= $file_size;
        $retArray['file_size_label']= $file_size_label;
        $retArray['file_width']= $file_width;
        $retArray['file_height']= $file_height;
        $retArray['file_info']= '<b>'.basename($image_path).'</b>, ' . $retArray['file_width'] .'x'. $retArray['file_height'] . ', '. $file_size_label;
        return $retArray;
    } */
    
    protected static function getImageProps(string $image_path, array $imagePropsArray= []) : array
    {
        if ( !file_exists($image_path) ) {
            echo '<pre>$image_path::'.print_r($image_path,true).'</pre>';
            die("-1 XXZ =========");
            return [];
        }
        $imagesExtensionsArray= \Config::get('app.images_extensions');
        $extension= with(new MyAppModel)->getFilenameExtension($image_path);
        $file_width  = null;
        $file_height = null;
        if ( in_array($extension,$imagesExtensionsArray) ) {
            $file_width  = Image::make($image_path)->width();
            $file_height = Image::make($image_path)->height();
            $file_size= Image::make($image_path)->filesize();
        } else {
            $file_size = File::size($image_path);
        }
        $file_size_label= with(new MyAppModel)->getFileSizeAsString($file_size);
        $retArray= [];
        $retArray['file_info']= '<b>'.basename($image_path).'</b>, '. $file_size_label;

        foreach( $imagePropsArray as $nextImageProp=>$nextImagePropValue ) {
            $retArray[$nextImageProp]= $nextImagePropValue;
        }
        $retArray['file_size']= $file_size;
        $retArray['file_size_label']= $file_size_label;
        if ( isset($file_width) ) {
            $retArray['file_width'] = $file_width;
        }
        if ( isset($file_height) ) {
            $retArray['file_height'] = $file_height;
        }
        if ( !empty($retArray['file_width']) and !empty($retArray['file_height']) ) {
            $retArray['file_info'] .= ', ' . $retArray['file_width'] . 'x' . $retArray['file_height'];
        }
//        echo '<pre>$retArray::'.print_r($retArray,true).'</pre>';
//        die("-1 XXZ");
        return $retArray;
    }
    
    public static function getPhoneValidationFormat()
    { // http://stackoverflow.com/questions/123559/a-comprehensive-regex-for-phone-number-validation/
        return config('app.valid_phone_format');
    }


    public static function getPercentValidationFormat() : string
    { // https://stackoverflow.com/questions/33624710/how-to-validate-money-in-laravel5-request-class
        return config('app.valid_percent_format', '^\d*(\.\d{1,2})?$');
    }

    public static function getMoneyValidationFormat() : string
    { // https://stackoverflow.com/questions/33624710/how-to-validate-money-in-laravel5-request-class
        return config('app.valid_money_format', '^\d*(\.\d{1,2})?$');
    }


    public static function getGeographicCoordinateValidationFormat() : string
    {
        return config('app.valid_geographic_coordinate_format', '^[\-]?\d*(\.\d{1,7})?$' );
    }

    public static function getShippingDecimalValidationFormat()
    { // https://stackoverflow.com/questions/33624710/how-to-validate-money-in-laravel5-request-class
        return config('app.valid_shipping_decimal_format', '^\d*(\.\d{1,2})?$');
    }

    public function getItemsPerPage() : int
    {
        return (int)Settings::getValue('ref_items_per_pagination', $this->ref_items_per_pagination);
    }

    public static function mysqlEscape( string $str ) : string
    {
        if(is_array($str))
            return array_map(__METHOD__, $str);

        if(!empty($inp) && is_string($str)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $str);
        }
        return trim( addslashes ($str) );
    }

    //             UserChatMessageDocument::deleteFileByPath($user_chat_message_document_filename, true);
    public static function deleteFileByPath( string $filename_path, $delete_empty_directory= false ) : bool
    {
//        with(new MyAppModel)->debToFile(print_r( $filename_path,true),'  deleteFileByPath -10 $filename_path::');
        Storage::delete($filename_path);
        $directory_path= pathinfo($filename_path);

        $file_exists = Storage::disk('local')->exists( 'public/'.$filename_path);
//        with(new MyAppModel)->debToFile(print_r( $file_exists,true),'  deleteFileByPath -11 $file_exists::');

        //         $file_exists    = ( !empty($logo) and Storage::disk('local')->exists( 'public/'.$file_full_path) );
        $ret= Storage::disk('local')->delete( 'public/' . $filename_path );
//        with(new MyAppModel)->debToFile(print_r( $ret,true),'  deleteFileByPath -12 $ret::');

        if ( !empty($directory_path['dirname']) /* and $FileSystem->exists($base_path.$directory_path['dirname']) */) {
            $files = Storage::files('public/'.$directory_path['dirname']);
            if (empty($files)) {
                Storage::deleteDirectory('public/' . $directory_path['dirname']);
                return true;
            }
        }
        return false;
    }

}
