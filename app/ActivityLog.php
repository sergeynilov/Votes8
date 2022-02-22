<?php

namespace App;
use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use App\VoteItem;
use App\Vote;
use App\User;


class ActivityLog extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'activity_log';
    protected $primaryKey = 'id';
    public $timestamps    = false;
    const CAUSER_TYPE_VOTE_SELECTED     = 'vote_selected';
    const CAUSER_TYPE_SET_QUIZ_QUALITY  = 'set_quiz_quality';
    const CAUSER_TYPE_FAILED_LOGIN      = 'failed_login';
    const CAUSER_TYPE_SUCCESSFUL_LOGIN  = 'successful_login';
    const CAUSER_TYPE_USER_REGISTRATION = 'successful_user_registration';
    const CAUSER_TYPE_USER_ACTIVATION   = 'successful_user_activation';
    const CAUSER_TYPE_CONTACT_US_SENT   = 'successful_user_contact_us_sent';
    const CAUSER_TYPE_PASSWORD_RESET    = 'password_reset';
    const CAUSER_TYPE_NO_FEED_DEFAULT_USER    = 'failer_no_creator_user_on_feed_reading';
    const CAUSER_TYPE_SUCCESS_FEED_IMPORTING    = 'successful_feed_importing';

    protected $fillable = [ 'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'properties' ];


}
