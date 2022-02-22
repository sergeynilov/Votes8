<?php

namespace App\Http\Controllers;

use App\MyAppModel;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ImageOptimizer;

use App\User;
use App\UserGroup;
use App\Chat;
use App\ChatMessage;
use App\ChatMessageDocument;
use App\UsersSiteSubscription;
use App\ChatParticipant;
use App\Events\VoteChatMessageSentEvent;    // app/Events/VoteChatMessageSentEvent.php
use App\Http\Traits\funcsTrait;


class UserChatController extends MyAppController
{

    use funcsTrait;
    private $tmp_chat_message_document_session_key = 'tmp_chat_message_document';
    private $session_id = '';


    /*** USER'S CHAT BLOCK START  ***/
    public function user_chat_container()
    {
//        if ($this->isDeveloperComp()) {
//            return $this->print_to_pdf();
//        }
        $loggedUser                                   = Auth::user();
        $chat_id                                      = 1;
        $viewParams                                   = $appParamsForJSArray = $this->getAppParameters(false, ['csrf_token', 'site_name', 'site_heading', 'site_subheading']);
        $appParamsForJSArray['chat_id']               = $chat_id;
        $viewParams['chat_id']                        = $chat_id;
        $appParamsForJSArray['logged_user_id']        = $loggedUser->id;
        $appParamsForJSArray['logged_user_username']  = $loggedUser->username;
        $viewParams['appParamsForJSArray']            = json_encode($appParamsForJSArray);
        $viewParams['loggedUser']                     = $loggedUser;
        $viewParams['medium_slogan_img_url']          = config('app.medium_slogan_img_url');

//        echo '<pre>$appParamsForJSArray::'.print_r($appParamsForJSArray,true).'</pre>';
//        echo '<pre>$viewParams::'.print_r($viewParams,true).'</pre>';
//        die("-1 XXZ");
        return view($this->getFrontendTemplateName() . '.profile.user_chat_container', $viewParams);
    } // public function user_chat_container()


