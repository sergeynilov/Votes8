<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;

class YoutubeAccessTokens extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'youtube_access_tokens';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'access_token' ];

    protected $casts = [
    ];


    protected static function boot() {
        parent::boot();
        static::deleting(function($youtubeAccessTokens) {
        });
    }

}
