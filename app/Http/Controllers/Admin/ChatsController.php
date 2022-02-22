<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MyAppController;
use App\Chat;
use DB;
use Auth;
use Carbon\Carbon;
use App\User;
use App\ChatMessage;
use App\ChatParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\ChatRequest;

class ChatsController extends MyAppController
{
    use funcsTrait;
    private $users_tb;
    private $chats_tb;
    private $chat_messages_tb;

    public function __construct()
    {
        parent::__construct();
        $this->chats_tb= with(new Chat)->getTable();
        $this->users_tb= with(new User)->getTable();
        $this->chat_messages_tb= with(new ChatMessage())->getTable();
    }

    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                                 = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['chatStatusValueArray']         = $this->SetArrayHeader(['' => ' -Select status- '], Chat::getChatStatusValueArray(false));
        $viewParamsArray['appParamsForJSArray']          = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.chat.index', $viewParamsArray);
    }

    public function get_chats_dt_listing()
    {
        $request = request();

        $filter_name   = $request->input('filter_name', '');
        $filter_status = $request->input('filter_status', '');

        $chatsCollection = Chat
            ::getByName($filter_name, true)
            ->getByStatus($filter_status, true)
            ->get();


        foreach( $chatsCollection as $next_key=> $nextChat ) {
            $chatsCollection[$next_key]->slashed_name= addslashes($nextChat->name);
        }


        return Datatables
            ::of($chatsCollection)
            ->editColumn('status', function ($chat) {
                if ( ! isset($chat->status)) {
                    return '';
                }
                return Chat::getChatStatusLabel($chat->status);
            })
            ->setRowClass(function ($chat) {
                return ( $chat->status != 'A' )? 'row_inactive_status' : '';
            })

            ->editColumn('description', function ($contactUs) {
                if ( ! isset($contactUs->description)) {
                    return '';
                }
                return $this->concatStr($contactUs->description);
            })

            ->editColumn('creator_id', function ($chat) {
                if ( ! isset($chat->creator_id)) {
                    return '';
                }
                $creator= User::find($chat->creator_id);
                if ( !empty($creator) ) {
                    return $creator->username;
                }
                return '';
            })

            ->editColumn('created_at', function ($chat) {
                if (empty($chat->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($chat->created_at);
            })
            ->editColumn('updated_at', function ($chat) {
                if (empty($chat->updated_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($chat->updated_at);
            })
            ->editColumn('action', '<a href="/admin/chats/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendChat.deleteChat({{$id}},\'{{$slashed_name}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function create()
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['chatStatusValueArray']         = $this->SetArrayHeader(['' => ' -Select Status- '], Chat::getChatStatusValueArray(false));
        $viewParamsArray['appParamsForJSArray']                  = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.chat.create', $viewParamsArray);
    }

    public function store(ChatRequest $request)
    {
        $loggedUser             = Auth::user();
        $chat                   = new Chat();
        $chat->name             = $request->get('name');
        $chat->status           = $request->get('status');
        $chat->description      = $request->get('description');
        $chat->creator_id       = $loggedUser->id;
        DB::beginTransaction();
        try {
            $chat->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status'), 'description' => $request->get('description')]);
        }
        $this->setFlashMessage('Chat created successfully !', 'success', 'Backend');

        return redirect()->route('admin.chats.index');
    }

    public function edit($chat_id)
    {
        $chatStatusValueArray         = $this->SetArrayHeader(['' => ' -Select Status- '], Chat::getChatStatusValueArray(false));
        $chat                         = Chat::find($chat_id);
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        if ($chat === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg',
                ['text' => 'Chat with id # "' . $chat_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $chatCreator                  = User::find($chat->creator_id);

        $viewParamsArray['chat']      = $chat;
        $viewParamsArray['chatCreator']    = $chatCreator;
        $appParamsForJSArray['id']    = $chat_id;
        $viewParamsArray['chatStatusValueArray']= $chatStatusValueArray;
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.chat.edit', $viewParamsArray);
    }

    public function update(ChatRequest $request, int $chat_id)
    {
        $viewParamsArray  = $this->getAppParameters(true, ['csrf_token']);
        $chat = Chat::find($chat_id);
        if ($chat === null) {
            $viewParamsArray['text'] = 'Chat with id # "' . $chat_id . '" not found !';
            $viewParamsArray['type'] = 'danger';
            return View( $this->getBackendTemplateName() . '.admin.dashboard.msg', $viewParamsArray );
        }
        $chat->name       = $request->get('name');
        $chat->status     = $request->get('status');
        $chat->updated_at = Carbon::now(config('app.timezone'));
        DB::beginTransaction();
        try {
            $chat->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Chat updated successfully !', 'success', 'Backend');

        return redirect()->route('admin.chats.index');
    }


    /* delete item with related Chat messages */
    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $chat = Chat::find($id);
        if ($chat === null) {
            return response()->json(['error_code' => 11, 'message' => 'Chat # "' . $id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $chat->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    public function get_chat_details_info(int $chat_id)
    {
        $viewParamsArray                     = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['chat_id']          = $chat_id;
        $html                                = view($this->getBackendTemplateName() . '.admin.chat.chat_details_info', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_chat_details_info(Request $request)



    // CHAT'S PARTICIPANTS BLOCK BEGIN
    public function get_chat_related_chat_participants(int $chat_id) ////  Route::get('/get_chat_related_chat_participants/{user_id}',  'Admin\ChatsController@get_ chat_related_chat_ participants') ->name('admin_get_chat_related_chat_participants');
    {

        $usersList= [];
        $tempUsers          = User
            ::select( $this->users_tb.'.*', \DB::raw( 'count('.DB::getTablePrefix().$this->chat_messages_tb.'.id) as messages_count' ), \DB::raw( 'sum(if('.DB::getTablePrefix().$this->chat_messages_tb.'.chat_id='.$chat_id.',1,0)) as 
        chat_messages_count' ) )
            ->orderBy('username', 'asc')
            ->groupBy($this->users_tb.'.id')
            ->leftJoin( $this->chat_messages_tb, $this->chat_messages_tb.'.user_id', '=', $this->users_tb.'.id')
            ->get();

        /*        $tempUsers= Chat
                    ::select( \DB::raw( ' chats.*, count(cm.id) as messages_count, sum(if(cm.chat_id='.$chat_id.',1,0)) as user_messages_count' ) )
        //            ->orderBy('username', 'asc')
                    ->leftJoin( \DB::raw('chat_messages as cm'), \DB::raw('cm.chat_id'), '=', \DB::raw('chats.id') )
        //            ->whereRaw(with(new User)->getTable().'.id <= 7')
                    ->get();
        //        $tempUsers= User
        //            ::orderBy('username', 'asc')
        //            ->whereRaw(with(new User)->getTable().'.id <= 7')
        //            ->get();*/

        $chatParticipants= ChatParticipant
            ::getByChat($chat_id)
            ->orderBy('status', 'desc')
            ->get();

        foreach( $tempUsers as $next_key=>$nextUser ) {
            $is_chat_selected = false;
            $selected_user_status  = '';
            $selected_user_status_label  = '';
            foreach( $chatParticipants as $nextUserParticipant ) {
                if ( (int)$nextUserParticipant->user_id == (int)$nextUser->id ) {
                    $is_chat_selected= true;
                    $selected_user_status= $nextUserParticipant->status;
                    $selected_user_status_label= ChatParticipant::getChatParticipantParticipantTypeLabel($nextUserParticipant->status);
                    break;
                }
            }
            $usersList[]= [
                'id'                  => $nextUser->id,
                'username'            => $nextUser->username,
                'full_name'           => $nextUser->full_name,
                'email'               => $nextUser->email,
                'messages_count'      => $nextUser->messages_count,
                'chat_messages_count' => $nextUser->chat_messages_count,
                'status'              => $nextUser->status,
                'created_at'          => $nextUser->created_at,
                'user_in_chat'        => $is_chat_selected,
                'selected_user_status' => $selected_user_status,
                'selected_user_status_label' => $selected_user_status_label,
            ];
        }
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['chat_id']   = $chat_id;
        $viewParamsArray['usersList'] = $usersList;
        $viewParamsArray['selected_chats_count']  = count($chatParticipants);
        $html                         = view($this->getBackendTemplateName() . '.admin.chat.related_chat_participants', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_chat_related_chat_participants()


    public function attach_chat_participant_to_chat(Request $request)
    {
        $user_id  = $request->get('user_id');
        $chat_id  = $request->get('chat_id');
        $status  = $request->get('status');

        $user = User::find($user_id);
        if ($user === null) {
            return response()->json(['error_code' => 11, 'message' => 'User # "' . $user_id . '" not found!', 'user' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        $chat = ChatParticipant::find($chat_id);
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
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'user' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } // public function attach_chat_participant_to_chat($chat_id, $chat_id, $status)

    // backendUser.prototype.clearChatParticipantOfUser = function (chat_id, chat_name) {

    public function clear_chat_participant_from_chat(Request $request)
    {
        $user_id  = $request->get('user_id');
        $chat_id  = $request->get('chat_id');
        $user = User::find($user_id);
        if ($user === null) {
            return response()->json(['error_code' => 11, 'message' => 'User # "' . $user_id . '" not found!', 'user' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $chat = ChatParticipant::find($chat_id);
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

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'user' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function clear_chat_participant_from_chat($user_id, $chat_id)

    // CHAT'S PARTICIPANTS BLOCK END


}
