<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ExternalNewsImporting;

class ExternalNewsImportingWithInitData extends Seeder
{
    private $external_news_importings_tb;
    public function __construct()
    {
        $this->external_news_importings_tb= with(new ExternalNewsImporting())->getTable();
    }
    public function run()
    {
        \DB::table($this->external_news_importings_tb)->insert([
            'title'          => 'BBC Health',
            'url'            => 'http://feeds.bbci.co.uk/news/health/rss.xml',
            'status'         => true,
            'import_image'   => false,
        ]);


        \DB::table($this->external_news_importings_tb)->insert([
            'title'          => 'BBC Education',
            'url'            => 'http://feeds.bbci.co.uk/news/education/rss.xml',
            'status'         => false,
            'import_image'   => false,
        ]);

        \DB::table($this->external_news_importings_tb)->insert([
            'title'          => 'CNN Top Stories',
            'url'            => 'http://rss.cnn.com/rss/edition.rss',
            'status'         => true,
            'import_image'   => false,
        ]);

        \DB::table($this->external_news_importings_tb)->insert([
            'title'          => 'CNN World',
            'url'            => 'http://rss.cnn.com/rss/edition_world.rss',
            'status'         => true,
            'import_image'   => false,
        ]);

        \DB::table($this->external_news_importings_tb)->insert([
            'title'          => 'FOX News',
            'url'            => 'http://feeds.foxnews.com/foxnews/latest',
            'status'         => true,
            'import_image'   => false,
        ]);

    }
}
