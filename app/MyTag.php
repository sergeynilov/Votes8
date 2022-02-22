<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\MyAppModel;
use App\TagDetail;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;


class MyTag extends MyAppModel
{
    use funcsTrait;

    protected $table        = 'tags';
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    private $votes_tag_type = 'votesTagType';

    public function getVotesTagType() : string
    {
        return $this->votes_tag_type;
    }

    public function tagDetail()
    {
        return $this->hasOne('App\TagDetail', 'tag_id', 'id');
//        return $this->hasOne('App\TagDetail', 'foreign_key', 'local_key' );
        //return $this->hasOne('App\Phone', 'foreign_key', 'local_key');

    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($tag) {
            $relatedTagDetail= $tag->tagDetail;
            /*Try replacing $relatedTagDetail= $tag->tagDetail(); with $relatedTagDetail= $tag->tagDetail;. – Jonas Staudenmeir 1 hour ago */
            if ( !empty($relatedTagDetail) ) {
                $relatedTagDetail->delete();
            }
        });
    }


    public function scopeContainingName($query, string $name= null, $locale = null)
    {
        if (empty($name)) return $query;
        $locale = $locale ?? app()->getLocale();
        return $query->whereRaw('LOWER(JSON_EXTRACT(name, "$.' . $locale .'")) like ?', ['"%' . strtolower($name) . '%"']);
    }

    public function scopeContainingSlug($query, string $slug= null, $locale = null)
    {
        if (empty($slug)) return $query;
        $locale = $locale ?? app()->getLocale();
        return $query->whereRaw('LOWER(JSON_EXTRACT(slug, "$.' . $locale .'")) like ?', ['"%' . strtolower($slug) . '%"']);
    }


    public function scopeGetBySlug($query, $slug= null)
    {
        if (empty($slug)) return $query;
        return $query->where(with(new Tag)->getTable().'.slug', $slug );
    }


    /* check if provided name is unique for tags.name field */
    public static function getSimilarTagByName( /*string */$name, int $id= null, bool $return_count = false )
    {
        $quoteModel = MyTag::where( 'name', $name );
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

    public static function getValidationRulesArray($tag_id= null) : array
    {
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:255',
//                Rule::unique(with(new Tag)->getTable())->ignore($tag_id),
            ],
            'description'      => 'required',
            'order_column'     => 'nullable|integer',
        ];

        /* $request->validate([
    'name' => ['required', 'string', new Uppercase],
]); */

        /*
        $request->validate([
        'name' => ['required', 'string', new Uppercase],
        ]); */
        return $validationRulesArray;
    }

//    /* get additional properties of cms_item image : path, url, size etc... */
//    public function getVoteImagePropsAttribute(): array
//    {
//        return $this->tagImagePropsArray;
//    }
//
//    /* set additional properties of cms_item image : path, url, size etc... */
//    public function setVoteImagePropsAttribute(array $tagImagePropsArray)
//    {
//        $this->tagImagePropsArray = $tagImagePropsArray;
//    }

}