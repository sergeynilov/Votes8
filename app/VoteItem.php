<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;


class VoteItem extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'vote_items';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $voteItemImagePropsArray = [];
    protected $img_filename_max_length = 255;
    
    protected static $uploads_vote_items_dir = 'vote-items/-vote-item-';

    protected $fillable = [ 'name', 'ordering', 'vote_id' , 'is_correct', 'image' ];
    protected static $logAttributes = ['*'];



    private static $voteItemIsCorrectLabelValueArray = Array('1' => 'Is Correct', '0' => 'Is Not Correct');

    protected $casts = [
    ];


//    public function getElasticsearchType(): string
//    {
//        return $this->elasticsearch_type;
//    }

    public function getImgFilenameMaxLength(): int
    {
        return $this->img_filename_max_length;
    }


    public function vote()
    {
        return $this->belongsTo('App\Vote');
    }


    public function voteItemUsersResults()
    {
        return $this->hasMany('App\VoteItemUsersResult');                //
    }


    protected static function boot() {
        parent::boot();
        static::deleting(function($voteItem) {
            foreach ( $voteItem->voteItemUsersResults()->get() as $nextVoteItemUsersResult ) { // vote_item_users_result
                $nextVoteItemUsersResult->delete();
            }

            $vote_item_image_path= VoteItem::getVoteItemImagePath($voteItem->id, $voteItem->image, true);
            VoteItem::deleteFileByPath($vote_item_image_path, true);
        });
    }


    public function scopeGetByVote($query, $vote_id= null)
    {
        if (!empty($vote_id)) {
            if ( is_array($vote_id) ) {
                $query->whereIn(with(new VoteItem)->getTable().'.vote_id', $vote_id);
            } else {
                $query->where(with(new VoteItem)->getTable().'.vote_id', $vote_id);
            }
        }
        return $query;
    }
    

    public static function getVoteItemIsCorrectValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$voteItemIsCorrectLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getVoteItemIsCorrectLabel(string $is_correct):string
    {
        if (!empty(self::$voteItemIsCorrectLabelValueArray[$is_correct])) {
            return self::$voteItemIsCorrectLabelValueArray[$is_correct];
        }
        return self::$voteItemIsCorrectLabelValueArray[0];
    }


    public static function getVoteItemDir(int $vote_item_id): string
    {
        return self::$uploads_vote_items_dir . $vote_item_id . '/';
    }

    public static function getVoteItemImagePath(int $vote_item_id, $image): string
    {
        if (empty($image)) {
            return '';
        }
        return self::$uploads_vote_items_dir . $vote_item_id . '/' . $image;
    }


    public static function setVoteItemImageProps(int $vote_item_id, string $image = null, bool $skip_non_existing_file = false): array
    {
        if (empty($image) and $skip_non_existing_file) {
            return [];
        }

        $dir_path = self::$uploads_vote_items_dir . '' . $vote_item_id . '';
        $file_full_path = $dir_path . '/' . $image;
        $file_exists    = ( !empty($image) and Storage::disk('local')->exists( 'public/'.$file_full_path) );

        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = config('app.empty_img_url');
            $image          = with(new VoteItem)->getFilenameBasename($file_full_path);
        }

        $image_path = $file_full_path;
        if ($file_exists) {
            $imagePropsArray = ['image' => $image, 'image_path' => $image_path, 'image_url' => Storage::url( $file_full_path ) ];
            $image_full_path = ( $image_path);
            $voteItemImgProps = with(new VoteItem)->getCFImageProps(base_path() . '/storage/app/public/'.$image_full_path, $imagePropsArray);
        } else {
            $voteItemImgProps = ['image' => $image, 'image_path' => $image_path, 'image_url' => ($file_full_path) ];
        }