    public function load_user_chat_content($chat_id)
    {

        $loggedUser                          = Auth::user();
        $chatParticipant = ChatParticipant
            ::getByChat($chat_id)
            ->getByUser($loggedUser->id)
            ->first();

        $relatedChatMessageDocuments= [];
        $tempRelatedChatMessageDocuments = ChatMessageDocument
//            ::chatMessageDocuments($chat_id)
            ::orderBy('created_at', 'desc')
            ->get();

        foreach( $tempRelatedChatMessageDocuments as $next_key=>$nextTempRelatedChatMessageDocument ) {
//            $relatedChatMessageDocuments[]=
            $chatMessageDocumentProps = ChatMessageDocument::setChatMessageDocumentImageProps($nextTempRelatedChatMessageDocument->id, $nextTempRelatedChatMessageDocument->filename, false);
            $chatMessageDocumentProps['info'] = $nextTempRelatedChatMessageDocument->info;
            $relatedChatMessageDocuments[]   = $chatMessageDocumentProps;
        }

//        echo '<pre>$relatedChatMessageDocuments::'.print_r($relatedChatMessageDocuments,true).'</pre>';
//        die("-1 XXZ");
        $profileUsersSiteSubscriptions = UsersSiteSubscription
            ::getByUserId($loggedUser->id)
            ->select(
                'users_site_subscriptions.*',
                'site_subscriptions.name as site_subscription_name',
                'site_subscriptions.vote_category_id',
                'vote_categories.name as vote_category_name'
            )
            ->leftJoin('site_subscriptions', 'site_subscriptions.id', '=', 'users_site_subscriptions.site_subscription_id')
            ->leftJoin('vote_categories', 'vote_categories.id', '=', 'site_subscriptions.vote_category_id')
            ->orderBy('site_subscription_name', 'asc')
            ->get();
        $avatarData                    = User::setUserAvatarProps($loggedUser->id, $loggedUser->avatar, true);

        $viewParams['chat_id']                       = $chat_id;
        $viewParams['chatParticipant']               = $chatParticipant;
        $viewParams['profileUsersSiteSubscriptions'] = $profileUsersSiteSubscriptions;
        $viewParams['avatar_dimension_limits']       = \Config::get('app.avatar_dimension_limits', ['max_width' => 32, 'max_height' => 32]);
        $viewParams['full_photo_dimension_limits']   = \Config::get('app.full_photo_dimension_limits', ['max_width' => 256, 'max_height' => 256]);
        $viewParams['avatarData']                    = $avatarData;
        $currentChat                                 = Chat::find($chat_id);
        if ($currentChat === null) {
//            return View($this->getBackendTemplateName() . '.admin.dashboard.msg',
//                ['text' => 'User with id # "' . $user_id . '" not found !', 'type' => 'danger', 'action' => ''],
//                $viewParamsArray);
        }
        $viewParams['currentChat'] = $currentChat;
        $viewParams['loggedUser'] = $loggedUser;
        $viewParams['relatedChatMessageDocuments']   = $relatedChatMessageDocuments;

        $chatMessages     = [];

        $tempChatMessages = ChatMessage
            ::select(
                'chat_messages.*',
                'users.username as chat_message_author_name',
                \DB::raw( '( select count(' .DB::getTablePrefix().'chat_message_documents'.'.chat_message_id) from ' .DB::getTablePrefix().'chat_message_documents where ' .DB::getTablePrefix().'chat_message_documents.chat_message_id = ' .DB::getTablePrefix().'chat_messages.id ) as chat_message_documents_count' )
            )
            ->getByChat($chat_id)
            ->join('users', 'users.id', '=', 'chat_messages.user_id')
            ->orderBy('is_top', 'desc')
            ->orderBy('chat_messages.id', 'desc')
            ->get();
        foreach ($tempChatMessages as $nextTempChatMessage) {
            $avatarData     = User::setUserAvatarProps($nextTempChatMessage->user_id, $nextTempChatMessage->user->avatar, true);
            $chatMessages[] = [
                'id'             => $nextTempChatMessage->id,
                'user_id'        => $nextTempChatMessage->user_id,
                'username'       => $nextTempChatMessage->user->username,
                'user_full_name' => $nextTempChatMessage->user->full_name,
                'is_top'         => $nextTempChatMessage->is_top,
                'text'           => $nextTempChatMessage->text,
                'created_at'     => $nextTempChatMessage->created_at,
                'chat_message_documents_count'     => $nextTempChatMessage->chat_message_documents_count,
                'avatarData'     => $avatarData,
//                'user_id'=> $nextTempChatMessage->user_id,
            ];
        }

        $viewParams['chatMessages'] = $chatMessages;

//        return view($this->getFrontendTemplateName() . '.profile.user_chat_content', $viewParams);
        $html = view($this->getFrontendTemplateName() . '.profile.user_chat_content', $viewParams)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);

    }

    public function show_chat_members($chat_id, $activeUserIds)
    {
        $loggedUser = Auth::user();

        $chatParticipants     = [];
        $tempChatParticipants = ChatParticipant
            ::getByChat($chat_id)
            ->orderBy('status', 'asc')
            ->get();

        $activeUsers     = [];
        $tempActiveUsers = User
            ::getByStatus('A')
            ->orderBy('username', 'asc')
            ->get();

        $activeUserIdsArray = $this->pregSplit('/,/', $activeUserIds);
        $online_count       = 0;
        $onlineUsersInChat  = [];
        $usersInChat        = [];
        foreach ($tempChatParticipants as $nextTempChatParticipant) {
            $is_online = in_array($nextTempChatParticipant->user_id, $activeUserIdsArray);
            if ($is_online) {
                $online_count++;
                $onlineUsersInChat[] = $nextTempChatParticipant->user->id;
            }
            $avatarData    = User::setUserAvatarProps($nextTempChatParticipant->user_id, $nextTempChatParticipant->user->avatar, true);
            $usersInChat[] = $nextTempChatParticipant->user->id;

            $chatParticipants[] = [
                'user_id'      => $nextTempChatParticipant->user->id,
                'username'     => $nextTempChatParticipant->user->username,
                'full_name'    => $nextTempChatParticipant->user->full_name,
                'email'        => $nextTempChatParticipant->user->email,
                'you'          => $loggedUser->id == $nextTempChatParticipant->user_id,
                'status'       => $nextTempChatParticipant->status,
                'status_label' => ChatParticipant::getChatParticipantParticipantTypeLabel($nextTempChatParticipant->status),
                'avatarData'   => $avatarData,
                'is_online'    => $is_online
            ];
        }
        foreach ($tempActiveUsers as $next_key => $nextActiveUser) {
            $user_in_chat  = in_array($nextActiveUser->id, $usersInChat);
            $is_online     = in_array($nextActiveUser->id, $onlineUsersInChat);
            $avatarData    = User::setUserAvatarProps($nextActiveUser->id, $nextActiveUser->avatar, true);
            $activeUsers[] = [
                'user_id'      => $nextActiveUser->id,
                'username'     => $nextActiveUser->username,
                'full_name'    => $nextActiveUser->full_name,
                'email'        => $nextActiveUser->email,
                'you'          => $loggedUser->id == $nextActiveUser->id,
                'avatarData'   => $avatarData,
                'user_in_chat' => $user_in_chat,
                'is_online'    => $is_online
            ];
        }
//        echo '<pre>$activeUsers::'.print_r($activeUsers,true).'</pre>';
//        echo '<pre>$chatParticipants:'.print_r($chatParticipants,true).'</pre>';
//        die("-1 XXZ");
        uasort($activeUsers, array($this, 'cmpActiveUsersByUserInChat'));
        $viewParams['chat_id']          = $chat_id;
        $viewParams['userProfile']      = $loggedUser;
        $viewParams['chatParticipants'] = $chatParticipants;
        $viewParams['activeUsers']      = $activeUsers;
        $viewParams['online_count']     = $online_count;

        $html = view($this->getFrontendTemplateName() . '.profile.user_chat_show_chat_members', $viewParams)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function show_chat_members()

    public function cmpActiveUsersByUserInChat($a, $b)
    {
        if ($a['user_in_chat'] == strtolower($b['user_in_chat'])) {
            return 0;
        }

        return ($a['user_in_chat'] < $b['user_in_chat']) ? 1 : -1;
    }

    public function attach_user_to_chat($chat_id, $user_id, $status)
    {
        $lChatParticipant          = new ChatParticipant();
        $lChatParticipant->chat_id = $chat_id;
        $lChatParticipant->user_id = $user_id;
        $lChatParticipant->status  = $status;
        $lChatParticipant->save();

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    }

    public function clear_user_in_chat($chat_id, $user_id)
    {
        $chatParticipant = ChatParticipant
            ::getByChat($chat_id)
            ->getByUser($user_id)
            ->first();
        if (empty($chatParticipant)) {
            return response()->json(['error_code' => 11, 'message' => 'user # "' . $user_id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $chatParticipant->delete();

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    }


    public function chat_message_sent(Request $request)
    {
        $newChatMessage               = new ChatMessage();
        $loggedUser                   = Auth::user();
        $newChatMessage->user_id      = $loggedUser->id;
        $newChatMessage->chat_id      = $request->get('chat_id');
        $newChatMessage->is_top       = $request->get('is_top');
        $newChatMessage->text         = $request->get('text');
        $newChatMessage->message_type = 'T';

        DB::beginTransaction();
        try {
            $newChatMessage->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error_code' => 11, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
//        broadcast(new NewMessage( $newChatMessage, $loggedUser ))->toOthers();
        broadcast(new VoteChatMessageSentEvent(
                $newChatMessage->text,    /* $message_text */
                $newChatMessage->id,      /* $user_chat_message_id */
                $newChatMessage->chat_id, /*     $chat_id  */
                $newChatMessage->is_top,  /*  $is_top */
                $loggedUser->id,          /* $author_id, */
                $loggedUser->username  /*  $author_name */
            )
        )->toOthers();

        return response()->json(['error_code' => 0, 'message' => '', 'id' => $newChatMessage->id], HTTP_RESPONSE_OK);

    } //public function chat_message_sent(Request $request)

    /*** USER'S CHAT BLOCK START  ***/


//http://local-votes.com/profile/clear-user-in-chat/1/21


    // CHAT_MESSAGE_DOCUMENT BLOCK START
    public function get_settings_related_chat_message_documents()
    {
        $chatMessageDocuments         = [];
        $user_registration_files_str = ChatMessageDocument::getValue('userRegistrationFiles');
        $tempUserRegistrationFiles   = array_unique($this->pregSplit('/;/', $user_registration_files_str));

//        $chat_message_document_preview_width = with(new Settings())->getImgPreviewWidth();

//        echo '<pre>$chat_message_document_preview_width::'.print_r($chat_message_document_preview_width,true).'</pre>';
        foreach ($tempUserRegistrationFiles as $next_key => $next_chat_message_document) {
            $next_chat_message_document = trim($next_chat_message_document);
            if (empty($next_chat_message_document)) {
                continue;
            }
            $chatMessageDocumentProps = ChatMessageDocument::setSettingsChatMessageDocumentProps($next_chat_message_document, false);
            $chatMessageDocuments[]   = $chatMessageDocumentProps;

        }

//        $this->debToFile(print_r($chatMessageDocuments, true), '  TEXT  -1 $chatMessageDocuments::');
        $viewParamsArray                                = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['chatMessageDocuments']         = $chatMessageDocuments;
        $viewParamsArray['chat_message_documents_count'] = count($chatMessageDocuments);
        $html                                           = view($this->getBackendTemplateName() . '.admin.settings.files_on_registration', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function public function get_related_chat_message_documents()



    // upload_chat_message_document_to_tmp_chat_message_document
    // Request URL: http://local-votes.com/profile/upload-image-to-chat-message-document
    public function upload_chat_message_document_to_tmp($chat_id)
    {
        $unique_session_id = Session::getId();
        $this->debToFile(print_r($chat_id, true), 'upload_chat_message_document_to_tmp  TEXT  - chat_id::');
//        die("-1 XXZ $unique_session_id");


        $request     = request();
        $requestData = $request->all();
        $this->debToFile(print_r($requestData, true), '  upload_chat_message_document_to_tmp_chat_message_document  --0 $requestData::');

        $this->debToFile(print_r($_FILES, true), '  upload_chat_message_document_to_tmp_chat_message_document  -21 $_FILES::');

        $UPLOADS_TPM_CHAT_MESSAGE_DOCUMENT_IMAGES_DIR = 'tmp/' . with(new ChatMessageDocument)->getTable();


        $dst_tmp_directory    = $UPLOADS_TPM_CHAT_MESSAGE_DOCUMENT_IMAGES_DIR . '_' . $unique_session_id;
        $tmp_dest_dirname_url = '/' . $UPLOADS_TPM_CHAT_MESSAGE_DOCUMENT_IMAGES_DIR . '_' . $unique_session_id;
        $src_filename         = $_FILES['files']['tmp_name'][0];


        $extension_filename = '';
        $imagesExtensions   = config('app.images_extensions');
        $is_image           = false;

//        $this->debToFile(print_r($src_filename, true), '  $tmpSettingsImagesDirs  -24 $src_filename::');
        $img_basename = ChatMessageDocument::checkValidImgName($_FILES['files']['name'][0], 0, true);
//        $this->debToFile(print_r($img_basename, true), '  $tmpSettingsImagesDirs  -25 $img_basename::');
//        $this->debToFile(print_r($dst_tmp_directory, true), '  $tmpSettingsImagesDirs  -251 v::');
        $filename_extension = $this->getFilenameExtension($img_basename);
//        $this->debToFile(print_r($filename_extension, true), '  999 $filename_extension::');
        foreach ($imagesExtensions as $next_extension) {
            if (strtolower($next_extension) == $filename_extension) {
                //                'extension_image_url' => $this->getFileExtensionsImageUrl($nextTempDownload->file), //     public function getFileExtensionsImageUrl(string $filename):
//                $this->debToFile('INSIDE  -1 $::');
                $is_image = true;
                break;
            }
        }
//        $this->debToFile(print_r($is_image, true), '  TEXT  -1 $is_image::');


        $info_message      = '';
        $tmp_dest_filename = $dst_tmp_directory . DIRECTORY_SEPARATOR . $img_basename;
//        $this->debToFile(print_r($tmp_dest_filename, true), '  $tmpSettingsImagesDirs  -26 $tmp_dest_filename::');
        $tmp_dest_filename_path = storage_path('/app/public/') . $tmp_dest_filename;

        if ($is_image) {
            $FilenameInfo = $this->getImageShowSize($src_filename, with(new ChatMessageDocument)->getImgPreviewWidth(), with(new ChatMessageDocument)->getImgPreviewHeight());
        } else {
            $next_extension_file = $this->getFileExtensionsImageUrl($img_basename); //     public function getFileExtensionsImageUrl(string $filename):
            $extension_filename = $next_extension_file;
//            $this->debToFile(print_r($extension_filename, true), '  -20 $extension_filename::');
            $FilenameInfo = $this->getImageShowSize(public_path($extension_filename), with(new ChatMessageDocument)->getImgPreviewWidth(), with(new ChatMessageDocument)->getImgPreviewHeight());
        }
//        $this->debToFile(print_r($FilenameInfo, true), '  $tmpSettingsImagesDirs  -27 $FilenameInfo::');
        $dest_filename = 'public/' . $tmp_dest_filename;
//        $this->debToFile(print_r($dest_filename, true), '  upload_image_to_chat_message_document  --2 $dest_filename::');
        Storage::disk('local')->put($dest_filename, File::get($src_filename));
        ImageOptimizer::optimize( storage_path().'/app/'.$dest_filename, null );

        $filesize = filesize($tmp_dest_filename_path);
        $chatMessageDocumentProps = with(new ChatMessageDocument)->getCFImageProps($tmp_dest_filename_path, []);
//        $this->debToFile(print_r($chatMessageDocumentProps, true), '  $tmpSettingsImagesDirs  -28 $chatMessageDocumentProps::');
        $resArray = [
            "files" =>
                [
                    "short_name"   => $img_basename,
                    "name"         => $tmp_dest_filename,
                    "size"         => $filesize,
                    'FilenameInfo' => $FilenameInfo,
                    'file_info'    => ! empty($chatMessageDocumentProps['file_info']) ? $chatMessageDocumentProps['file_info'] : '',
                    "size_label"   => $this->getNiceFileSize($filesize),
                    "info_message" => $info_message,
                    "url"          => ($is_image ? ('/storage' . $tmp_dest_dirname_url . '/' . $img_basename . '?tm=' . time()) : $extension_filename),
                ]
        ];
        echo json_encode($resArray);
    }  // upload_chat_message_document_to_tmp_chat_message_document




    public function get_temp_uploading_chat_message_documents($chat_id)
    {
        $tempChatMessageDocumentTmpPaths      = Session::get($this->tmp_chat_message_document_session_key);
        $chatMessageDocumentTmpPaths          = [];
        foreach ($tempChatMessageDocumentTmpPaths as $nextTempChatMessageDocumentTmpPath) {
            if (empty($nextTempChatMessageDocumentTmpPath)) {
                continue;
            }
            $chatMessageDocumentProps = ChatMessageDocument::setSettingsChatMessageDocumentProps($nextTempChatMessageDocumentTmpPath['chat_message_document_tmp_path'], false);
            $chatMessageDocumentProps[]= $nextTempChatMessageDocumentTmpPath['info'];
            $chatMessageDocumentTmpPaths['info']   = $chatMessageDocumentProps;

        }

        $this->debToFile(print_r($chatMessageDocumentTmpPaths, true), '  TEXT  -1 $chatMessageDocumentTmpPaths::');
        $viewParamsArray                                 = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['chat_id             ']         = $chat_id;
        $viewParamsArray['chatMessageDocuments']         = $chatMessageDocumentTmpPaths;
        $viewParamsArray['chat_message_documents_count'] = count($chatMessageDocumentTmpPaths);
        $html                                            = view($this->getFrontendTemplateName() . '.profile.temp_uploading_chat_message_documents', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);

    }

    public function upload_image_to_chat_message_document($chat_id)
    {
        $request     = request();
        $requestData = $request->all();
        $chat_message_document_tmp_path = $requestData['hidden_selected_chat_message_document'];

        $chat = Chat::find($chat_id);
        if ($chat === null) {
            return ['message' => 'Chat with id # "' . $chat_id . '" not found !', 'error_code' => 1, 'chat_id' => $chat_id];
        }
        if (empty($this->session_id)) {
            $this->session_id = Session::getId();
        }

        $info                        = !empty($requestData['info']) ? $requestData['info'] : 'ZZZ';

        $img_basename = ChatMessageDocument::checkValidImgName(basename($chat_message_document_tmp_path), 0, true);
        $similar_chat_message_document_count=  ChatMessageDocument::getSimilarChatMessageDocumentByFilename( $img_basename, $chat_id, null, true );
        if ( $similar_chat_message_document_count > 0 ) {
            return response()->json(['error_code' => 11, 'message' => 'Image "'.$img_basename.'" has been already uploaded for this page !'], HTTP_RESPONSE_OK);
        }

        $this->session_id                 = Session::getId();
        $chatMessageDocumentTmpPaths      = Session::get($this->tmp_chat_message_document_session_key);
        $this->debToFile(print_r($chatMessageDocumentTmpPaths, true), '  upload_image_to_chat_message_document  -111 $chatMessageDocumentTmpPaths::');

        $chatMessageDocumentTmpPaths[]= [
            'chat_message_document_tmp_path'=>$chat_message_document_tmp_path,
            'info'=> $info
        ];

        $request->session()->put($this->tmp_chat_message_document_session_key, $chatMessageDocumentTmpPaths);


        $chatMessageDocumentTmpPaths      = Session::get($this->tmp_chat_message_document_session_key);
        $this->debToFile(print_r($chatMessageDocumentTmpPaths, true), '  upload_image_to_chat_message_document  -222 $chatMessageDocumentTmpPaths::');

        //        $this->debToFile(print_r($chat_message_document_tmp_path, true), '  upload_image_to_chat  --10 $chat_message_document_tmp_path::');
//        $this->debToFile(print_r($dest_filename, true), '  upload_image_to_chat  --2 $dest_filename::');

        return ['message' => '', 'error_code' => 0, 'chat_id' => $chat_id, 'image' => $img_basename];
    } //public function upload_image_to_chat_message_document()


    public function delete_chat_message_document_destroy()
    {
        $request              = request();
        $chat_message_document = $request->get('chat_message_document');
//        $this->debToFile(print_r($chat_message_document, true), '  TEXT  -1 $chat_message_document::');
        $userRegistrationSettings = ChatMessageDocument::getByName('userRegistrationFiles')->first();
        $user_registration_files_str = !empty($userRegistrationSettings->value) ? $userRegistrationSettings->value : '';
        $tempUserRegistrationFiles   = array_unique($this->pregSplit('/;/', $user_registration_files_str));

        $result_user_registration_files_str= '';
//            $this->debToFile(print_r($tempUserRegistrationFiles, true), '  TEXT  -1 $tempUserRegistrationFiles::');
        foreach ($tempUserRegistrationFiles as $next_key => $next_user_registration_file) {
//                $this->debToFile(print_r($next_user_registration_file, true), '  TEXT  -1 $next_user_registration_file::');
            if ( trim($next_user_registration_file) == trim($chat_message_document) ) {
//                    $this->debToFile(print_r($next_user_registration_file, true), '  UNSET $next_user_registration_file::');
                unset($tempUserRegistrationFiles[$next_key]);

                $filename_to_delete = 'public/' . with(new ChatMessageDocument)->getUuploadsChatMessageDocumentFiles_Dir() . '/' . $chat_message_document;
//                    $this->debToFile(print_r($filename_to_delete, true), '  TEXT  -10 $filename_to_delete::');
                Storage::delete($filename_to_delete);
            } else {
                $result_user_registration_files_str.= $next_user_registration_file.';';
            }
        }


        DB::beginTransaction();
        try {

            if ($userRegistrationSettings === null) {
                $userRegistrationSettings       = new ChatMessageDocument();
                $userRegistrationSettings->name = 'userRegistrationFiles';
//                $this->debToFile(print_r($result_user_registration_files_str, true), '  TEXT  -MULL NEW $result_user_registration_files_str::');

            }
//            $this->debToFile(print_r($result_user_registration_files_str, true), '  TEXT  -19 $result_user_registration_files_str::');
            $userRegistrationSettings->value      = $result_user_registration_files_str;
            $userRegistrationSettings->updated_at = Carbon::now(config('app.timezone'));
            $userRegistrationSettings->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return ['message' => $e->getMessage(), 'error_code' => 1];
        }


        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function delete_chat_message_document_destroy

    // CHAT_MESSAGE_DOCUMENT BLOCK END

}