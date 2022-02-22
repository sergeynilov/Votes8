<?php

namespace App;

use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Group;
use App\UserGroup;
use App\ChatMessage;
use App\SearchResult;
use App\ChatParticipant;
use App\Chat;
use App\Vote;
use App\PageContent;
use App\Http\Traits\funcsTrait;
use Laravel\Cashier\Billable;

const CUSTOMER= 'customer';
const SELLER= 'seller';

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable;
    use Notifiable;
    use funcsTrait;
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $userAvatarPropsArray = [];
    protected $avatar_filename_max_length = 255;
    protected $full_photo_filename_max_length = 255;
    protected static $password_length = 6;
    
    protected $fillable = [ 'username', 'email', 'status', 'provider_name', 'provider_id', 'first_name', 'last_name', 'phone', 'website', 'password', 'activated_at', 'avatar', 'full_photo', 'updated_at', 'verified' ];

    protected $hidden = [ 'password', 'remember_token'  ];
    protected static $uploads_user_avatar_temps_dir = 'tmp/-user-avatar-temp-';
    protected static $uploads_user_avatars_dir = 'user-avatars/-user-avatar-';

    protected static $uploads_user_full_photo_temps_dir = 'tmp/-user-full_photo-temp-';
    protected static $uploads_user_full_photos_dir = 'user-full-photos/-user-full-photo-';

    private static $userStatusLabelValueArray = Array('A' => 'Active', 'I' => 'Inactive', 'N' => 'New');
    private static $userVerifiedLabelValueArray = Array('1' => 'Is Verified', '0' => 'Is Not Verified');
    private static $userSexLabelValueArray = [ 'M' => 'Male', 'F'=>'Female' ];

    protected $casts = [
    ];

    public function getFullNameAttribute()
    {
        return $this->makeClearDoubledSpaces( trim("{$this->first_name} {$this->last_name}") );
    }

    public static function getUserStatusValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$userStatusLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }

    public static function getUserStatusLabel(string $status): string
    {
        if ( ! empty(self::$userStatusLabelValueArray[$status])) {
            return self::$userStatusLabelValueArray[$status];
        }

        return self::$userStatusLabelValueArray[0];
    }



    public static function getUserVerifiedValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$userVerifiedLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getUserVerifiedLabel(string $verified): string
    {
        if ( isset(self::$userVerifiedLabelValueArray[$verified])) {
            return self::$userVerifiedLabelValueArray[$verified];
        }
        return self::$userVerifiedLabelValueArray[0];
    }


    public static function getUserSexValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$userSexLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getUserSexLabel($sex)
    {
        if ( !empty(self::$userSexLabelValueArray[$sex])) {
            return self::$userSexLabelValueArray[$sex];
        }
        return '';
    }


    public function payments()
    {
        return $this->hasMany('App\Payment', 'userr_id');
    }

    public function contactUs()
    {
        return $this->hasMany('App\ContactUs', 'acceptor_id');
    }




    public function quizQualityResults()
    {
        return $this->hasMany('App\QuizQualityResult');
    }

    public function userGroups()
    {
        return $this->hasMany('App\UserGroup');
    }

    public function searchResults()
    {
        return $this->hasMany('App\SearchResult');
    }


    public function chatParticipants()
    {
        return $this->hasMany('App\ChatParticipant');
    }

    public function chatMessages()
    {
        return $this->hasMany('App\ChatMessage');
    }

    public function usersSiteSubscriptions()
    {
        return $this->hasMany('App\UsersSiteSubscription');
    }

    public function voteItemUsersResults()
    {
        return $this->hasMany('App\VoteItemUsersResult');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote', 'creator_id', 'id');
    }

    public function chats()
    {
        return $this->hasMany('App\Chat', 'creator_id', 'id');
    }

    public function pageContents()
    {
        return $this->hasMany('App\PageContent', 'creator_id', 'id');
    }


    public function getAvatarFilenameMaxLength(): int
    {
        return $this->avatar_filename_max_length;
    }

    public function getFullPhotoFilenameMaxLength(): int
    {
        return $this->full_photo_filename_max_length;
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($user) {
            foreach ( $user->votes()->get() as $nextVote ) {
                $nextVote->delete();
            }

            foreach ( $user->pageContents()->get() as $nextPageContent ) {
                $nextPageContent->delete();
            }

            foreach ( $user->contactUs()->get() as $nextContactUs ) {
                $nextContactUs->delete();
            }

            foreach ( $user->quizQualityResults()->get() as $nextQuizQualityResult ) {
                $nextQuizQualityResult->delete();
            }

            foreach ( $user->usersSiteSubscriptions()->get() as $nextUsersSiteSubscription ) {
                $nextUsersSiteSubscription->delete();
            }

            foreach ( $user->voteItemUsersResults()->get() as $nextVoteItemUsersResult ) {
                $nextVoteItemUsersResult->delete();
            }

            foreach ( $user->chatMessages()->get() as $nextChatMessage ) {
                $nextChatMessage->delete();
            }

            foreach ( $user->chats()->get() as $nextChat ) {
                $nextChat->delete();
            }
            foreach ( $user->userGroups()->get() as $nextUserGroup ) {
                $nextUserGroup->delete();
            }

            foreach ( $user->searchResults()->get() as $nextSearchResult ) {
                $nextSearchResult->delete();
            }


            foreach ( $user->chatParticipants()->get() as $nextChatParticipant ) {
                $nextChatParticipant->delete();
            }
        });
    }

    
    public static function scopeGetByUserName($query, $username = null, $partial = false)
    {
        if (empty($username)) {
            return $query;
        }
        return $query->where(with(new User)->getTable().'.username', (! $partial ? '=' : 'like'), ($partial ? '%' : '') . $username . ($partial ? '%' : ''));
    }

    public function scopeGetByStatus($query, $status = null)
    {
        if (empty($status)) {
            return $query;
        }
        return $query->where(with(new User)->getTable().'.status', $status);
    }


    public static function getUsersGroupsByUserId(int $user_id, $show_group_title= false)
    {
        $groups_tb= with(new Group)->getTable();
        $users_groups_tb= with(new UserGroup)->getTable();
        $usersGroupsList= UserGroup::where( 'user_id', $user_id )
            ->leftJoin($groups_tb, $groups_tb.'.id', '=', $users_groups_tb.'.group_id')
            ->select( $users_groups_tb.".*", $groups_tb.".name as group_name" )
            ->get();
        if ( !$show_group_title ) return $usersGroupsList;
        $ret= '';
        foreach( $usersGroupsList as $nextUsersGroup ) {
            $ret.= $nextUsersGroup->group_name.', ';
        }
        $ret= with(new User)->trimRightSubString($ret, ', ');
        return $ret;
    }


    public static function getUserAvatarDir(int $user_avatar_id): string
    {
        return self::$uploads_user_avatars_dir . $user_avatar_id . '/';
    }


    public static function getUserAvatarPath(int $user_avatar_id, $avatar): string
    {
        if (empty($avatar)) {
            return '';
        }
        return self::$uploads_user_avatars_dir . $user_avatar_id . '/' . $avatar;
    }




    public static function setUserAvatarProps(int $user_id, string $photo = null, bool $skip_non_existing_file = false): array
    {
//        echo '<pre>$user_id::'.print_r($user_id,true).'</pre>';
//        echo '<pre>setUserImageProps  $photo::'.print_r($photo,true).'</pre>';
//        echo '<pre>$skip_non_existing_file::'.print_r($skip_non_existing_file,true).'</pre>';
        if (empty($photo) and $skip_non_existing_file) {
            return [];
        }


        $dir_path = self::$uploads_user_avatars_dir . '' . $user_id . '';
        $file_full_path = /*'/app/'.*/$dir_path . '/' . $photo;
//        echo '<pre>!!!!$file_full_path::'.print_r($file_full_path,true).'</pre>';
        $file_exists    = ( !empty($photo) and Storage::disk('local')->exists( 'public/'.$file_full_path) );

//        echo '<pre>$file_exists::'.print_r($file_exists,true).'</pre>';
        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = config('app.empty_img_url');       //file:///_wwwroot/lar/votes/public/photos/emptyImg.png
            echo '<pre>++ $file_full_path::'.print_r($file_full_path,true).'</pre>';
            $photo          = with(new User)->getFilenameBasename($file_full_path);
        }

        $photo_path = $file_full_path;
        if ($file_exists) {
            $photoPropsArray = ['photo' => $photo, 'photo_path' => $photo_path, 'photo_url' => Storage::url( $file_full_path ) ];
            $photo_full_path = ( $photo_path);
//            echo '<pre>-1 $photo_full_path::'.print_r($photo_full_path,true).'</pre>';
//            if ( ! empty($previewSizeArray['width'])) {
//                $photoPropsArray['preview_width']  = $previewSizeArray['width'];
//                $photoPropsArray['preview_height'] = $previewSizeArray['height'];
//            }
            $userImgProps = with(new User)->getCFImageProps(base_path() . '/storage/app/public/'.$photo_full_path, $photoPropsArray);
        } else {
            $userImgProps = ['photo' => $photo, 'photo_path' => $photo_path, 'photo_url' => ($file_full_path) ];
//            $photo_full_path = base_path() . '/public' . $photo_path;
//            echo '<pre>-1111 $photo_full_path::'.print_r($photo_full_path,true).'</pre>';
        }