//        echo '<pre>$voteItemImgProps::'.print_r($voteItemImgProps,true).'</pre>';
        return $voteItemImgProps;

    }

    /* get additional properties of vote_item image : path, url, size etc... */
    public function getVoteItemImagePropsAttribute(): array
    {
        return $this->voteItemImagePropsArray;
    }

    /* set additional properties of vote_item image : path, url, size etc... */
    public function setVoteItemImagePropsAttribute(array $voteItemImagePropsArray)
    {
        $this->voteItemImagePropsArray = $voteItemImagePropsArray;
    }

    /* check if provided item_value is unique for attribute_item.item_value withing 1 attribute ( $attribute_id ) field */
/*    public static function getSimilarAttributeItemByItemValue( string $item_value, int $attribute_id, int $id= null, $return_count= false )
    {
        $quoteModel = AttributeItem::whereRaw( AttributeItem::pgStrLower('item_value', false, false) .' = '. AttributeItem::pgStrLower(AttributeItem::pgEscape($item_value), true,false) );
        $quoteModel = $quoteModel->where( 'attribute_id', '=' , $attribute_id );
        if ( !empty( $id ) ) {
            $quoteModel = $quoteModel->where( 'id', '!=' , $id );
        }

        if ( $return_count ) {
            return $quoteModel->get()->count();
        }
        $retRow= $quoteModel->get();
        if ( empty($retRow[0]) ) return false;
        return $retRow[0];
    }*/

    /* check if provided name is unique for vote_item.name withing 1 vote ( $vote_id ) field */
    //             $vote_item_count = VoteItem::getSimilarVoteItemByName( $value, (int)$vote_id, (int)$vote_item_id, true );

    public static function getSimilarVoteItemByName( string $name, int $vote_id, int $id= null, $return_count= false )
    {
        $quoteModel = VoteItem::where( 'name', $name );
        $quoteModel = $quoteModel->where( 'vote_id', '=' , $vote_id );
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


    //         return VoteItem::getValidationRulesArray( $request->get('vote_id'), $request->get('id') );
    public static function getValidationRulesArray($vote_id, $vote_item_id = null): array
    {
        $additional_item_value_validation_rule= 'check_vote_item_unique_by_name:'.$vote_id.','.( !empty($vote_item_id)?$vote_item_id:'');


        $validationRulesArray            = [
            'vote_id'      => 'required|exists:'.( with(new Vote)->getTable() ).',id',
//            'vote_id'             => 'required|max:255|' . $additional_item_value_validation_rule,


            'name'             => 'required|max:255|' . $additional_item_value_validation_rule,
            'is_correct'       => 'required|in:' . with(new VoteItem)->getValueLabelKeys(VoteItem::getVoteItemIsCorrectValueArray(false)),
            'ordering'         => 'required|integer',
        ];

        return $validationRulesArray;
    }
    /* CREATE TABLE "vote_items" ("id" integer not null primary key autoincrement,
            "vote_id" integer null,
         "name" varchar not null,
             "ordering" integer not null,
            "is_correct" tinyint(1) not null default '0',
    "image" varchar null,
    "created_at" datetime default CURRENT_TIMESTAMP not null, "updated_at" datetime null, foreign key("vote_id") references "votes"("id") on delete RESTRICT)
     */

    public static function bulkVoteItemsToElastic()
    {
        $elastic = app(\App\Elastic\Elastic::class);

        $elasticsearch_root_index  = config('app.elasticsearch_root_index');
        $elasticsearch_type        = with(new VoteItem)->getElasticsearchType();

        VoteItem::chunk(100, function ($VoteItems) use ($elastic, $elasticsearch_root_index, $elasticsearch_type) {
            foreach ($VoteItems as $nextVoteItem) {
                $elastic->index([
                    'index' => $elasticsearch_root_index,
                    'type'  => $elasticsearch_type,
                    'id'    => $nextVoteItem->id,
                    'body'  => [
                        'id'          => $nextVoteItem->id,
                        'name'        => $nextVoteItem->name,
                        'ordering'    => $nextVoteItem->ordering,
                    ]
                ]);
            }
        });
    }

}
