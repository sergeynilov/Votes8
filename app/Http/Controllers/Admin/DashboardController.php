<?php

namespace App\Http\Controllers\Admin;

use App\Download;
use App\Payment;
use App\PaymentItem;
use App\VoteItem;
use Auth;
use App\Http\Controllers\MyAppController;
use Elasticsearch\Common\Exceptions\ElasticsearchException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Artisan;
use DB;
use Spipu\Html2Pdf\Html2Pdf;

use App\Http\Traits\funcsTrait;
use App\VoteItemUsersResult;
use App\QuizQualityResult;
use App\Vote;
use App\ContactUs;
use App\User;
use App\Todo;
use App\library\AppArchiver;
use App\library\AppRssImport;
use App\library\YoutubeWrapper;
use App\ActivityLog;
use App\Facades\Instagram;
//use Monolog\Handler\ElasticSearchHandler;
//use GuzzleHttp\Ring\Client\MockHandler;

class DashboardController extends MyAppController
{
    use funcsTrait;
    private $users_tb;
    private $todos_tb;
    private $payments_tb;
    private $payment_items_tb;
    private $downloads_tb;

    public function __construct()
    {
//        Vote::bulkVotesToElastic();
//        VoteItem::bulkVoteItemsToElastic();

//        $date = Carbon::now(config('app.timezone'))->addHour();
//        $this->debToFile(print_r($date->format('Y-m-d\TH:i:s.uP T'), true), '-1:');
//        $this->debToFile(print_r($date->format('Y-m-d\\TH:i:s\\Z'), true), '-2:');
//        die("-1 XXZ");


        parent::__construct();
        $this->middleware('auth', ['except' => 'msg']);
        $this->users_tb= with(new User)->getTable();
        $this->todos_tb= with(new Todo)->getTable();

        $this->payments_tb                = with(new Payment)->getTable();
        $this->payment_items_tb           = with(new PaymentItem)->getTable();
        $this->downloads_tb               = with(new Download)->getTable();

        if ($this->isDeveloperComp()) {
//            $res= Vote::makeElasticSearch('Test');
/*            $res= Vote::setVotesElasticMapping();

            echo '<pre>$res::'.print_r($res,true).'</pre>';
            die("-1 XXZXXX");*/



//            $appRssImport= new AppRssImport();
//            $appRssImport->workRssItems(true);

//            die("-1 XXZ");
            /*            $userList= \InstagramApi::getUser();
            echo '<pre>$userList::'.print_r($userList,true).'</pre>';
                $postsList= Instagram::getPosts();
                echo '<pre>$postsList::'.print_r($postsList,true).'</pre>';
            $tagPostsList=  Instagram::getTagPosts('php');
            echo '<pre>$tagPostsList::'.print_r($tagPostsList,true).'</pre>';
die("-1 XXZ");*/


//            public function checkRegexpHttpPrefix($str) {
//            $ret= $this->checkRegexpHttpPrefix('HTtP://whatsyourneed..com');
//            echo '<pre>$ret::'.print_r($ret,true).'</pre>';
//            die("-1 XXZ");
            // https://laracasts.com/discuss/channels/tips/html2pdf-in-laravel

/*            $html2pdf = new Html2Pdf();
            $html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test');
//            $html2pdf->output();

            $pdf= $html2pdf->Output('', 'S'); // The filename is ignored when you use 'S' as the second parameter.
                echo '<pre>$pdf::'.print_r($pdf,true).'</pre>';
            return response($pdf)
                       ->header('Content-Type', 'application/pdf')
                       ->header('Content-Length', strlen($pdf))
                       ->header('Content-Disposition', 'inline; filename="example.pdf"');*/

//            $this->clearDirectoryByPeriod("tmp",24);



/*            $appArchiver = new AppArchiver();
            $ret= $appArchiver->archiveUserRegistrationFiles();
            echo '<pre>$ret::'.print_r($ret,true).'</pre>';
//            die("-1 XXZ=====");
            return ;*/


//            $client5 = Elasticsearch\ClientBuilder::create()->build();
//            echo '<pre>$client5::'.print_r($client5,true).'</pre>';

//            $accounts = Account::search($request->input('search'))->get();
//            $data= Vote::search("movie")->get();
//            echo '<pre>$data::'.print_r($data,true).'</pre>';
//            die("-1 XXZ");

            /*            $handler = new MockHandler([
                            'status' => 200,
                            'transfer_stats' => [
                                'total_time' => 100
                            ],
                            'body' => fopen('somefile.json')
                        ]);
                        $builder = ClientBuilder::create();
                        $builder->setHosts(['somehost']);
                        $builder->setHandler($handler);
                        $client = $builder->build();*/
            
//            $client= \Elasticsearch\ClientBuilder::create()->build();
//            $results= $client->search([
//                'index'=> 'man',
//                'body'=> [
//                    'query'=>[
//                        'match'=>[
//                            '_all'=>'design'
//                        ]
//                    ]
//                ]
//            ]);
//            echo '<pre>$results::'.print_r($results,true).'</pre>';

            return;
            $query = [
                'multi_match' => [
                    'query'  => $search,
                    'fields' => ['title^3', 'content'],
                ],
            ];

            $parameters = [
                'index' => 'blog',
                'type'  => 'post',
                'body'  => [
                    'query' => $query
                ]
            ];

            $response = $elastic->search($parameters);
        }

//        if ( ! $this->isDeveloperComp()) {
//            $this->sendTestEmail();
//        }

//        $val= $this->getCFFormattedDate( Carbon::createFromFormat('Y-m-d', '2018-10-10') );
//        $val= $this->getCFFormattedDate( Carbon::createFromFormat('Y-m-d', '2018-10-10') );
//        echo '<pre>$val::'.print_r($val,true).'</pre>';
//        die("-1 XXZ");

//        $appCronTasks = new AppCronTasks();
//        $appCronTasks->notifyNewContactUs(true);
//
//        $appCronTasks = new AppCronTasks();
//        $appCronTasks->notifyNewUsers(true);
//        die("-1 XXZ");
    }


