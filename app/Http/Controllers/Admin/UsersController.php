<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use ImageOptimizer;

use App\Http\Controllers\MyAppController;
use DB;
use App\Chat;
use App\ChatMessage;
use App\User;
use App\UserGroup;
use App\Group;
use App\Settings;
use App\ChatParticipant;
use App\SiteSubscription;
use App\UsersSiteSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\UserRequest;
use App\Mail\SendgridMail;

class UsersController extends MyAppController
{
    use funcsTrait;
    private $chats_tb;
    private $chat_messages_tb;

    public function __construct()
    {
        parent::__construct();
        $this->chats_tb= with(new Chat)->getTable();
        $this->chat_messages_tb= with(new ChatMessage())->getTable();
    }

    // USER'S LISTING/EDITOR BLOCK BEGIN
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter_type = '', $filter_value = '')
    {
        $userStatusValueArray         = $this->SetArrayHeader(['' => ' -Select status- '], User::getUserStatusValueArray(false));
        $viewParamsArray                                 = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['userStatusValueArray']         = $userStatusValueArray;
        $viewParamsArray['appParamsForJSArray']          = json_encode($appParamsForJSArray);

//        echo '<pre>$viewParamsArray::'.print_r($viewParamsArray,true).'</pre>';
//        die("-1 XXZ===");
        return view($this->getBackendTemplateName() . '.admin.user.index', $viewParamsArray);
    }

    public function get_users_dt_listing()
    {
        $request = request();

        $filter_username   = $request->input('filter_username', '');
        $filter_status = $request->input('filter_status', '');

        $usersCollection = User
            ::getByUsername($filter_username, true)
            ->getByStatus($filter_status, true)
            ->get();
        foreach( $usersCollection as $next_key=> $nextUser ) {
            $usersCollection[$next_key]->slashed_username= addslashes($nextUser->username);
        }

        return Datatables
            ::of($usersCollection)
            ->editColumn('status', function ($user) {
                if ( ! isset($user->status)) {
                    return '';
                }
                return User::getUserStatusLabel($user->status);
            })
            ->setRowClass(function ($user) {
                return ! $user->status == "I" ? ' row_new_status ' : '';
            })
            ->editColumn('created_at', function ($user) {
                if (empty($user->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($user->created_at);
            })
            ->editColumn('updated_at', function ($user) {
                if (empty($user->updated_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($user->updated_at);
            })
            ->editColumn('action', '<a href="/admin/users/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendUser.deleteUser({{$id}},\'{{$slashed_username}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id)
    {
        $userStatusValueArray         = $this->SetArrayHeader(['' => ' -Select status- '], User::getUserStatusValueArray(false));
        $user                         = User::find($user_id);

        $userAvatarPropsAttribute     = User::setUserAvatarProps($user->id, $user->avatar, true);
        if (count($userAvatarPropsAttribute) > 0) {
            $user->setUserAvatarPropsAttribute($userAvatarPropsAttribute);
        }

        $userFullPhotoPropsAttribute  = User::setUserFullPhotoProps($user->id, $user->full_photo, true);
//        echo '<pre>$userAvatarPropsAttribute::'.print_r($userAvatarPropsAttribute,true).'</pre>';
//        echo '<pre>$userFullPhotoPropsAttribute::'.print_r($userFullPhotoPropsAttribute,true).'</pre>';
//        die("-1 XXZ");
        if (count($userFullPhotoPropsAttribute) > 0) {
            $user->setUserFullPhotoPropsAttribute($userFullPhotoPropsAttribute);
        }
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        if ($user === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg',
                ['text' => 'User with id # "' . $user_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $viewParamsArray['userSexLabelValueArray']            = $this->SetArrayHeader(['' => ' -Select Sex- '], User::getUserSexValueArray(false));
        $viewParamsArray['user']                         = $user;
        $appParamsForJSArray['id']                       = $user_id;
        $viewParamsArray['userStatusValueArray']         = $userStatusValueArray;
        $viewParamsArray['appParamsForJSArray']          = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.user.edit', $viewParamsArray);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, int $user_id)
    {
        $viewParamsArray  = $this->getAppParameters(true, ['csrf_token']);
//        echo '<pre>UPDATE $user_id::' . print_r($user_id, true) . '</pre>';

        $user = User::find($user_id);
        if ($user === null) {
            $viewParamsArray['text'] = 'User with id # "' . $user_id . '" not found !';
            $viewParamsArray['type'] = 'danger';
            return View( $this->getBackendTemplateName() . '.admin.dashboard.msg', $viewParamsArray );
        }
        $userUploadFileAvatar = $request->file('avatar');
        $userUploadFileFullPhoto = $request->file('full_photo');

        $uploaded_file_max_mib = (int)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size              = 1024 * $uploaded_file_max_mib;
        $rules                 = array(
            'avatar' => 'max:' . $max_size,
            'full_photo' => 'max:' . $max_size,
        );
        $validator             = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        if ( ! empty($userUploadFileAvatar)) {
            $user_avatar     = UserGroup::checkValidImgName($userUploadFileAvatar->getClientOriginalName(), with(new User)->getAvatarFilenameMaxLength(), true);
            $avatar_file_path  = $userUploadFileAvatar->getPathName();
            $user->avatar    = $user_avatar;
        } // if (!empty($userUploadFileAvatar)) {

        if ( ! empty($userUploadFileFullPhoto)) {
            $user_full_photo     = UserGroup::checkValidImgName($userUploadFileFullPhoto->getClientOriginalName(), with(new User)->getAvatarFilenameMaxLength(), true);
            $user_full_photo_file_path  = $userUploadFileFullPhoto->getPathName();
            $user->full_photo    = $user_full_photo;
        } // if (!empty($userUploadFileFullPhoto)) {

        if ( ! empty($user_avatar) and !empty($avatar_file_path)) {
            $dest_avatar = 'public/' . User::getUserAvatarPath($user_id, $user_avatar);
            Storage::disk('local')->put($dest_avatar, File::get($avatar_file_path));
            ImageOptimizer::optimize( storage_path().'/app/'.$dest_avatar, null );
        }

        if ( ! empty($user_full_photo) and !empty($user_full_photo_file_path) ) {
            $dest_full_photo = 'public/' . User::getUserFullPhotoPath($user_id, $user_full_photo);
            Storage::disk('local')->put($dest_full_photo, File::get($user_full_photo_file_path));
            ImageOptimizer::optimize( storage_path().'/app/'.$dest_avatar, null );
        }

        DB::beginTransaction();
        try {
            $requestData = $request->all();
            $user->first_name = $requestData['first_name'];
            $user->last_name  = $requestData['last_name'];
            $user->phone      = $requestData['phone'];
            $user->website    = $requestData['website'];
            $user->notes      = $requestData['notes'];
            $user->sex        = $requestData['sex'];
            $user->updated_at = Carbon::now(config('app.timezone'));
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('User updated successfully !', 'success', 'Backend');

        return redirect()->route('admin.users.index');
    }


    /* delete item with related users */
    public function destroy(Request $request)
    {
        $id = $request->get('id');

        $user = User::find($id);
        if ($user === null) {
            return response()->json(['error_code' => 11, 'message' => 'User # "' . $id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    public function generate_password($user_id)
    {
        $user               = User::find($user_id);
        $generated_password = User::generatePassword();
        $hashed_password    = bcrypt($generated_password);
        $site_name          = Settings::getValue('site_name');

        DB::beginTransaction();
        try {
            $user->password   = $hashed_password;
            $user->updated_at = Carbon::now(config('app.timezone'));
            $user->save();
            DB::commit();
            $additiveVars= [
                'to_user_email'        => $user->email,
                'new_generated_password'   => $generated_password,
                'to_user_name'         => $user->username,
                'to_user_first_name'   => $user->first_name,
                'to_user_last_name'    => $user->last_name,

            ];
            $subject= 'New password was generated at ' . $site_name . ' site ';
            \Mail::to($user->email)->send( new SendgridMail( 'emails/password_was_generated',$user->email,  '', $subject , $additiveVars ) );

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'user_id' => $user->id], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'New password was generated !', 'error_code' => 0], HTTP_RESPONSE_OK);
    } // public function generate_password()
    // USER'S LISTING/EDITOR BLOCK BEGIN

    // USER'S GROUP ACCESS BLOCK BEGIN
    public function get_user_access_groups_info($user_id)
    {
        $groups= Group
            ::all()
            ->toArray();
        $userGroups= UserGroup::getByUser($user_id)->get();
        foreach( $groups as $next_key=>$nextGroup ) {
            $is_found= false;
            foreach( $userGroups as $nextUserGroup ) {
                if ( (int)$nextGroup['id'] == (int)$nextUserGroup->group_id) {
                    $is_found= true;
                    break;
                }
            }
            if ($is_found) {
                $groups[$next_key]['is_checked'] = true;
            }
        }
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, []);
        $viewParamsArray['userGroupSelectedValueArray']      = $this->SetArrayHeader(['' => ' -Select access selected- '], UserGroup::getUserGroupSelectedValueArray(false));
        $viewParamsArray['groups'] = $groups;
        $html                      = view($this->getBackendTemplateName() . '.admin.user.get_user_access_groups_info', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_user_access_groups_info()



    public function update_user_access()
    {
        $request= request();
        $requestData= $request->all();
        $user_id= $requestData['user_id'];
        $user = User::find($user_id);
        if ($user === null) {
            return response()->json(['error_code' => 11, 'message' => 'User # "' . $user_id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $selectedUserAccess= $requestData['selectedUserAccess'];

        DB::beginTransaction();
        try {
            foreach( $selectedUserAccess as $nextSelectedUserAccess ) {
                $a = preg_split("/checked_user_access_/", $nextSelectedUserAccess['id']);
                if ( count($a) == 2 ) {

                    $userGroup= UserGroup
                        ::getByGroup($a[1])
                        ->getByUser($user_id)
                        ->first();
                    if ( empty($userGroup) and $nextSelectedUserAccess['selected'] == 1) {
                        $userGroup           = new UserGroup();
                        $userGroup->user_id  = $user_id;
                        $userGroup->group_id = $a[1];
                        $userGroup->save();
                    } // add to group

                    if ( empty($userGroup) and $nextSelectedUserAccess['selected'] == 0) {
                    } // nothing

                    if ( !empty($userGroup) and $nextSelectedUserAccess['selected'] == 0) {
                        $userGroup->delete();
                    } // clear from group

                }
            }

            $user->updated_at= Carbon::now(config('app.timezone'));
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error_code' => 1, 'message' => $e->getMessage() ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Your modifications were applied !', 'error_code' => 0], HTTP_RESPONSE_OK);
    } // public function update_user_access()
    // USER'S GROUP ACCESS BLOCK END


    // USER'S SITE SUBSCRIPTIONS BLOCK BEGIN
    public function get_user_related_site_subscriptions(int $user_id)
    {
        $siteSubscriptionsList   = [];
        $siteSubscriptions          = SiteSubscription
            ::orderBy('name', 'asc')
            ->get();

        $usersSiteSubscriptions= UsersSiteSubscription
            ::getByUserId($user_id)
            ->get();

        foreach( $siteSubscriptions as $next_key=>$nextSiteSubscription ) {
            $is_site_subscription_selected= false;
            foreach( $usersSiteSubscriptions as $nextUsersSiteSubscription ) {
                if ( (int)$nextUsersSiteSubscription->site_subscription_id == (int)$nextSiteSubscription->id ) {
                    $is_site_subscription_selected= 1;
                    break;
                }
            }
            $siteSubscriptionsList[]= [
                'id'           => $nextSiteSubscription->id,
                'name'         => $nextSiteSubscription->name,
                'active'       => $nextSiteSubscription->active,
                'created_at'   => $nextSiteSubscription->created_at,
                'selected'     => $is_site_subscription_selected,
            ];
        }

        $user                         = User::find($user_id);
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['user_id']   = $user_id;
        $viewParamsArray['user']      = $user;
        $viewParamsArray['siteSubscriptionsList']  = $siteSubscriptionsList;
        $viewParamsArray['selected_site_subscriptions_count']  = count($usersSiteSubscriptions);
        $html                         = view($this->getBackendTemplateName() . '.admin.user.related_site_subscriptions', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_user_related_site_subscriptions()

    public function attach_related_site_subscription($user_id, $site_subscription_id)
    {
        $user = User::find($user_id);
        if ($user === null) {
            return response()->json(['error_code' => 11, 'message' => 'User # "' . $user_id . '" not found!', 'user' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        $site_subscription = SiteSubscription::find($site_subscription_id);
        if ($site_subscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Site subscription # "' . $site_subscription_id . '" not found!', 'site_subscription' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $newUsersSiteSubscription= new UsersSiteSubscription();
            $newUsersSiteSubscription->site_subscription_id= $site_subscription_id;
            $newUsersSiteSubscription->user_id= $user_id;
            $newUsersSiteSubscription->save();

            $user->updated_at= Carbon::now(config('app.timezone'));
            $user->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'user' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function attach_related_site_subscription($user_id, $site_subscription_id)

    public function clear_related_site_subscription($user_id, $site_subscription_id)
    {
        $user = User::find($user_id);
        if ($user === null) {
            return response()->json(['error_code' => 11, 'message' => 'User # "' . $user_id . '" not found!', 'user' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $site_subscription = SiteSubscription::find($site_subscription_id);
        if ($site_subscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Site subscription # "' . $site_subscription_id . '" not found!', 'site_subscription' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $site_subscriptiongable= UsersSiteSubscription
                ::getByUserId($user_id)
                ->getBySiteSubscriptionId($site_subscription_id)
                ->first();
            if ($site_subscriptiongable!= null) {
                $site_subscriptiongable->delete();
            }
            $user->updated_at= Carbon::now(config('app.timezone'));
            $user->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'user' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function clear_related_site_subscription($user_id, $site_subscription_id)

    // USER'S SITE SUBSCRIPTIONS BLOCK END


    // USER'S CHATS BLOCK BEGIN
    public function get_user_related_chats(int $user_id)
    {
        $chatsList   = [];
        $chats          = Chat
            ::select( $this->chats_tb.'.*', \DB::raw('count('.DB::getTablePrefix().$this->chat_messages_tb.'.id) as messages_count'), \DB::raw('sum(if('.DB::getTablePrefix().$this->chat_messages_tb.'.user_id='.$user_id.',1,0)) as 
        user_messages_count') )
            ->orderBy('name', 'asc')
            ->groupBy($this->chats_tb.'.id')
            ->leftJoin( $this->chat_messages_tb, $this->chat_messages_tb.'.chat_id', '=', $this->chats_tb.'.id' )
            ->get();


        $chatParticipants= ChatParticipant
            ::getByUser($user_id)
            ->orderBy('status', 'desc')
            ->get();

        foreach( $chats as $next_key=>$nextChat ) {
            $is_chat_selected = false;
            $selected_chat_access_status  = '';
            $selected_chat_access_status_label  = '';
            foreach( $chatParticipants as $nextChatParticipant ) {
                if ( (int)$nextChatParticipant->chat_id == (int)$nextChat->id ) {
                    $is_chat_selected= true;
                    $selected_chat_access_status= $nextChatParticipant->status;
                    $selected_chat_access_status_label= ChatParticipant::getChatParticipantParticipantTypeLabel($nextChatParticipant->status);
                    break;
                }
            }
            $chatsList[]= [
                'id'           => $nextChat->id,
                'name'         => $nextChat->name,
                'messages_count'         => $nextChat->messages_count,
                'user_messages_count'         => $nextChat->user_messages_count,
                'status'       => $nextChat->status,
                'created_at'   => $nextChat->created_at,
                'user_in_chat' => $is_chat_selected,
                'selected_chat_access_status' => $selected_chat_access_status,
                'selected_chat_access_status_label' => $selected_chat_access_status_label,
            ];
        }

//        echo '<pre>$chatsList::'.print_r($chatsList,true).'</pre>';
//        die("-1 XXZ");

        $user                         = User::find($user_id);
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['user_id']   = $user_id;
        $viewParamsArray['user']      = $user;
        $viewParamsArray['chatsList']  = $chatsList;
        $viewParamsArray['selected_chats_count']  = count($chatParticipants);
        $html                         = view($this->getBackendTemplateName() . '.admin.user.related_chat_participants', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_user_related_chats()

//Route::get('/users/attach_related_chat/{user_id}/{chat_id}', 'Admin\UsersController@attach_related_chat');
//Route::get('/users/clear_related_chat/{user_id}/{chat_id}', 'Admin\UsersController@clear_related_chat');



//attach_chat_participant_to_user
        public function attach_chat_participant_to_user(Request $request)
        {
            $user_id  = $request->get('user_id');
            $chat_id  = $request->get('chat_id');
            $status  = $request->get('status');

            $user = User::find($user_id);
            if ($user === null) {
                return response()->json(['error_code' => 11, 'message' => 'User # "' . $user_id . '" not found!', 'user' => null],
                    HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }

            $chat = Chat::find($chat_id);
            if ($chat === null) {
                return response()->json(['error_code' => 11, 'message' => 'Chat # "' . $chat_id . '" not found!', 'chat' => null],
                    HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }

            DB::beginTransaction();
            try {
                $newChatParticipant= new ChatParticipant();
                $newChatParticipant->chat_id= $chat_id;
                $newChatParticipant->user_id= $user_id;
                $newChatParticipant->status= $status;
                $newChatParticipant->save();

                $user->updated_at= Carbon::now(config('app.timezone'));
                $user->save();

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();

                return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'user' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
        } // public function attach_chat_participant_to_user($user_id, $chat_id, $status)

    // backendUser.prototype.clearChatParticipantOfUser = function (chat_id, chat_name) {

    public function clear_related_chat(Request $request)
        {
            $user_id  = $request->get('user_id');
            $chat_id  = $request->get('chat_id');
            $user = User::find($user_id);
            if ($user === null) {
                return response()->json(['error_code' => 11, 'message' => 'User # "' . $user_id . '" not found!', 'user' => null],
                    HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
            $chat = Chat::find($chat_id);
            if ($chat === null) {
                return response()->json(['error_code' => 11, 'message' => 'Chat # "' . $chat_id . '" not found!', 'chat' => null],
                    HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }

            DB::beginTransaction();
            try {
                $ChatParticipant= ChatParticipant
                    ::getByUser($user_id)
                    ->getByChat($chat_id)
                    ->first();
                if ($ChatParticipant!= null) {
                    $ChatParticipant->delete();
                }

                $user->updated_at= Carbon::now(config('app.timezone'));
                $user->save();

                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'user' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
        } //     public function clear_related_chat($user_id, $chat_id)

    // USER'S CHATS BLOCK END


//admin_user_get_user_related_chats

}
