<?php

/* If you copied the content in your app\Http\Traits.. then thats the namespace that should be set in your trait:

namespace App\Http\Traits;

trait HasSlug...
for the Str issue try \Illuminate\Support\Str. */

//namespace Spatie\HasSlug;
//namespace Spatie\Tags;
namespace App\Http\Traits;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            collect($model->getTranslatedLocales('name'))
                ->each(fn (string $locale) => $model->setTranslation(
                    'slug',
                    $locale,
                    $model->generateSlug($locale)
                ));
        });
    }

    protected function generateSlug(string $locale): string
    {
        $slugger = config('tags.slugger');

//        $slugger ??= '\Illuminate\Support\Str::slug';
//        $slugger = $slugger ?:  \Illuminate\Support\Str::class;
        $slugger = $slugger ?:  \Illuminate\Support\Str;
        return call_user_func($slugger, $this->getTranslation('name', $locale));
    }
}