    /* Dashboard page */


    public function index(Request $request)
    {

        if ($this->isDeveloperComp()) {
/*
            $elasticSearch = \Elasticsearch\ClientBuilder::create()->build();
            echo '<pre>$elasticSearch::' . print_r($elasticSearch, true) . '</pre>';
            $elasticSearchConfigData= [
                'host' => 'localhost',
                'port' => 9200,
                'index' => 'pets',
            ];

            $elasticSearchClient= new ElasticaClient();
            echo '<pre>$elasticSearchClient::'.print_r($elasticSearchClient,true).'</pre>';*/
        }
//        return;
//        return view( $this->getCurrentAdminTemplate().'.dashboard.dashboard', $commonVarsArray );

        
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);
//        echo '<pre>$viewParamsArray::'.print_r($viewParamsArray,true).'</pre>';
//        $viewParamsArray['siteSubscriptionStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Active- '],
//            SiteSubscription::getSiteSubscriptionActiveValueArray(false));

        $newVotes                               = Vote::getByStatus('N')->get();
        $newContactUs                           = ContactUs::getByAccepted('N')->get();
        $viewParamsArray['newVotes']            = $newVotes;
        $viewParamsArray['newContactUs']        = $newContactUs;
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.dashboard.index', $viewParamsArray);

    }



    public function show_logged_user_info()
    {
        $site_home_url     = \URL::to('/');
        $loggedUser         = Auth::user();
        $opened_todo_count  = Todo
            ::getByCompleted('0')
            ->getByForUserId($loggedUser->id)
            ->count();
        return \Response::json(['opened_todo_count' => $opened_todo_count, 'logged_user_id'=> $loggedUser->id, 'site_home_url'=> $site_home_url, 'error_code' => 0, 'message' => '']);
    }

    public function logout()
    {
        $request = request();
        $request->session()->flush();
        Auth::logout();

//        die("-1 XXZ logout -1 : ");
        return redirect('/login');
    }

    public function php_info()
    {
        phpinfo();
    }

    public function get_system_alerts()
    {

        $commonVarsArray = $this->addTemplateVars(compact([
            'active_products_count',
            'pending_products_count',
            'new_users_count',
            'new_invoice_orders_count',
            'processing_orders_count'
        ]));
//            echo '<pre>$processing_orders_count::'.print_r($processing_orders_count,true).'</pre>';
//
//            die("-1 XXZ");
        /*                if ( $new_invoice_orders_count > 0 ) {
                            $alert_text .= '<a href="' . base_url() . 'admin/order/index/page/1/filter_status/I"><b>' . $new_invoice_orders_count . '</b> New Invoice Order(s)</a><br>';
                        }
                        if ( $processing_orders_count > 0 ) {
                            $alert_text .= '<a href="' . base_url() . 'admin/order/index/page/1/filter_status/P"><b>' . $processing_orders_count . '</b> Processing Order(s)</a><br>';
                        }*/
        /*        if ( $active_products_count > 0 ) {
                    $alert_text .= '<a href="' . $this->getBackendHome() . '/admin/product/index?filter_status=A"><b>' . $active_products_count . '</b> Active Product(s)</a>&nbsp;<br>';
                }
                if ( $pending_products_count > 0 ) {
                    $alert_text .= '<a href="' . $this->getBackendHome() . '/admin/product/index?filter_status=P"><b>' . $pending_products_count . '</b> Pending Product(s)</a>&nbsp;<br>';
                }

                if ( $new_invoice_orders_count > 0 ) {
                    $alert_text .= '<a href="' . $this->getBackendHome() . '/admin/order/index?filter_status=I"><b>' . $new_invoice_orders_count . '</b> New invoice orders(s)</a>&nbsp;<br>';
                }
                if ( $processing_orders_count > 0 ) {
                    $alert_text .= '<a href="' . $this->getBackendHome() . '/admin/order/index?filter_status=P"><b>' . $processing_orders_count . '</b> Processing orders(s)</a>&nbsp;<br>';
                }


                if ( $new_users_count > 0 ) {
                    $alert_text .= '<a href="' . $this->getBackendHome() . '/admin/user/index?filter_active_status=N"><b>' . $new_users_count . '</b> New User(s)</a>&nbsp;<br>';
                }*/

        $html = view($this->getCurrentAdminTemplate() . '.dashboard.get_system_alerts', $commonVarsArray)->render();

        return \Response::json(['html' => $html, 'error_code' => 0, 'message' => '']);
    }


    public function get_system_info()
    {
        try {
            $system_info = $this->getSystemInfo();
        } catch (Exception $e) {
            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'src_file' => null, 'npm_output_listing' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => '', 'system_info' => $system_info], HTTP_RESPONSE_OK);
    } //public function get_system_info()

    public function get_cms_item_by_alias()
    {
        $alias   = $this->getParameter('alias');
        $cmsItem = CmsItem::getSimilarCmsItemByAlias($alias);

        if (empty($cmsItem)) {
            return \Response::json(['error_code' => 1, 'message' => 'Alias ' . $alias . ' not found!', 'alias' => $alias]);
        }

        $cmsItemArray                       = $cmsItem->toArray();
        $cmsItemArray['page_type_label']    = CmsItem::getCmsItemPageTypeLabel($cmsItemArray['page_type']);
        $cmsItemArray['page_content_label'] = CmsItem::getCmsItemContentTypeLabel($cmsItemArray['content_type']);
        $cmsItemArray['published_label']    = CmsItem::getCmsItemPublishedLabel($cmsItemArray['published']);

        return \Response::json(['error_code' => 0, 'message' => '', 'alias' => $alias, 'cmsItemArray' => $cmsItemArray]);
    }


    public function msg()
    {
        $viewParamsArray         = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['text'] = session()->get('text');
//        if ( empty($viewParamsArray['text']) ) {
//            $viewParamsArray['text'] = 'Testing Backend text ';
//        }

        if (empty($viewParamsArray['text'])) {
            return redirect()->route('admin.dashboard');
        }
        $viewParamsArray['action']              = session()->get('action');
        $viewParamsArray['type']                = session()->get('type');
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

//        echo '<pre>$viewParamsArray::'.print_r($viewParamsArray,true).'</pre>';
        return view($this->getBackendTemplateName() . '.admin.dashboard.msg', $viewParamsArray);
    }
    

    public function refresh_demo_data()
    {
        $exitCode = Artisan::call('migrate:refresh', [
            '--seed' => true,
        ]);

        return response()->json(['error_code' => 1, 'message' => "", 'exitCode' => $exitCode], HTTP_RESPONSE_OK);
    }


    public function get_payment_items_rows()
    {
        $ref_items_per_pagination = 10;

        $request               = request();
        $page                  = $request->input('page');
        $filter_download_id    = $request->input('filter_download_id');
        $filter_user_id        = $request->input('filter_user_id');

        $total_rows            = PaymentItem
            ::getByUserId($filter_user_id)
            ->join($this->payments_tb, $this->payments_tb . '.id', '=', $this->payment_items_tb . '.payment_id')
            ->getByItemId($filter_download_id)
            ->count();

        $paymentItems        = PaymentItem
            ::getByStatus('C', 'payments')
            ->getByItemId($filter_download_id)
            ->getByUserId($filter_user_id)
            ->select(
                $this->payment_items_tb . '.*',
                $this->downloads_tb . '.title as payed_item_title',
                $this->users_tb     . '.username as payment_username',
                $this->payments_tb . '.payment_type',
                $this->payments_tb . '.invoice_number',
                $this->payments_tb . '.payment_description',
                $this->payments_tb . '.payer_email',
                $this->payments_tb . '.payer_first_name',
                $this->payments_tb . '.payer_last_name',
                $this->payments_tb . '.shipping',
                $this->payments_tb . '.tax',
                $this->payments_tb . '.payer_shipping_address'
            )
            ->orderBy( 'payed_item_title', 'asc')
            ->orderBy($this->payment_items_tb . '.created_at', 'desc')
            ->join($this->payments_tb, $this->payments_tb . '.id', '=', $this->payment_items_tb . '.payment_id')
            ->join($this->users_tb, $this->users_tb . '.id', '=', $this->payments_tb . '.user_id')
            ->join($this->downloads_tb, $this->downloads_tb . '.id', '=', $this->payment_items_tb . '.item_id')
            ->paginate($ref_items_per_pagination, null, null, $page)
            ->onEachSide((int)($ref_items_per_pagination / 2));

        $viewParamsArray                    = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['paymentItems']    = $paymentItems;
        $viewParamsArray['total_rows']      = $total_rows;
        $viewParamsArray['filter_download_id']  = $filter_download_id;
        $viewParamsArray['filter_user_id']      = $filter_user_id;
        $viewParamsArray['downloadsValueArray']      = $this->SetArrayHeader(['' => ' -All Items- '], Download::getDownloadsSelectionArray());
        $viewParamsArray['usersSelectionArray']      = $this->SetArrayHeader(['' => ' -All Users- '], User::getUsersSelectionArray());
        $html                               = view($this->getBackendTemplateName() . '.admin.dashboard.payments_rows', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_payment_items_rows()




    public function get_activity_log_rows($page)
    {
        $ref_items_per_pagination = 10;
        $total_rows     = ActivityLog::count();

        $activityLogRows = ActivityLog
            ::orderBy('created_at', 'desc')
            ->select( 'activity_log.*' )
            ->paginate($ref_items_per_pagination, null, null, $page)
            ->onEachSide((int)($ref_items_per_pagination / 2));

        $viewParamsArray                    = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['activityLogRows'] = $activityLogRows;
        $viewParamsArray['total_rows']      = $total_rows;
        $html                               = view($this->getBackendTemplateName() . '.admin.dashboard.activity_log_rows', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_activity_log_rows()



    public function clear_activity_log_rows()
    {

        DB::beginTransaction();
        try {
            ActivityLog::truncate();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } // public function clear_activity_log_rows()

    public function test(Request $request)
    {

        try {
//            $youtubeWrapper= new YoutubeWrapper();
//            $youtubeWrapper->getVideosList(true, false);
//            $youtubeWrapper->getVideoInfo(true, false);


            $loggedUser             = Auth::user();
            $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);

            $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
            $viewParamsArray['loggedUser']          = $loggedUser;

            return view($this->getBackendTemplateName() . '.admin.dashboard.test', $viewParamsArray);

        } catch (\Exception $e) {
            echo '<pre>$e->getMessage()::'.print_r($e->getMessage(),true).'</pre>';
        }


    }


    // TODO'S BLOCK START
    public function todo_container_page()
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);
//        echo '<pre>$viewParamsArray::'.print_r($viewParamsArray,true).'</pre>';
//        $viewParamsArray['siteSubscriptionStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Active- '],
//            SiteSubscription::getSiteSubscriptionActiveValueArray(false));

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
        return view($this->getBackendTemplateName() . '.admin.dashboard.todo_container_page', $viewParamsArray);

    }

    public function show_todo_page()
    {
        $request               = request();
        $filter_completed      = $request->input('filter_completed');

        $this->users_tb= with(new User)->getTable();
        $this->todos_tb= with(new Todo)->getTable();

        $todosResults = Todo
            ::getByCompleted($filter_completed)
            ->orderBy('completed', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
        $viewParamsArray                            = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['todosResults']            = $todosResults;
        $viewParamsArray['todos_count']             = count($todosResults);
        $viewParamsArray['todoUserValueArray']      = $this->SetArrayHeader(['' => ' -Select User- '], User::getUsersSelectionArray(false));

        $viewParamsArray['todoCompletedValueArray'] = $this->SetArrayHeader(['' => ' -Select Completed- '], Todo::getTodoCompletedValueArray(false));
        $viewParamsArray['todoPrioritiesValueArray'] = $this->SetArrayHeader(['' => ' -Select Priority- '], Todo::getTodoPrioritiesValueArray(false));
        $html                           = view($this->getBackendTemplateName() . '.admin.dashboard.todo_page', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
        $html                           = view($this->getBackendTemplateName() . '.admin.dashboard.todo_page', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function show_todo_page(Request $request)

    public function save_todo_page()
    {
        $request= request();
        $requestData= $request->all();
//        $this->debToFile(print_r( $requestData,true),'  TEXT  -1 $requestData::');
        $todoList= !empty($requestData['todoList']) ? $requestData['todoList'] : [];

        DB::beginTransaction();
        try {
            foreach( $todoList as $nextTodo ) {
//                $this->debToFile(print_r( $nextTodo,true),'  TEXT  -1 $nextTodo::');
                if ( empty($nextTodo['todo_id']) ) {
                    $todo= new Todo();
                } else {
                    $todo= Todo::find($nextTodo['todo_id']);
                }
                $todo->text= $nextTodo['todo_text'];
                $todo->priority= $nextTodo['todo_priority'];
                $todo->completed= $nextTodo['todo_completed'];
                $todo->for_user_id= $nextTodo['todo_foruserid'];
                $todo->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error_code' => 1, 'message' => $e->getMessage() ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Your modifications were applied !', 'error_code' => 0], HTTP_RESPONSE_OK);
    } // public function save_todo_page()

    /* delete todo item */
    public function delete_todo(Request $request)
    {
        $todo_id  = $request->get('todo_id');
        $todo = Todo::find($todo_id);

        if ($todo === null) {
            return response()->json(['error_code' => 11, 'message' => 'Todo # "' . $todo_id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $todo->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function delete_todo(Request $request)

    // TODO'S BLOCK END

}
