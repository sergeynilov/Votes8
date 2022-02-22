<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\PageContentImage;

class PageContentImagesWithInitData extends Seeder
{
    private $page_content_images_tb;
    public function __construct()
    {
        $this->page_content_images_tb= with(new PageContentImage())->getTable();
    }
    public function run()
    {

        //        INSERT INTO `Votes`.`page_content_images` (`page_content_id`, `filename`, `info`) VALUES (1, 'your_vote.jpg', 'Site slogan image');

        \DB::table($this->page_content_images_tb)->insert([
            'id'                    => 1,
            'page_content_id'       => 1, // About
            'filename'              => 'your_vote.jpg',
            'is_main'               => true,
            'is_video'              => false,
            'video_width'           => null,
            'video_height'          => null,
            'info'                  => 'Site slogan image',
        ]);

        \DB::table($this->page_content_images_tb)->insert([
            'id'                    => 2,
            'page_content_id'       => 1, // About
            'filename'              => 'our_boss.jpg',
            'is_main'               => false,
            'is_video'              => false,
            'video_width'           => null,
            'video_height'          => null,
            'info'                  => 'Our boss photo',
        ]);

        \DB::table($this->page_content_images_tb)->insert([
            'id'                    => 3,
            'page_content_id'       => 1, // About
            'filename'              => 'our_main_manager.jpg',
            'is_main'               => false,
            'is_video'              => false,
            'video_width'           => null,
            'video_height'          => null,
            'info'                  => 'Our main manager',
        ]);

        \DB::table($this->page_content_images_tb)->insert([
            'id'                    => 4,
            'page_content_id'       => 1, // About
            'filename'              => 'video_demo.mp4',
            'is_main'               => false,
            'is_video'              => true,
            'video_width'           => 720,
            'video_height'          => 404,
            'info'                  => 'video demo Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua.',
        ]);

        \DB::table($this->page_content_images_tb)->insert([
            'id'                    => 5,
            'page_content_id'       => 2, // Contact Us
            'filename'              => 'office_building.jpeg',
            'is_main'               => true,
            'is_video'              => false,
            'video_width'           => null,
            'video_height'          => null,
            'info'                  => 'Office building',
        ]);

        \DB::table($this->page_content_images_tb)->insert([
            'id'                    => 6,
            'page_content_id'       => 2, // Contact Us
            'filename'              => 'video.mp4',
            'is_main'               => false,
            'is_video'              => true,
            'video_width'           => 200,
            'video_height'          => 110,
            'info'                  => 'Our Office video Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua',
        ]);

/*        \DB::table($this->page_content_images_tb)->insert([
            'id'                    => 7,
            'page_content_id'       => 11, // Terms of service
            'filename'              => 'terms_of_service_demo_video.mp4',
            'is_main'               => false,
            'is_video'              => true,
            'video_width'           => 720,
            'video_height'          => 404,
            'info'                  => 'Terms of service video Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua',
        ]);

        \DB::table($this->page_content_images_tb)->insert([
            'id'                    => 8,
            'page_content_id'       => 11, // Terms of service
            'filename'              => 'sample.flv',
            'is_main'               => false,
            'is_video'              => true,
            'video_width'           => 720,
            'video_height'          => 480,
            'info'                  => 'Terms of service video 2 Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua',
        ]);*/

    }
}
