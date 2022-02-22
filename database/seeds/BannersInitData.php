<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Banner;

class BannersInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banner::create([
            'id'               => 1,
            'text'             => 'PHP site',
            'logo'             => 'php-logo.png',
            'short_descr'      => 'PHP is a popular scripting language',
            'url'              => 'http://php.net/',
            'active'           => true,
            'ordering'         => 1,
            'view_type'        => 2,
        ]);

        Banner::create([
            'id'               => 2,
            'text'             => 'Mysql site',
            'logo'             => 'mysql-logo.jpg',
            'short_descr'      => 'The popular open source database',
            'url'              => 'https://www.mysql.com/',
            'active'           => true,
            'ordering'         => 2,
            'view_type'        => 1,
        ]);

        Banner::create([
            'id'               => 3,
            'text'             => 'Vuejs site',
            'logo'             => 'vuejs-logo.png',
            'short_descr'      => 'Vue is a progressive framework',
            'url'              => 'https://vuejs.org/',
            'active'           => true,
            'ordering'         => 3,
            'view_type'        => 2,
        ]);

        Banner::create([
            'id'               => 4,
            'text'             => 'jquery',
            'logo'             => 'jquery-logo.jpg',
            'short_descr'      => 'jQuery is a fast, JavaScript library',
            'url'              => 'https://jquery.com/',
            'active'           => true,
            'ordering'         => 4,
            'view_type'        => 3,
        ]);

    }
}
