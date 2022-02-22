<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Storage;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;


class Banner extends MyAppModel
{
    use funcsTrait;

    protected $table = 'banners';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [ 'text', 'logo', 'short_descr', 'url', 'active', 'ordering', 'view_type' ];

    protected $bannerLogoPropsArray = [];
    protected $img_filename_max_length = 100;

    private static $bannerActiveLabelValueArray = Array('1' => 'Is active', '0' => 'Inactive');
    private static $bannerViewTypeLabelValueArray = Array(1 => 'Blue dark', 2 => 'Blue light', 3 => 'Blue medium' ); 

    protected static $uploads_banners_dir = 'banners/-banner-';


    protected static function boot() {
        parent::boot();
        static::deleting(function($banner) {
            $banner_image_path= Banner::getBannerLogoPath($banner->id, $banner->logo, true);
//            with(new Banner)->debToFile(print_r($banner_image_path, true), '$banner_image_path::');
            Banner::deleteFileByPath($banner_image_path, true);
        });
    }

    protected $casts = [
    ];


    public function getImgFilenameMaxLength(): int
    {
        return $this->img_filename_max_length;
    }



    public static function getBannerActiveValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$bannerActiveLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getBannerActiveLabel(string $active):string
    {
        if (!empty(self::$bannerActiveLabelValueArray[$active])) {
            return self::$bannerActiveLabelValueArray[$active];
        }
        return self::$bannerActiveLabelValueArray[0];
    }



    public static function getBannerViewTypeValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$bannerViewTypeLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getBannerViewTypeLabel(string $view_type):string
    {
        if (!empty(self::$bannerViewTypeLabelValueArray[$view_type])) {
            return self::$bannerViewTypeLabelValueArray[$view_type];
        }
        return self::$bannerViewTypeLabelValueArray[0];
    }


    public function scopeGetByText($query, $text= null, $partial= false)
    {
        if (empty($text)) return $query;
        return $query->where( with(new Banner)->getTable().'.text', (!$partial?'=':'like'), ($partial?'%':''). $text .($partial?'%':'') );
    }

    public function scopeGetByViewType($query, $view_type= null)
    {
        if (empty($view_type)) return $query;
        return $query->where(with(new Banner)->getTable().'.view_type', $view_type );
    }


    public function scopeGetByActive($query, $active= null)
    {
        if (!isset($active) or strlen($active) == 0 ) return $query;
        return $query->where( with(new Banner)->getTable().'.active', $active );
    }

    /* check if provided text is unique for banners.text field */
    public static function getSimilarBannerByText( string $text, int $id= null, bool $return_count = false )
    {
        $quoteModel = Banner::where( 'text', $text );
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

    public static function getValidationRulesArray($banner_id= null) : array
    {
        $validationRulesArray = [
            'text' => [
                'required',
                'string',
                'max:20',
            ],
            'url' => [
                'required',
                'string',
                'max:255',
            ],
            'active'     =>  'required|in:'.with( new Banner)->getValueLabelKeys( Banner::getBannerActiveValueArray(false) ),
            'ordering'   =>  'required|integer',
            'view_type'  =>  'required|in:'.with( new Banner)->getValueLabelKeys( Banner::getBannerViewTypeValueArray(false) ),
        ];
        return $validationRulesArray;
    }

    public static function getBannerDir(int $banner_id): string
    {
        return self::$uploads_banners_dir . $banner_id . '/';
    }

    public static function getBannerLogoPath(int $banner_id, $logo): string
    {
        if (empty($logo)) {
            return '';
        }
        return self::$uploads_banners_dir . $banner_id . '/' . $logo;
    }


    public static function setBannerLogoProps(int $banner_id, string $logo = null, bool $skip_non_existing_file = false): array
    {
//        echo '<pre>$banner_id::'.print_r($banner_id,true).'</pre>';
//        echo '<pre>setBannerLogoProps  $logo::'.print_r($logo,true).'</pre>';
//        echo '<pre>$skip_non_existing_file::'.print_r($skip_non_existing_file,true).'</pre>';
        if (empty($logo) and $skip_non_existing_file) {
            return [];
        }

        $dir_path = self::$uploads_banners_dir . '' . $banner_id . '';
        $file_full_path = /*'/app/'.*/$dir_path . '/' . $logo;
//        echo '<pre>!!!!$file_full_path::'.print_r($file_full_path,true).'</pre>';
        $file_exists    = ( !empty($logo) and Storage::disk('local')->exists( 'public/'.$file_full_path) );

//        echo '<pre>$file_exists::'.print_r($file_exists,true).'</pre>';
        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = config('app.empty_img_url');       //file:///_wwwroot/lar/votes/public/images/emptyImg.png
//            echo '<pre>++ $file_full_path::'.print_r($file_full_path,true).'</pre>';
            $logo          = with(new Banner)->getFilenameBasename($file_full_path);
        }

        $logo_path = $file_full_path;
        if ($file_exists) {
            $logoPropsArray = ['image' => $logo, 'image_path' => $logo_path, 'image_url' => Storage::url( $file_full_path ) ];
            $logo_full_path = ( $logo_path);
//            echo '<pre>-1 $logo_full_path::'.print_r($logo_full_path,true).'</pre>';
            $bannerImgProps = with(new Banner)->getCFImageProps(base_path() . '/storage/app/public/'.$logo_full_path, $logoPropsArray);
        } else {
            $bannerImgProps = ['image' => $logo, 'image_path' => $logo_path, 'image_url' => ($file_full_path) ];
        }
        
//        echo '<pre>$bannerImgProps::'.print_r($bannerImgProps,true).'</pre>';
        return $bannerImgProps;

    }

    public function getBannerLogoPropsAttribute(): array
    {
        return $this->bannerLogoPropsArray;
    }


    /* set additional properties of cms_item image : path, url, size etc... */
    public function setBannerLogoPropsAttribute(array $bannerLogoPropsArray)
    {
        $this->bannerLogoPropsArray = $bannerLogoPropsArray;
    }

}