//        echo '<pre>$userImgProps::'.print_r($userImgProps,true).'</pre>';
        return $userImgProps;

    }

//    public static function setUserFullPhotoProps_OLD(int $user_id, string $avatar = null, bool $skip_non_existing_file = false): array
//    {
//        if (empty($avatar) and $skip_non_existing_file) {
//            return [];
//        }
//
//        $dir_path = self::$uploads_user_avatars_dir . '' . $user_id . '';
//
//        $file_full_path = $dir_path . '/' . $avatar;
//        $file_exists    = ( !empty($avatar) and Storage::disk('local')->exists('public/' . $file_full_path) );
//
//        if ( ! $file_exists) {
//            if ($skip_non_existing_file) {
//                return [];
//            }
//            $file_full_path = /*'public' . */
//                config('app.empty_img_url');
//            $avatar          = with(new MyAppModel())->getFilenameBasename($file_full_path);
//        }
//
//        $avatar_path = $file_full_path;
//        //        $avatar_path= public_path('storage/'.$dir_path . '/' . $avatar);
//        //        $avatar_path= public_path('storage/app/'.$dir_path . '/' . $avatar);
//        //        $avatar_path= 'storage/app/'.$dir_path . '/' . $avatar;
//        //        $avatar_path= $file_full_path;
//        if ($file_exists) {
//            $avatar_url       = 'storage/app/' . $dir_path . '/' . $avatar;
//            $avatarPropsArray = ['avatar' => $avatar, 'avatar_path' => $avatar_path, 'avatar_url' => '/storage/' . $dir_path . '/' . $avatar];
//            $avatar_full_path = base_path() . '/storage/app/public/' . $avatar_path;
//        } else {
//            $avatarPropsArray = ['avatar' => $avatar, 'avatar_path' => $avatar_path, 'avatar_url' => $file_full_path];
//            $avatar_full_path = base_path() . '/public/' . $avatar_path;
//        }
//
//        if ( ! empty($previewSizeArray['width'])) {
//            $avatarPropsArray['preview_width']  = $previewSizeArray['width'];
//            $avatarPropsArray['preview_height'] = $previewSizeArray['height'];
//        }
//        $userImgProps = with(new MyAppModel())->getCFImageProps($avatar_full_path, $avatarPropsArray);
//
//        return $userImgProps;
//
//    }

    public static function getUserFullPhotoDir(int $user_full_photo_id): string
    {
        return self::$uploads_user_full_photos_dir . $user_full_photo_id . '/';
    }


    public static function getUserFullPhotoPath(int $user_full_photo_id, $full_photo): string
    {
        if (empty($full_photo)) {
            return '';
        }
        return self::$uploads_user_full_photos_dir . $user_full_photo_id . '/' . $full_photo;
    }





    public static function setUserFullPhotoProps(int $user_id, string $photo = null, bool $skip_non_existing_file = false): array
    {
//        echo '<pre>$user_id::'.print_r($user_id,true).'</pre>';
//        echo '<pre>setUserImageProps  $photo::'.print_r($photo,true).'</pre>';
//        echo '<pre>$skip_non_existing_file::'.print_r($skip_non_existing_file,true).'</pre>';
        if (empty($photo) and $skip_non_existing_file) {
            return [];
        }


        $dir_path = self::$uploads_user_full_photos_dir . '' . $user_id . '';
        $file_full_path = /*'/app/'.*/$dir_path . '/' . $photo;
//        echo '<pre>!!!!$file_full_path::'.print_r($file_full_path,true).'</pre>';
        $file_exists    = ( !empty($photo) and Storage::disk('local')->exists( 'public/'.$file_full_path) );

//        echo '<pre>$file_exists::'.print_r($file_exists,true).'</pre>';
        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = config('app.empty_img_url');       //file:///_wwwroot/lar/votes/public/photos/emptyImg.png
//            echo '<pre>++ $file_full_path::'.print_r($file_full_path,true).'</pre>';
            $photo          = with(new User)->getFilenameBasename($file_full_path);
        }

        $photo_path = $file_full_path;
        if ($file_exists) {
            $photoPropsArray = ['full_photo' => $photo, 'full_photo_path' => $photo_path, 'full_photo_url' => Storage::url( $file_full_path ) ];
            $photo_full_path = ( $photo_path);
//            echo '<pre>-1 $photo_full_path::'.print_r($photo_full_path,true).'</pre>';
            $userImgProps = with(new User)->getCFImageProps(base_path() . '/storage/app/public/'.$photo_full_path, $photoPropsArray);
        } else {
            $userImgProps = ['full_photo' => $photo, 'full_photo_path' => $photo_path, 'full_photo_url' => ($file_full_path) ];
        }


