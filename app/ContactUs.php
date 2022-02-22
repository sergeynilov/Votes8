<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;

class ContactUs extends MyAppModel
{
    use funcsTrait;

    protected $table      = 'contact_us';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable   = ['acceptor_id', 'author_name', 'author_email', 'message', 'accepted', 'accepted_at'];

    private static $contactUsAcceptedValueArray = Array('1' => 'Accepted', '0' => 'New');

    protected $casts = [
    ];


    public function acceptor()
    {
        return $this->belongsTo('App\User');
    }

    public static function getContactUsAcceptedValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$contactUsAcceptedValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }


    public static function getContactUsAcceptedLabel(string $accepted): string
    {
        if ( ! empty(self::$contactUsAcceptedValueArray[$accepted])) {
            return self::$contactUsAcceptedValueArray[$accepted];
        }
        return self::$contactUsAcceptedValueArray[0];
    }


    public function scopeGetByAcceptorId($query, $acceptor_id = null)
    {
        if (empty($acceptor_id)) {
            return $query;
        }
        return $query->where(with(new ContactUs)->getTable() . '.acceptor_id', $acceptor_id);
    }


    public function scopeGetByAccepted($query, $accepted = null)
    {
        if ( !isset($accepted) or strlen($accepted) == 0 ) {
            return $query;
        }
        return $query->where(with(new ContactUs)->getTable() . '.accepted', $accepted);
    }


    public function scopeGetByName($query, $name = null)
    {
        if ( ! isset($name)) {
            return $query;
        }
        return    $query->whereRaw(' ( ' . ContactUs::myStrLower('author_email', false, false) . ' like ' . ContactUs::myStrLower($name, true,
                true) . ' OR ' . ContactUs::myStrLower('author_name', false, false) . ' like ' . ContactUs::myStrLower($name, true, true) . ' ) ');
    }


    public static function getValidationRulesArray($options): array
    {
        $validationRulesArray = [
            'author_name'  => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'message'      => 'required',
            'acceptor_id'  => 'required|integer|exists:' . (with(new User)->getTable()) . ',id',
            'accepted'     => 'required|in:' . with(new ContactUs)->getValueLabelKeys(ContactUs::getContactUsAcceptedValueArray(false)),
            'captcha'      => 'required|captcha'
        ];
        if (in_array('skip_acceptor_id', $options)) {
            unset($validationRulesArray['acceptor_id']);
        }
        if (in_array('skip_accepted', $options)) {
            unset($validationRulesArray['accepted']);
        }

        return $validationRulesArray;
    }

    public static function addDummyData()
    {
        $faker                = \Faker\Factory::create();
        $max_contact_us_count = 10;

        for ($i = 0; $i < $max_contact_us_count; $i++) {
            $random_index = mt_rand(1, $max_contact_us_count);

            $acceptor_id = null;
            $accepted    = false;
            $accepted_at = null;
            $datetime    = $faker->dateTimeThisMonth()->format('Y-m-d h:m:s');
            if ($random_index <= 5) {
                $acceptor_id = $random_index;
                $accepted    = true;
                $accepted_at = $datetime;
            }

            $sex   = rand(1, 2);
            if ($sex == 1) {
                $first_name = $faker->firstNameMale;

                $title_rand = rand(1, 4);
                $title      = ($title_rand == 1) ? $faker->titleMale . ' ' : '';
            } else {
                $first_name = $faker->firstNameFemale;

                $title_rand = rand(1, 4);
                $title      = ($title_rand == 1) ? $faker->titleFemale . ' ' : '';
            }

            $last_name   = $faker->lastName;
            $faker_name  = $first_name . ' ' . $last_name;
            $faker_username= strtolower( $first_name . ' ' . $last_name );
            $faker_email= $faker_username.'@votes_site.com';
            $faker_website= 'votes_site_'.$faker_username.'.com';
            $arr         = with(new ContactUs)->pregSplit("/@/", $faker_email);
            if (count($arr) == 2) {
                $faker_email = strtolower($first_name . '.' . $last_name) . '@' . $arr[1];
            }
            $faker_message = $faker->paragraphs(3, true);

             DB::table('contact_us')->insert([
                'acceptor_id'  => $acceptor_id,
                'author_name'  => $title.$faker_name,
                'author_email' => $faker_email,
                'message'      => $faker_message,

                'accepted'    => $accepted,
                'accepted_at' => $accepted_at,
                'created_at'  => $datetime,
            ]);
            $new_contact_us_id= DB::getPdo()->lastInsertId();
//            echo '<pre>$new_contact_us_id::'.print_r($new_contact_us_id,true).'</pre>';

            DB::table('users')->insert([
                'username'   => $faker_username,
                'email'      => $faker_email,
                'status'     => $accepted ? 'A' : 'N',
                'verified'   => $accepted ? 1 : 0,
                'password'   => bcrypt('111111'),
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'phone'      => $faker->phoneNumber,
                'website'    => $faker_website,
            ]);
            $new_user_id= DB::getPdo()->lastInsertId();


            DB::table('users_groups')->insert([
                'user_id'=> $new_user_id,
                'group_id'=> USER_ACCESS_USER
            ]);



            if (!$accepted) {   // if this contact_us has not accepted status then we need to add 1 row in cron_notifications
                DB::table('cron_notifications')->insert([
                    'cron_type'  => CronNotification::NEW_CONTACT_US,
                    'cron_object_id'  => $new_contact_us_id,
                    'created_at'  => $datetime,
                ]);
                DB::table('cron_notifications')->insert([
                    'cron_type'       => CronNotification::NEW_USER,
                    'cron_object_id'  => $new_user_id,
                    'created_at'      => $datetime,
                ]);
            }
        } // for($i= 0; $i< $max_contact_us_count; $i++) {
    }


}
