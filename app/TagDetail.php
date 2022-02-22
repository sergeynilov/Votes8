<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Elasticsearch\Client;

class TagDetail extends MyAppModel
{
    use Notifiable;
    use funcsTrait;

    protected $table = 'tag_details';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $tagDetailImagePropsArray = [];
    protected $img_filename_max_length = 100;
    
    protected static $uploads_tag_details_dir = 'tag-details/-tag-detail-';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_id',
        'image',
        'description',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'meta_keywords' => 'array'
    ];

    /* CREATE TABLE `tag_details` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `tag_id` INT(10) UNSIGNED NOT NULL,
        `image` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
        `description` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
        PRIMARY KEY (`id`),
        UNIQUE INDEX `tag_details_tag_id_unique` (`tag_id`),
        CONSTRAINT `tag_details_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
    )
    COLLATE='utf8mb4_unicode_ci'
    ENGINE=InnoDB
    AUTO_INCREMENT=33
    ; */
    public function Tag()
    {
        return $this->belongsTo('App\MyTag', 'tag_id');
    }


    protected static function boot() {
        parent::boot();
        static::deleting(function($tagDetail) {
            $tag_detail_image_path= TagDetail::getTagDetailImagePath($tagDetail->tag_id, $tagDetail->image, true);
            TagDetail::deleteFileByPath($tag_detail_image_path, true);
        });
    }


    public function scopeGetByTagId($query, $tag_id= null)
    {
        if (!empty($tag_id)) {
            if ( is_array($tag_id) ) {
                $query->whereIn( with(new TagDetail)->getTable().'.tag_id', $tag_id );
            } else {
                $query->where( with(new TagDetail)->getTable() . '.tag_id', $tag_id );
            }
        }
        return $query;
    }
    
    public function getImgFilenameMaxLength(): int
    {
        return $this->img_filename_max_length;
    }


    public static function getTagDetailDir(int $tag_id): string
    {
        return self::$uploads_tag_details_dir . $tag_id . '/';
    }

    public static function getTagDetailImagePath(int $tag_id, $image): string
    {
        if (empty($image)) {
            return '';
        }
        return self::$uploads_tag_details_dir . $tag_id . '/' . $image;
    }



    public static function setTagDetailImageProps(int $tag_id, string $image = null, bool $skip_non_existing_file = false): array
    {
        if (empty($image) and $skip_non_existing_file) {
            return [];
        }

        $dir_path = self::$uploads_tag_details_dir . '' . $tag_id . '';
        $file_full_path = $dir_path . '/' . $image;
        $file_exists    = ( !empty($image) and Storage::disk('local')->exists( 'public/'.$file_full_path) );

        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = config('app.empty_img_url');       //file:///_wwwroot/lar/votes/public/images/emptyImg.png
            $image          = with(new TagDetail)->getFilenameBasename($file_full_path);
        }

        $image_path = $file_full_path;
        if ($file_exists) {
            $imagePropsArray = ['image' => $image, 'image_path' => $image_path, 'image_url' => Storage::url( $file_full_path ) ];
            $image_full_path = ( $image_path);
            $tagDetailImgProps = with(new TagDetail)->getCFImageProps(base_path() . '/storage/app/public/'.$image_full_path, $imagePropsArray);
        } else {
            $tagDetailImgProps = ['image' => $image, 'image_path' => $image_path, 'image_url' => ($file_full_path) ];
        }

//        echo '<pre>$tagDetailImgProps::'.print_r($tagDetailImgProps,true).'</pre>';
        return $tagDetailImgProps;
    }


    /* get additional properties of cms_item image : path, url, size etc... */
    public function getTagDetailImagePropsAttribute(): array
    {
        return $this->tagDetailImagePropsArray;
    }


    /* set additional properties of cms_item image : path, url, size etc... */
    public function setTagDetailImagePropsAttribute(array $tagDetailImagePropsArray)
    {
        $this->tagDetailImagePropsArray = $tagDetailImagePropsArray;
    }



//    public function bulkVotesToElastic()
//    {
//        $elastic = $this->app->make(App\Elastic\Elastic::class);
//
//        Post::chunk(100, function ($posts) use ($elastic) {
//            foreach ($posts as $post) {
//                $elastic->index([
//                    'index' => 'blog',
//                    'type' => 'post',
//                    'id' => $post->id,
//                    'body' => $post->toArray()
//                ]);
//            }
//        });
//    }


}