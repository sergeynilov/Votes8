<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\EventAttendee;
use App\Event;
use App\User;

class eventAttendeesWithInitData extends Seeder
{
    private $events_tb;
    private $users_tb;
    private $event_attendees_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->events_tb= with(new Event())->getTable();
        $this->event_attendees_tb= with(new EventAttendee())->getTable();
    }
    public function run()
    {

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 1,
            'attendee_user_id'      => 2,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 1,
            'attendee_user_id'      => null,
            'attendee_user_email'   => 'TomBanks@demo.votes.email.com',
            'attendee_user_display_name'   => 'Tom Banks',
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 1,
            'attendee_user_id'      => 3,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 2,
            'attendee_user_id'      => null,
            'attendee_user_email'   => 'BandLittle@demo.votes.email.com',
            'attendee_user_display_name'   => 'Band Little',
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 2,
            'attendee_user_id'      => 3,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 3,
            'attendee_user_id'      => null,
            'attendee_user_email'   => 'sam-jilroy@demo.votes.email.com',
            'attendee_user_display_name'   => 'Sam Jilroy',
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 3,
            'attendee_user_id'      => 1,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 3,
            'attendee_user_id'      => null,
            'attendee_user_email'   => 'Bill.Norton@demo.votes.email.com',
            'attendee_user_display_name'   => 'Bill Norton',
        ]);


        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 4,
            'attendee_user_id'      => 1,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 4,
            'attendee_user_id'      => 5,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 6,
            'attendee_user_id'      => 4,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);
        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 6,
            'attendee_user_id'      => 5,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);
        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 6,
            'attendee_user_id'      => null,
            'attendee_user_email'   => 'Hall.Hanton@demo.votes.email.com',
            'attendee_user_display_name'   => 'Hall Hanton',
        ]);



        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 7,
            'attendee_user_id'      => 1,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 7,
            'attendee_user_id'      => 2,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 7,
            'attendee_user_id'      => 3,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 7,
            'attendee_user_id'      => 4,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 7,
            'attendee_user_id'      => 5,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);


        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 8,
            'attendee_user_id'      => 1,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 8,
            'attendee_user_id'      => 2,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 8,
            'attendee_user_id'      => 3,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 8,
            'attendee_user_id'      => 4,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);

        \DB::table($this->event_attendees_tb)->insert([
            'event_id'              => 8,
            'attendee_user_id'      => 5,
            'attendee_user_email'   => null,
            'attendee_user_display_name'   => null,
        ]);


    }
}
