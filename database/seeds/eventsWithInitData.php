<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Event;
use App\User;


class eventsWithInitData extends Seeder
{
    private $events_tb;
    private $users_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->events_tb= with(new Event())->getTable();
    }
    public function run()
    {

        \DB::table($this->events_tb)->insert([
            'event_name'          => 'Ktulu selebration',
            'slug'                => 'ktulu-selebration',
            'published'           => true,
            'start_date'          => '2019-08-20 14:30',
            'end_date'            => '2019-08-20 16:40',
            'type'                => 'S',
            'is_public'           => true,
            'location'            => 'City Central Stadium',
            'latitude'            => null,
            'longitude'           => null,
            'description'         => '<p><strong>Ktulu selebration</strong> description</p> <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>',
            'status'              => 'U',
            'calendar_event_id'   => 'eb0k69svljgsnbk5gd45qmhhu4',
            'calendar_event_html_Link'=>'https://www.google.com/calendar/event?eid=ZWIwazY5c3ZsamdzbmJrNWdkNDVxbWhodTQgc29mdHJlYWN0b3IuY29tX2xtMzE4bDc3ZmEzOW1vZDVwZ2ZqNThlbGEwQGc',
            'creator_id'=>3
        ]);

        \DB::table($this->events_tb)->insert([
            'event_name'          => 'Big Orange Festival',
            'slug'                => 'big-orange-festival',
            'published'           => true,
            'start_date'          => '2019-08-21 10:00',
            'end_date'            => '2019-08-25 11:30',
            'type'                => 'D',
            'is_public'           => true,
            'location'            => 'Suburb of the city, near with forest',
            'latitude'            => null,
            'longitude'           => null,
            'description'         => '<p><strong>Big Orange Festival</strong> description</p> <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>',
            'status'              => 'U',
            'calendar_event_id'   => 'rln76f7u4ctsvlmvt0kog8ma04',
            'calendar_event_html_Link'=>'https://www.google.com/calendar/event?eid=cmxuNzZmN3U0Y3RzdmxtdnQwa29nOG1hMDQgc29mdHJlYWN0b3IuY29tX2xtMzE4bDc3ZmEzOW1vZDVwZ2ZqNThlbGEwQGc',
            'creator_id'=>1
        ]);

        \DB::table($this->events_tb)->insert([
            'event_name'          => 'Small Clear Water Festival',
            'slug'                => 'small-clear-water-festival',
            'published'           => true,
            'start_date'          => '2019-08-20 6:20',
            'end_date'            => '2019-08-23 22:00',
            'type'                => 'C',
            'is_public'           => true,
            'location'            => 'Center of the city',//
            'latitude'            => 51.507818,
            'longitude'           => -0.126786,
            'description'         => '<p><strong>Small Clear Water Festival</strong> description</p> <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>',
            'creator_id'=>2
        ]);

        \DB::table($this->events_tb)->insert([
            'event_name'          => 'Big Boss Birthday',
            'slug'                => 'big-boss-birthday',
            'published'           => false,
            'start_date'          => '2019-08-22 18:10',
            'end_date'            => '2019-08-22 21:30',
            'type'                => 'O',
            'is_public'           => false,
            'location'            => 'At main Office',
            'latitude'            => null,
            'longitude'           => null,
            'description'         => '<p><strong>Big Boss Birthday</strong> description</p> <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>',
            'creator_id'=>1
        ]);

        \DB::table($this->events_tb)->insert([   // id = 5
            'event_name'          => 'Green Grass out of doors meeting',
            'slug'                => 'green-grass-out-of-doors-meeting',
            'published'           => true,
            'start_date'          => '2019-08-21 16:30',
            'end_date'            => '2019-08-21 19:50',
            'type'                => 'D',
            'is_public'           => true,
            'location'            => 'Suburb of the city, near with forest',
            'latitude'            => null,
            'longitude'           => null,
            'description'         => '<p><strong>Green Grass out of doors meeting</strong> description</p> <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>',
            'creator_id'=>1
        ]);


        \DB::table($this->events_tb)->insert([   // id = 6
            'event_name'          => 'Rest on the lake',
            'slug'                => 'rest-on-the-lake',
            'published'           => true,
            'start_date'          => '2019-08-15 18:00',
            'end_date'            => '2019-08-18 21:50',
            'type'                => 'D',
            'is_public'           => true,
            'location'            => 'London, The Serpentine lake', //'Suburb of the city, at lake',
            'latitude'            => 51.652277,
            'longitude'           => -0.327700,
            'description'         => '<p><strong>Rest on the lake</strong> description</p> <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>',
            'creator_id'=>3
        ]);

        \DB::table($this->events_tb)->insert([   // id = 7
            'event_name'          => 'Corporate party',
            'slug'                => 'corporate-party',
            'published'           => true,
            'start_date'          => '2019-08-13 18:00',
            'end_date'            => '2019-08-14 22:00',
            'type'                => 'O',
            'is_public'           => false,
            'location'            => 'Central Office',
            'latitude'            => null,
            'longitude'           => null,
            'description'         => '<p><strong>Corporate party</strong> description</p> <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>',
            'creator_id'=>2
        ]);


        \DB::table($this->events_tb)->insert([   // id = 8
            'event_name'          => 'PHP developer\'s conference',
            'slug'                => 'php-developers-conference',
            'published'           => true,
            'start_date'          => '2019-08-10 18:00',
            'end_date'            => '2019-08-12 22:00',
            'type'                => 'O',
            'is_public'           => true,
            'location'            => 'Central Office',
            'latitude'            => null,
            'longitude'           => null,
            'description'         => '<p><strong>PHP developer\'s conference</strong> description</p> <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>
<p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. <i>Excepteur sint  occaecat</i> cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
</p>',
            'creator_id'=>2
        ]);

    }
}
