<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use DB;
use App\MyAppModel;
use App\PageContentImage;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Carbon\Carbon;
use Config;

use Illuminate\Support\Facades\File;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Elasticsearch\Client;
use App\library\CheckValueType;

class PageContent extends MyAppModel implements Feedable
{
    use Notifiable;
    use Sluggable;
    use funcsTrait;

    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'page_contents';

    protected $pageContentImagePropsArray = [];
    protected $img_filename_max_length = 100;

    protected static $uploads_page_contents_dir = 'page-contents/-page-content-';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'content_shortly',
        'creator_id',
        'is_featured',
        'is_homepage',
        'published',
        'page_type',
        'image',
        'source_type',
        'source_url',
    ];

    protected $casts = [
        'meta_keywords' => 'array'
    ];

    public function toFeedItem(): FeedItem
    {
        $updated= !empty($this->updated_at) ? $this->updated_at : $this->created_at;
        $date        = Carbon::createFromTimestamp(strtotime($updated));//->format($date_carbon_format_as_text);

        $item_slug= route('news', $this->slug);
        $creator= User::find($this->creator_id);
        $creator_username= ( !empty($creator->username) ? $creator->username : '' );
        return FeedItem::create()
                       ->id( $this->id )
                       ->title( $this->title )
                       ->summary( !empty($this->content_shortly) ? $this->content_shortly : '' )
                       ->updated( $date )
                       ->link( $item_slug )
                       ->author( $creator_username );
    }

    public static function getNewsFeedItems()
    {
        $feed_items_on_limit= Settings::getValue('feed_items_on_limit', CheckValueType::cvtInteger, 10);
        return PageContent
            ::getByPublished(true)
            ->getByPageType('N')
            ->orderBy('created_at', 'desc')
            ->limit($feed_items_on_limit)
            ->get(); // get only published news

    }

    private static $pageContentIsFeaturedLabelValueArray = Array('1' => 'Is Featured', '0' => 'Is Not Featured');
    private static $pageContentPageTypeLabelValueArray = Array('N' => 'Our News', 'E' => 'External News', 'P' => 'Page', 'B' => 'Blog');
    private static $pageContentIsHomepageLabelValueArray = Array('1' => 'Is Homepage', '0' => 'Is Not Homepage');
    private static $pageContentPublishedLabelValueArray = Array('1' => 'Is Published', '0' => 'Is Not Published');

    public function pageContentImages()
    {
        return $this->hasMany('App\PageContentImage');
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($pageContent) {
            $page_content_image_path= PageContent::getPageContentImagePath($pageContent->id, $pageContent->image, true);
            PageContent::deleteFileByPath($page_content_image_path, true);

            foreach ( $pageContent->pageContentImages()->get() as $nextPageContentImages ) {
                $nextPageContentImages->delete();
            }
        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


    public function scopeGetByTitle($query, $title = null, $partial = false)
    {
        if (empty($title)) {
            return $query;
        }
        return $query->where(with(new PageContent)->getTable().'.title', (! $partial ? '=' : 'like'), ($partial ? '%' : '') . $title . ($partial ? '%' : ''));
    }


    public function scopeGetByPublished($query, $published = null)
    {
        if (!isset($published) or strlen($published) == 0) {
            return $query;
        }
        return $query->where(with(new PageContent)->getTable().'.published', $published);
    }

    public function scopeGetByPageType($query, $page_type = null)
    {
        if (empty($page_type)) {
            return $query;
        }
        return $query->where(with(new PageContent)->getTable().'.page_type', $page_type);
    }

    public function scopeGetByIsFeatured($query, $is_featured = null)
    {
        if (!isset($is_featured) or strlen($is_featured) == 0) {
            return $query;
        }
        return $query->where(with(new PageContent)->getTable().'.is_featured', $is_featured);
    }

    public function scopeGetByIsHomepage($query, $is_homepage = null)
    {
        if (!isset($is_homepage) or strlen($is_homepage) == 0) {
            return $query;
        }
        return $query->where(with(new PageContent)->getTable().'.is_homepage', $is_homepage);
    }


    public function scopeGetByCreatorId($query, $creator_id= null)
    {
        if (!empty($creator_id)) {
            if ( is_array($creator_id) ) {
                $query->whereIn( with(new PageContent)->getTable().'.creator_id', $creator_id );
            } else {
                $query->where( with(new PageContent)->getTable() . '.creator_id', $creator_id );
            }
        }
        return $query;
    }

    public function scopeGetBySlug($query, $slug = null)
    {
        if (empty($slug)) {
            return $query;
        }
        return $query->where(with(new PageContent)->getTable().'.slug', $slug);
    }

    public static function getPageContentIsFeaturedValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$pageContentIsFeaturedLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPageContentIsFeaturedLabel(string $is_featured): string
    {
        if ( ! empty(self::$pageContentIsFeaturedLabelValueArray[$is_featured])) {
            return self::$pageContentIsFeaturedLabelValueArray[$is_featured];
        }
        return self::$pageContentIsFeaturedLabelValueArray[0];
    }

    public static function getPageContentPageTypeValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$pageContentPageTypeLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }

    public static function getPageContentPageTypeLabel(string $page_type): string
    {
        if ( ! empty(self::$pageContentPageTypeLabelValueArray[$page_type])) {
            return self::$pageContentPageTypeLabelValueArray[$page_type];
        }

        return '';
    }


    public static function getPageContentIsHomepageValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$pageContentIsHomepageLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPageContentIsHomepageLabel(string $is_homepage): string
    {
        if ( ! empty(self::$pageContentIsHomepageLabelValueArray[$is_homepage])) {
            return self::$pageContentIsHomepageLabelValueArray[$is_homepage];
        }
        return self::$pageContentIsHomepageLabelValueArray[0];
    }

    public static function getPageContentPublishedValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$pageContentPublishedLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPageContentPublishedLabel(string $published): string
    {
        if ( ! empty(self::$pageContentPublishedLabelValueArray[$published])) {
            return self::$pageContentPublishedLabelValueArray[$published];
        }
        return self::$pageContentPublishedLabelValueArray[0];
    }

    public function getImgFilenameMaxLength(): int
    {
        return $this->img_filename_max_length;
    }


    public static function getPageContentDir(int $page_content_id): string
    {
        return self::$uploads_page_contents_dir . $page_content_id . '/';
    }

    public static function getPageContentImagePath(int $page_content_id, $image): string
    {
        if (empty($image)) {
            return '';
        }
        return self::$uploads_page_contents_dir . $page_content_id . '/' . $image;
    }


    /*
    public static function setPageContentImageProps(int $page_content_id, string $image = null, bool $skip_non_existsing_file = false): array
    {
        if (empty($image) and $skip_non_existsing_file) {
            return [];
        }

        $dir_path = self::$uploads_page_contents_dir . '' . $page_content_id . '';
        $file_full_path = $dir_path . '/' . $image;
        $file_exists    = ( !empty($image) and Storage::disk('local')->exists('public/' . $file_full_path) );

 */

    public static function setPageContentImageProps(int $page_content_id, string $image = null, bool $skip_non_existing_file = false): array
    {
//        echo '<pre>$page_content_id::'.print_r($page_content_id,true).'</pre>';
//        echo '<pre>setPageContentImageProps  $image::'.print_r($image,true).'</pre>';
//        echo '<pre>$skip_non_existing_file::'.print_r($skip_non_existing_file,true).'</pre>';
        if (empty($image) and $skip_non_existing_file) {
            return [];
        }


        $dir_path = self::$uploads_page_contents_dir . '' . $page_content_id . '';
        $file_full_path = /*'/app/'.*/$dir_path . '/' . $image;
//        echo '<pre>!!!!$file_full_path::'.print_r($file_full_path,true).'</pre>';
        $file_exists    = ( !empty($image) and Storage::disk('local')->exists( 'public/'.$file_full_path) );

//        echo '<pre>$file_exists::'.print_r($file_exists,true).'</pre>';
        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = config('app.empty_img_url');       //file:///_wwwroot/lar/votes/public/images/emptyImg.png
//            echo '<pre>++ $file_full_path::'.print_r($file_full_path,true).'</pre>';
            $image          = with(new PageContent)->getFilenameBasename($file_full_path);
        }

        $image_path = $file_full_path;
        if ($file_exists) {
            $imagePropsArray = ['image' => $image, 'image_path' => $image_path, 'image_url' => Storage::url( $file_full_path ) ];
            $image_full_path = ( $image_path);
//            echo '<pre>-1 $image_full_path::'.print_r($image_full_path,true).'</pre>';
            $pageContentImgProps = with(new PageContent)->getCFImageProps(base_path() . '/storage/app/public/'.$image_full_path, $imagePropsArray);
        } else {
            $pageContentImgProps = ['image' => $image, 'image_path' => $image_path, 'image_url' => ($file_full_path) ];
        }


//        echo '<pre>$pageContentImgProps::'.print_r($pageContentImgProps,true).'</pre>';
        return $pageContentImgProps;

    }

    // VALID
    //                              votes/public/images/emptyImg.png

    //  in App
    //  /mnt/_work_sdb8/wwwroot/lar/votes/public/images/emptyImg.png

    /* get additional properties of cms_item image : path, url, size etc... */
    public function getPageContentImagePropsAttribute(): array
    {
        return $this->pageContentImagePropsArray;
    }


    /* set additional properties of cms_item image : path, url, size etc... */
    public function setPageContentImagePropsAttribute(array $pageContentImagePropsArray)
    {
        $this->pageContentImagePropsArray = $pageContentImagePropsArray;
    }



    /* check if provided source_url is unique for page_contents.source_url field */
    public static function getSimilarPageContentBySourceUrl( string $source_url, int $id= null, bool $return_count = false )
    {
        $quoteModel = PageContent::where( 'source_url', $source_url );
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





    public static function getValidationRulesArray($page_content_id = null): array
    {
        $validationRulesArray = [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new PageContent)->getTable())->ignore($page_content_id),
            ],

            'content'        => 'required',
            'content_shortly'=> 'max:255',
            'is_featured'    => 'required|in:' . with(new PageContent)->getValueLabelKeys(PageContent::getPageContentIsFeaturedValueArray(false)),
            'is_homepage'    => 'required|in:' . with(new PageContent)->getValueLabelKeys(PageContent::getPageContentIsHomepageValueArray(false)),
            'published'      => 'required|in:' . with(new PageContent)->getValueLabelKeys(PageContent::getPageContentPublishedValueArray(false)),
            'page_type'      => 'required|in:' . with(new PageContent)->getValueLabelKeys(PageContent::getPageContentPageTypeValueArray(false)),

            'source_type'    => 'max:20',
            'source_url'     => 'max:255',
        ];
        return $validationRulesArray;
    }


    public function downloads()
    {
        return $this->morphToMany(Download::class, 'downloadable');
    }




}
