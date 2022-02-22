<?php

namespace App;

use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;

class SettingsText extends MyAppModel {
    protected $table      = 'settings_text';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    use funcsTrait;

    protected $settingsImageImagePropsArray = [];
    protected $img_filename_max_length = 255;


    public static function scopeGetByName($query, $name)
    {
        return $query->where( with(new SettingsText)->getTable().'.name', '=', $name);
    }

    public static function getText( $name, $default_text = '' ) {
        // return ''; // COMMENT
        $text= SettingsText::getByName($name)->first();
        if ( empty($text->text) ) return $default_text;
        return $text->text;
    }


    public static function getSettingsTextList( array $filtersArray = [], bool $return_keys_array= false )
    {
        // return ''; // COMMENT
        $settings_table_name= with(new SettingsText)->getTable();
        $quoteModel= SettingsText::from(  $settings_table_name );

        /* Set filter condition for all nonempty values in $filtersArray */
        if ( !empty($filtersArray) and is_array($filtersArray) ) {
            $quoteModel->whereIn( 'name', $filtersArray );
        }

        if ( ! empty( $limit ) and (int) $limit > 0 ) {
            $quoteModel = $quoteModel->take( $limit );
        }

        $settingsValuesList = $quoteModel->get();
        if ( $return_keys_array ) {
            $retArray= [];
            foreach( $settingsValuesList as $next_key=>$nextSettingsTextValue ) {
                $retArray[$nextSettingsTextValue->name]= $nextSettingsTextValue->text;
            }
            return $retArray;
        }
        return $settingsValuesList;

    }

    public static function getSimilarSettingsTextByName( string $name, int $id= null, bool $return_count = false )
    {
        $quoteModel = SettingsText::whereRaw( SettingsText::myStrLower('name', false, false) .' = '. SettingsText::myStrLower( SettingsText::mysqlEscape($name), true,false) );
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


    public static function updateSettingsText( array $dataArray ) : array
    {
        foreach( $dataArray as $next_settings_name=> $next_settings_text ) {
            $similarSettingsText= SettingsText::getSimilarSettingsTextByName($next_settings_name);
//            echo '<pre>$similarSettingsText::'.print_r($similarSettingsText,true).'</pre>';
//            echo '<pre>$next_settings_text::'.print_r($next_settings_text,true).'</pre>';
            if ( $similarSettingsText === false ) {
//                echo '<pre>INSIDE</pre>';
                $similarSettingsText         = new SettingsText();
                $similarSettingsText->name   = $next_settings_name;
            }
            $similarSettingsText->text      = $next_settings_text;
            $similarSettingsText->save();
        }
        return [ 'error_code' => 0, 'errorsList' => [], 'success_message' => "Settings Text were updated" ];
    }




}