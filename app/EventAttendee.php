<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;

class EventAttendee extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'event_attendees';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'event_id', 'attendee_user_id', 'attendee_user_email', 'attendee_user_display_name' ];

    protected $casts = [
    ];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function scopeGetByEvent($query, $event_id= null)
    {
        if (!empty($event_id)) {
            if ( is_array($event_id) ) {
                $query->whereIn(with(new EventAttendee)->getTable().'.event_id', $event_id);
            } else {
                $query->where(with(new EventAttendee)->getTable().'.event_id', $event_id);
            }
        }
        return $query;
    }


    public function scopeGetByAttendeeUserId($query, $attendee_user_id= null)
    {
        if (!empty($attendee_user_id)) {
            if ( is_array($attendee_user_id) ) {
                $query->whereIn(with(new EventAttendee)->getTable().'.attendee_user_id', $attendee_user_id);
            } else {
                $query->where(with(new EventAttendee)->getTable().'.attendee_user_id', $attendee_user_id);
            }
        }
        return $query;
    }


    protected static function boot() {
        parent::boot();
        static::deleting(function($eventAttendee) {
        });
    }



    //         return EventAttendee::getValidationRulesArray( $request->get('event_id'), $request->get('id') );
    public static function getValidationRulesArray($event_id, $event_attendee_id = null): array
    {
//        $additional_item_value_validation_rule= 'check_event_attendee_unique_by_name:'.$event_id.','.( !empty($event_attendee_id)?$event_attendee_id:'');
        /*         Schema::create('event_attendees', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->integer('event_id')->unsigned();
                    $table->foreign('event_id')->references('id')->on('events')->onDelete('RESTRICT');

                    $table->integer('attendee_user_id')->unsigned();
                    $table->foreign('attendee_user_id')->references('id')->on('users')->onDelete('RESTRICT');

                    $table->string('email',100)->nullable();

                    $table->timestamp('created_at')->useCurrent();
                });
                Artisan::call('db:seed', array('--class' => 'eventAttendeesWithInitData'));
         */


        $validationRulesArray            = [
//            'event_id'      => 'required|exists:'.( with(new Event)->getTable() ).',id',
//            'name'             => 'required|max:255|' . $additional_item_value_validation_rule,
//            'ordering'         => 'required|integer',
        ];

        return $validationRulesArray;
    }

}
