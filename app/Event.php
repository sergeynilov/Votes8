<?php

namespace App;

use App\MyAppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Cviebrock\EloquentSluggable\Sluggable;

use DB;
use App\Http\Traits\funcsTrait;

class Event extends MyAppModel
{
    use funcsTrait;
    use Sluggable;

    protected $table      = 'events';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'event_name', 'slug', 'start_date', 'end_date', 'status', 'type', 'is_public', 'location', 'description',  'calendar_event_id', 'calendar_event_html_Link', 'creator_id' ];

    private static $eventReportTypeValueArray = Array( 'L' => 'Listing','C' => 'Calendar' );
    private static $eventStatusValueArray = Array( 'N' => 'New', 'M' => 'Modified', 'U' => 'Unmodified' );
    private static $eventTypeValueArray = Array( 'O' => 'Office', 'D' => 'Out of doors', 'C' => 'City', 'S' => 'Concert/Stadium' , 'R' => 'Restaurant/Cafe' );

    private static $eventIsPublicValueArray = [ true => 'Is public', false => 'Is not public' ];
    private static $eventPublishedLabelValueArray = Array('1' => 'Is Published', '0' => 'Is Not Published');


    private static $eventTypeColorValueArray = [ 'O' => '#33B679'/*olive*/, 'D' => '#0B8043'/*green*/, 'C' => '#E67C73' /*pink*/, 'S' => '#FF0000'/*red*/ , 'R' =>
        '#F4511E'/*orange*/ ];
    private static $eventTypeGoogleCalendarColorValueArray = [ 'O' => 2/*olive*/, 'D' => 10/*green*/, 'C' => 4 /*pink*/, 'S' => 11/*red*/ , 'R' => 6/*orange*/ ];

                             /*                     'colorId'     => 0,   // maroon
//                    'colorId'     => 11,   // red
//                    'colorId'     => 10,   // green
//                    'colorId'     => 9,   // blue
//                    'colorId'     => 8,   // dark gray
//                    'colorId'     => 7,   // blue
//                    'colorId'     => 6,   // orange
//                    'colorId'     => 5,   // unknown
//                    'colorId'     => 4,   // pink
//                    'colorId'     => 3,   // violat
//                    'colorId'     => 2,   // olive
//                    'colorId'     => 1,   // blue
 */

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'event_name'
            ]
        ];
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }


    public function scopeGetByCreatorId($query, $creator_id = null)
    {
        if (empty($creator_id)) {
            return $query;
        }
        return $query->where(with(new Event)->getTable() . '.creator_id', $creator_id);
    }

    public function scopeGetByCalendarEventId($query, $calendar_event_id = null)
    {
        if (empty($calendar_event_id)) {
            return $query;
        }
        return $query->where(with(new Event)->getTable() . '.calendar_event_id', $calendar_event_id);
    }

    public function scopeGetBySlug($query, $slug= null)
    {
        if (empty($slug)) return $query;
        return $query->where(with(new Event)->getTable().'.slug', $slug );
    }

    public function scopeGetByStatus($query, $status = null)
    {
        if ( !isset($status) or strlen($status) == 0 ) {
            return $query;
        }
        return $query->where(with(new Event)->getTable() . '.status', $status);
    }


    public function scopeGetByIsPublic($query, $is_public = null)
    {
        if ( !isset($is_public) or strlen($is_public) == 0 ) {
            return $query;
        }
        return $query->where(with(new Event)->getTable() . '.is_public', $is_public);
    }


    public function scopeGetByType($query, $type = null)
    {
        if ( !isset($type) or strlen($type) == 0 ) {
            return $query;
        }
        return $query->where(with(new Event)->getTable() . '.type', $type);
    }


    public function scopeGetByPublished($query, $published = null)
    {
        if (!isset($published) or strlen($published) == 0) {
            return $query;
        }
        return $query->where(with(new Event)->getTable().'.published', $published);
    }


    public function scopeGetByEventName($query, $event_name = null, $partial = false)
    {
        if ( ! isset($event_name)) {
            return $query;
        }
        return $query->where(with(new Event)->getTable().'.event_name', (! $partial ? '=' : 'like'), ($partial ? '%' : '') . $event_name . ($partial ? '%' : ''));
    }


    public function scopeGetByStartDate($query, $filter_start_date= null, string $sign= null)
    {
        if (!empty($filter_start_date)) {
            if (!empty($sign)) {
                $query->whereRaw( DB::getTablePrefix().with(new Event)->getTable().'.start_date ' . $sign . "'".$filter_start_date."' " );
            } else {
                $query->where(with(new Event)->getTable().'.start_date', $filter_start_date);
            }
        }
        return $query;
    }

    public function scopeGetByEndDate($query, $filter_end_date= null, string $sign= null)
    {
        if (!empty($filter_end_date)) {
            if (!empty($sign)) {
                $query->whereRaw( DB::getTablePrefix().with(new Event)->getTable().'.end_date ' . $sign . "'".$filter_end_date."' " );
            } else {
                $query->where(with(new Event)->getTable().'.end_date', $filter_end_date);
            }
        }
        return $query;
    }


