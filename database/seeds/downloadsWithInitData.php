<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Download;
use App\User;

class downloadsWithInitData extends Seeder
{
    private $users_tb;
    private $downloads_tb;

    public function __construct()
    {
        $this->users_tb     = with(new User)->getTable();
        $this->downloads_tb = with(new Download)->getTable();
    }

    public function run()
    {

        \DB::table($this->downloads_tb)->insert([
            'id'            => 1,
            'title'         => 'Our Services',
            'file'          => 'our-services.doc',
            'active'        => true,
            'description'   => '<b>Our Services</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua 
 <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>',
            'creator_id'    => 3,
            'preview_image' => 'preview_image_our-services.jpg',
            'price'         => 1.2,
            'price_info'    => 'Price info for Our Services Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam',
            'created_at'    => now(),
        ]);

        \DB::table($this->downloads_tb)->insert([
            'id'            => 2,
            'title'         => 'Our prices',
            'file'          => 'our_prices.ods',
            'active'        => true,
            'description'   => '<b>Our prices</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua 
 <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>',
            'creator_id'    => 5,
            'preview_image' => 'preview_image_our_prices.jpg',
            'price'         => 1,
            'price_info'    => 'Price info for Our prices Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam',
            'created_at'    => now(),
        ]);


        \DB::table($this->downloads_tb)->insert([
            'id'            => 3,
            'title'         => 'Rules of our site',
            'file'          => 'rules-of-our-site.pdf',
            'active'        => false,
            'description'   => '<b>Rules of our site</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua 
 <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
 ',
            'creator_id'    => 2,
            'preview_image' => null,
            'price'         => 0.8,
            'price_info'    => 'Price info for rules-of our site Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam',
            'created_at'    => now(),
        ]);


        \DB::table($this->downloads_tb)->insert([
            'id'            => 4,
            'title'         => 'Slogan',
            'file'          => 'slogan_1.jpg',
            'active'        => true,
            'description'   => '<b>Slogan</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua 
 <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
 ',
            'creator_id'    => 2,
            'preview_image' => null,
            'price'         => 1.5,
            'price_info'    => 'Price info for Slogan Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam',
            'created_at'    => now(),
        ]);


        \DB::table($this->downloads_tb)->insert([
            'id'            => 5,
            'title'         => 'Terms',
            'file'          => 'terms.doc',
            'active'        => true,
            'description'   => '<b>Terms</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua 
 <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
 ',
            'creator_id'    => 3,
            'preview_image' => 'preview_image_terms.jpg',
            'price'         => 1.1,
            'price_info'    => 'Price info for Terms Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam',
            'created_at'    => now(),
        ]);

    }
}
