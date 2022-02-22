<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;


class ExternalNewsImporting extends MyAppModel
{
    use funcsTrait;

    protected $table      = 'external_news_importing';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable   = [ 'title', 'url', 'status' ];

    private static $externalNewsImportingStatusLabelValueArray = Array('1' => 'Is active', '0' => 'Is not active');
    private static $externalNewsImportingImportImageLabelValueArray = Array('1' => 'Yes', '0' => 'No');

    public static function getExternalNewsImportingStatusValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$externalNewsImportingStatusLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getExternalNewsImportingStatusLabel(string $status):string
    {
        if (!empty(self::$externalNewsImportingStatusLabelValueArray[$status])) {
            return self::$externalNewsImportingStatusLabelValueArray[$status];
        }
        return '';
    }



    public static function getExternalNewsImportingImportImageValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$externalNewsImportingImportImageLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getExternalNewsImportingImportImageLabel(string $import_image):string
    {
        if (!empty(self::$externalNewsImportingImportImageLabelValueArray[$import_image])) {
            return self::$externalNewsImportingImportImageLabelValueArray[$import_image];
        }
        return '';
    }


    public function scopeGetByTitle($query, $title= null, $partial= false)
    {
        if (empty($title)) return $query;
        return $query->where('.title', (!$partial?'=':'like'), ($partial?'%':''). $title .($partial?'%':'') );
    }

    public function scopeGetByStatus($query, $status= null)
    {
        if ( !isset($status)  or strlen($status) == 0 ) return $query;
        return $query->where( with(new ExternalNewsImporting)->getTable().'.status', $status );
    }


    public function scopeGetByImportImage($query, $import_image= null)
    {
        if ( !isset($import_image)  or strlen($import_image) == 0 ) return $query;
        return $query->where( with(new ExternalNewsImporting)->getTable().'.import_image', $import_image );
    }

    /* check if provided title is unique for external_news_importing.title field */
    public static function getSimilarExternalNewsImportingByTitle( string $title, int $id= null, bool $return_count = false )
    {
        $quoteModel = ExternalNewsImporting::where( 'title', $title );
        if ( !empty( $id ) ) {
            $quoteModel = $quoteModel->where( 'id', '!=' , $id );
        }
        if ( $return_count ) {
            return $quoteModel->get()->count();
        }
        $retRow= $quoteModel->get();
        if ( empty($retRow[0]) ) return false;
        return $retRow[0];
    }

    public static function getValidationRulesArray($external_news_importing_id= null) : array
    {
        $validationRulesArray = [
            'title' => [
                'required',
                'string',
                'max:100',
                Rule::unique(with(new ExternalNewsImporting)->getTable())->ignore($external_news_importing_id),
            ],
            'url' => [
                'required',
                'url',
                'max:100',
                Rule::unique(with(new ExternalNewsImporting)->getTable())->ignore($external_news_importing_id),
            ],
            'status'     =>   'required|in:'.with( new ExternalNewsImporting)->getValueLabelKeys( ExternalNewsImporting::getExternalNewsImportingStatusValueArray(false) ),
            'import_image'     =>   'required|in:'.with( new ExternalNewsImporting)->getValueLabelKeys( ExternalNewsImporting::getExternalNewsImportingImportImageValueArray
                (false) ),
        ];
        return $validationRulesArray;
    }

}