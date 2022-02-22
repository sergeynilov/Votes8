<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;


class PageContentImage extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'page_content_images';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected static $img_preview_width= 320;
    protected static $img_preview_height= 240;


    protected $pageContentImageImagePropsArray = [];
    protected $img_filename_max_length = 255;

    protected static $uploads_page_content_images_dir = 'page-content-images/-page-content-image-';
    protected $fillable = [ 'filename', 'is_main', 'page_content_id' , 'is_video', 'video_width', 'video_height', 'info' ];


    private static $pageContentImageIsMainLabelValueArray = Array('1' => 'Is Main', '0' => 'Is Not Main');
    private static $pageContentImageIsVideoLabelValueArray = Array('1' => 'Is Video', '0' => 'Is Not Video');

    protected $casts = [
    ];



    public function getImgFilenameMaxLength(): int
    {
        return $this->img_filename_max_length;
    }


    public function pageContent()
    {
        return $this->belongsTo('App\PageContent');
    }


    protected static function boot() {
        parent::boot();
        static::deleting(function($pageContentImage) {
            $page_content_image_image_path= PageContentImage::getPageContentImageImagePath($pageContentImage->page_content_id, $pageContentImage->filename);
            PageContentImage::deleteFileByPath($page_content_image_image_path, true);
        });
    }


    public function scopeGetByPageContent($query, $page_content_id= null)
    {
        if (!empty($page_content_id)) {
            if ( is_array($page_content_id) ) {
                $query->whereIn(with(new PageContentImage)->getTable().'.page_content_id', $page_content_id);
            } else {
                $query->where(with(new PageContentImage)->getTable().'.page_content_id', $page_content_id);
            }
        }
        return $query;
    }


    public static function getPageContentImageIsMainValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$pageContentImageIsMainLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPageContentImageIsMainLabel(string $is_main):string
    {
        if (!empty(self::$pageContentImageIsMainLabelValueArray[$is_main])) {
            return self::$pageContentImageIsMainLabelValueArray[$is_main];
        }
        return self::$pageContentImageIsMainLabelValueArray[0];
    }


    public static function getPageContentImageIsVideoValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$pageContentImageIsVideoLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPageContentImageIsVideoLabel(string $is_video):string
    {
        if (!empty(self::$pageContentImageIsVideoLabelValueArray[$is_video])) {
            return self::$pageContentImageIsVideoLabelValueArray[$is_video];
        }
        return self::$pageContentImageIsVideoLabelValueArray[0];
    }


    public static function getPageContentImageDir(int $page_content_id): string
    {
        return self::$uploads_page_content_images_dir . $page_content_id . '/';
    }

    public static function getPageContentImageImagePath(int $page_content_id, $image): string
    {
        if (empty($image)) {
            return '';
        }
        return self::$uploads_page_content_images_dir . $page_content_id . '/' . $image;
    }


    public static function setPageContentImageImageProps(int $page_content_id, string $image = null, bool $skip_non_existing_file = false): array
    {
        if (empty($image) and $skip_non_existing_file) {
            return [];
        }
        $file_was_found= true;
        $dir_path = self::$uploads_page_content_images_dir . '' . $page_content_id . '';
        $file_full_path = $dir_path . '/' . $image;
        $file_exists    = ( !empty($image) and Storage::disk('local')->exists('public/' . $file_full_path) );

        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_was_found= false;
            $file_full_path = config('app.empty_img_url');
        }

        $image_path = $file_full_path;
        //        $image_path= public_path('storage/'.$dir_path . '/' . $image);
        //        $image_path= public_path('storage/app/'.$dir_path . '/' . $image);
        //        $image_path= 'storage/app/'.$dir_path . '/' . $image;
        //        $image_path= $file_full_path;
        // /_wwwroot/lar/PageContentImages/storage/app/public/page_content_images/-page_content_image-1/tobe.png
        if ($file_exists) {
            $image_url       = 'storage/app/' . $dir_path . '/' . $image;
            $imagePropsArray = ['image' => $image, 'image_path' => $image_path, 'image_url' => '/storage/' . $dir_path . '/' . $image];
            $image_full_path = base_path() . '/storage/app/public/' . $image_path;
        } else {
            $imagePropsArray = ['image' => $image, 'image_path' => $image_path, 'image_url' => $file_full_path];
            $image_full_path = base_path() . '/public/' . $image_path;
        }

        if ( ! empty($previewSizeArray['width'])) {
            $imagePropsArray['preview_width']  = $previewSizeArray['width'];
            $imagePropsArray['preview_height'] = $previewSizeArray['height'];
        }
        $pageContentImageImgProps = with(new PageContentImage)->getCFImageProps($image_full_path, $imagePropsArray);

        if ( !$file_was_found ) {
            $pageContentImageImgProps['file_info'] = 'File "' . $image . '" not found ';
        }
        return $pageContentImageImgProps;

    }

    /* get additional properties of page_content image : path, url, size etc... */
    public function getPageContentImageImagePropsAttribute(): array
    {
        return $this->pageContentImageImagePropsArray;
    }

    /* set additional properties of page_content image : path, url, size etc... */
    public function setPageContentImageImagePropsAttribute(array $pageContentImageImagePropsArray)
    {
        $this->pageContentImageImagePropsArray = $pageContentImageImagePropsArray;
    }


    public static function getSimilarPageContentImageByFilename( string $filename, int $page_content_id, int $id= null, $return_count= false )
    {
        $quoteModel = PageContentImage::where( 'filename', $filename );
        $quoteModel = $quoteModel->where( 'page_content_id', '=' , $page_content_id );
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


    //         return PageContentImage::getValidationRulesArray( $request->get('page_content_id'), $request->get('id') );
    public static function getValidationRulesArray($page_content_id, $page_content_image_id = null): array
    {
        $additional_item_value_validation_rule= 'check_page_content_image_unique_by_name:'.$page_content_id.','.( !empty($page_content_image_id)?$page_content_image_id:'');


        $validationRulesArray            = [
            'page_content_id'      => 'required|exists:'.( with(new PageContent)->getTable() ).',id',
//            'page_content_id'             => 'required|max:255|' . $additional_item_value_validation_rule,


            'name'             => 'required|max:255|' . $additional_item_value_validation_rule,
            'is_video'       => 'required|in:' . with(new PageContentImage)->getValueLabelKeys(PageContentImage::getPageContentImageIsCorrectValueArray(false)),
            'ordering'         => 'required|integer',
        ];

        return $validationRulesArray;
    }

    public static function bulkPageContentImagesToElastic()
    {
        $elastic = app(\App\Elastic\Elastic::class);

        $elasticsearch_root_index  = config('app.elasticsearch_root_index');
        $elasticsearch_type        = with(new PageContentImage)->getElasticsearchType();

        PageContentImage::chunk(100, function ($PageContentImages) use ($elastic, $elasticsearch_root_index, $elasticsearch_type) {
            foreach ($PageContentImages as $nextPageContentImage) {
                $elastic->index([
                    'index' => $elasticsearch_root_index,
                    'type'  => $elasticsearch_type,
                    'id'    => $nextPageContentImage->id,
                    'body'  => [
                        'id'          => $nextPageContentImage->id,
                        'name'        => $nextPageContentImage->name,
                        'ordering'    => $nextPageContentImage->ordering,
                    ]
                ]);
            }
        });
    }

    public function getImgPreviewWidth() : int
    {
        return self::$img_preview_width;
    }

    public function getImgPreviewHeight() : int
    {
        return self::$img_preview_height;
    }

}