//        echo '<pre>$userImgProps::'.print_r($userImgProps,true).'</pre>';
        return $userImgProps;

    }

    public static function setUserFullPhotoProps_OLD(int $user_id, string $full_photo = null, bool $skip_non_existing_file = false): array
    {
        if (empty($full_photo) and $skip_non_existing_file) {
            return [];
        }

        $dir_path = self::$uploads_user_full_photos_dir . '' . $user_id . '';

        $file_full_path = $dir_path . '/' . $full_photo;
        $file_exists    = ( !empty($full_photo) and Storage::disk('local')->exists('public/' . $file_full_path) );

        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = /*'public' . */
                config('app.empty_img_url');
            $full_photo          = with(new MyAppModel())->getFilenameBasename($file_full_path);
        }

        $full_photo_path = $file_full_path;
        if ($file_exists) {
            $full_photo_url       = 'storage/app/' . $dir_path . '/' . $full_photo;
            $full_photoPropsArray = ['full_photo' => $full_photo, 'full_photo_path' => $full_photo_path, 'full_photo_url' => '/storage/' . $dir_path . '/' . $full_photo];
            $full_photo_full_path = base_path() . '/storage/app/public/' . $full_photo_path;
        } else {
            $full_photoPropsArray = ['full_photo' => $full_photo, 'full_photo_path' => $full_photo_path, 'full_photo_url' => $file_full_path];
            $full_photo_full_path = base_path() . '/public/' . $full_photo_path;
        }

        if ( ! empty($previewSizeArray['width'])) {
            $full_photoPropsArray['preview_width']  = $previewSizeArray['width'];
            $full_photoPropsArray['preview_height'] = $previewSizeArray['height'];
        }
        $userImgProps = with(new MyAppModel())->getCFImageProps($full_photo_full_path, $full_photoPropsArray);

        return $userImgProps;

    }

    /* get additional properties of cms_item photo : path, url, size etc... */
    public function getUserAvatarPropsAttribute(): array
    {
        return $this->userAvatarPropsArray;
    }

    /* set additional properties of cms_item photo : path, url, size etc... */
    public function setUserAvatarPropsAttribute(array $userAvatarPropsArray)
    {
        $this->userAvatarPropsArray = $userAvatarPropsArray;
    }


    /* get additional properties of cms_item photo : path, url, size etc... */
    public function getUserFullPhotoPropsAttribute()
    {
        return $this->userFullPhotoPropsArray;
    }

    /* set additional properties of cms_item photo : path, url, size etc... */
    public function setUserFullPhotoPropsAttribute(array $userFullPhotoPropsArray)
    {
        $this->userFullPhotoPropsArray = $userFullPhotoPropsArray;
    }


    public static function getUserAvatarTempDir(string $session_id): string
    {
        return self::$uploads_user_avatar_temps_dir . $session_id . '/';
    }

    public static function getUserAvatarTempPath(string $session_id, $photo): string
    {
        if (empty($photo)) {
            return '';
        }
        return self::$uploads_user_avatar_temps_dir . $session_id . '/' . $photo;
    }

    public static function getUserFullPhotoTempDir(string $session_id): string
    {
        return self::$uploads_user_full_photo_temps_dir . $session_id . '/';
    }

    public static function getUserFullPhotoTempPath(string $session_id, $photo): string
    {
        if (empty($photo)) {
            return '';
        }
        return self::$uploads_user_full_photo_temps_dir . $session_id . '/' . $photo;
    }



    public static function getUserRegisterValidationRulesArray($options= []) : array
    {
        $users_tb= with(new User)->getTable();
        $validationRulesArray = [
            'username'        => 'required|max:255|unique:'.$users_tb,
            'email'           => 'required|email|max:255|unique:'.$users_tb,
            'password'        => 'required|min:6|max:15',
            'password_conf'   => 'required|min:6|max:15|same:password',
            'first_name'      => 'nullable|max:50',
            'last_name'       => 'nullable|max:50',
            'phone'           => 'nullable|max:50',
            'website'         => 'nullable|max:50',
            'notes'           => 'nullable',
            'sex'             => 'nullable|in:' . with(new Group)->getValueLabelKeys(User::getUserSexValueArray(false)),
            'activated_at'    => 'nullable',
        ];

        if ( in_array('hide_username', $options)) {
            unset($validationRulesArray['username']);
        }
        if ( in_array('hide_email', $options)) {
            unset($validationRulesArray['email']);
        }
        if ( in_array('hide_password', $options)) {
            unset($validationRulesArray['password']);
        }
        if ( in_array('hide_password_conf', $options)) {
            unset($validationRulesArray['password_conf']);
        }
        return $validationRulesArray;
    }

    // verified and verification_token
    public static function getUniqueUsername( string $username )
    {
        $index= 0;
        while($index < 1000) {
            $indexed_username= $username . ( $index > 0 ? $index:'');
            $similarUse = User::getSimilarUserByUsername($indexed_username);
            if ( empty($similarUse) ) return $indexed_username;
            $index++;
        }
        return $indexed_username;
    }

    public static function getSimilarUserByVerificationToken( string $verification_token, int $id= null, $return_count= false )
    {
        $quoteModel = User::where( 'verification_token', $verification_token );
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

    /* check if provided name is unique for users.name field */
    public static function getSimilarUserByUsername( string $username, int $id= null, bool $return_count = false )
    {
        $quoteModel = User::whereRaw( (new MyAppModel)->myStrLower('username', false, false) .' = '. (new MyAppModel)->myStrLower( (new MyAppModel)->mysqlEscape($username), true,false) );
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

    /* check if provided email is unique for users.email field */
    public static function getSimilarUserByEmail( string $email, int $id= null, bool $return_count = false )
    {
        $quoteModel = User::whereRaw( (new MyAppModel)->myStrLower('email', false, false) .' = '. (new MyAppModel)->myStrLower( (new MyAppModel)->mysqlEscape($email), true,false) );
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


    public static function generatePassword()	{

        return str_random(self::$password_length);
    }

    public static function getUsersSelectionArray(string $filter_status=null) :array {
        $users = User::orderBy('username','desc')->getByStatus($filter_status)->get();
        $usersSelectionArray= [];
        foreach( $users as $nextUser ) {
            $usersSelectionArray[$nextUser->id]= $nextUser->username.' ( '.$nextUser->last_name.' '.$nextUser->last_name . ', '.$nextUser->email.' )';
        }
        return $usersSelectionArray;
    }

    public static function getValidationRulesArray($user_id = null, $options= []): array
    {
        $validationRulesArray = [
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique(with(new User)->getTable())->ignore($user_id),
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique(with(new User)->getTable())->ignore($user_id),
            ],

            'password'        => 'required|max:255|min:6|required_with:password_2',
            'password_2' => 'required|max:255|min:6',

            'first_name'      => 'required|max:50',
            'last_name'       => 'required|max:50',
            'phone'           => 'max:50',
            'website'         => 'max:50',

            'status'      => 'required|in:' . with(new Group)->getValueLabelKeys(User::getUserStatusValueArray(false)),
        ];

        if (in_array('skip_email', $options)) {
            unset($validationRulesArray['email']);
        }
        if (in_array('skip_password', $options)) {
            unset($validationRulesArray['password']);
            unset($validationRulesArray['password_2']);
        }

        return $validationRulesArray;
    }


}