/*  'start_date', 'end_date'
   public function scopeGetByCreatedAt($query, $filter_voted_at_from= null, string $sign= null)
    {
        if (!empty($filter_voted_at_from)) {
            if (!empty($sign)) {
                $query->whereRaw( DB::getTablePrefix().with(new VoteItemUsersResult)->getTable().'.created_at ' . $sign . "'".$filter_voted_at_from."' " );
            } else {
                $query->where(with(new VoteItemUsersResult)->getTable().'.created_at', $filter_voted_at_from);
            }
        }
        return $query;
    }

*/


    public static function getEventStatusValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$eventStatusValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getEventStatusLabel(string $status): string
    {
        if ( ! empty(self::$eventStatusValueArray[$status])) {
            return self::$eventStatusValueArray[$status];
        }
        return '';
    }



    public static function getEventReportTypeValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$eventReportTypeValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }
    public static function getEventReportTypeLabel(string $report_type): string
    {
        if ( ! empty(self::$eventReportTypeValueArray[$report_type])) {
            return self::$eventReportTypeValueArray[$report_type];
        }
        return '';
    }


    public static function getEventTypeValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$eventTypeValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getEventTypeLabel(string $type): string
    {
        if ( ! empty(self::$eventTypeValueArray[$type])) {
            return self::$eventTypeValueArray[$type];
        }
        return '';
    }





    public static function getEventIsPublicValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$eventIsPublicValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }
    public static function getEventIsPublicLabel(string $is_public): string
    {
        if ( ! empty(self::$eventIsPublicValueArray[$is_public])) {
            return self::$eventIsPublicValueArray[$is_public];
        }
        return '';
    }


    public static function getEventPublishedValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$eventPublishedLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getEventPublishedLabel(string $published): string
    {
        if ( ! empty(self::$eventPublishedLabelValueArray[$published])) {
            return self::$eventPublishedLabelValueArray[$published];
        }
        return self::$eventPublishedLabelValueArray[0];
    }


    public static function getEventTypeColorValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$eventTypeColorValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getEventTypeColorLabel(string $type_color): string
    {
        if ( ! empty(self::$eventTypeColorValueArray[$type_color])) {
            return self::$eventTypeColorValueArray[$type_color];
        }
        return '';
    }


    public static function getEventTypeGoogleCalendarColorValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$eventTypeGoogleCalendarColorValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getEventTypeGoogleCalendarColorLabel(string $type_color): string
    {
        if ( ! empty(self::$eventTypeGoogleCalendarColorValueArray[$type_color])) {
            return self::$eventTypeGoogleCalendarColorValueArray[$type_color];
        }
        return '';
    }

    /* check if provided event_name is unique for events.event_name field */
    public static function getSimilarEventByEventName( string $event_name, int $id= null, bool $return_count = false )
    {
        $quoteModel = Event::whereRaw( (new Event)->myStrLower('event_name', false, false) .' = '. (new Event)->myStrLower( (new Event)->mysqlEscape($event_name), true,false) );
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


    public static function getValidationRulesArray($event_id = null, array $options= []): array
    {
        $validationRulesArray = [
            'event_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new Event)->getTable())->ignore($event_id),
            ],

            'start_date'   => 'required',
            'end_date'     => 'required',
            'status'       => 'nullable|in:' . with(new Event)->getValueLabelKeys( Event::getEventStatusValueArray(false) ),
            'type'         => 'required|in:' . with(new Event)->getValueLabelKeys( Event::getEventTypeValueArray(false) ),
            'is_public'    => 'required|in:' . with(new Event)->getValueLabelKeys( Event::getEventIsPublicValueArray(false) ),
            'published'    => 'required|in:' . with(new Event)->getValueLabelKeys( Event::getEventPublishedValueArray(false) ),
            'location'     => 'nullable',
            'description'  => 'nullable',
            'creator_id'   => 'nullable|integer|exists:' . (with(new User)->getTable()) . ',id',
        ];

        return $validationRulesArray;
    }

}
