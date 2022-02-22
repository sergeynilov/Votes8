<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Settings;
use App\User;
use App\Vote;
use App\Download;
use App\Todo;
use App\Banner;


use App\VoteCategory;
use App\ChatParticipant;
use App\Mail\SendgridMail;
use App\UsersSiteSubscription;
use App\Http\Traits\funcsTrait;
use Spipu\Html2Pdf\Html2Pdf;
use App\library\CheckValueType;

class HomeController extends MyAppController
{
    use funcsTrait;
    private $votes_tb;
    private $vote_categories_tb;

    public function __construct()
    {
        $this->votes_tb= with(new Vote)->getTable();
        $this->vote_categories_tb= with(new VoteCategory())->getTable();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() // home page
    {


        $viewParamsArray          = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        with(new self)->info( $viewParamsArray,'$viewParamsArray::' );
        $home_page_ref_items_per_pagination = Settings::getValue('home_page_ref_items_per_pagination', CheckValueType::cvtInteger, 20);

        $activeQuizVotes = Vote
            ::getByStatus('A')
            ->getByIsHomepage(true)
            ->getByIsQuiz(true)
            ->orderBy('ordering', 'desc')
            ->orderBy('created_at', 'desc')
            ->leftJoin( "vote_categories", 'vote_categories.id', '=', 'votes.vote_category_id' )
            ->select( "votes.*", "vote_categories.name as vote_category_name", "vote_categories.slug as vote_category_slug" )
            ->paginate($home_page_ref_items_per_pagination)
            ->onEachSide((int)($home_page_ref_items_per_pagination / 2));

        foreach ($activeQuizVotes as $next_key => $nextVote) {
            $voteImageProps = Vote::setVoteImageProps($nextVote->id, $nextVote->image, false);
            if (count($voteImageProps) > 0) {
                $nextVote->setVoteImagePropsAttribute($voteImageProps);
            }
        }
        $viewParamsArray['activeQuizVotes'] = $activeQuizVotes;

        $activeNonQuizVotes = Vote
            ::getByStatus('A')
//            ->whereRaw($this->votes_tb.'.id =  6')

            ->getByIsHomepage(true)
            ->getByIsQuiz(false)
            ->orderBy('ordering', 'desc')
            ->orderBy('created_at', 'desc')
            ->leftJoin( $this->vote_categories_tb, $this->vote_categories_tb.'.id', '=', $this->votes_tb.'.vote_category_id' )
            ->select( $this->votes_tb.".*", $this->vote_categories_tb.".name as vote_category_name", $this->vote_categories_tb.".slug as vote_category_slug")
            ->get();
        foreach ($activeNonQuizVotes as $next_key => $nextVote) {
            $voteImageProps = Vote::setVoteImageProps($nextVote->id, $nextVote->image, false);
            if (count($voteImageProps) > 0) {
                $nextVote->setVoteImagePropsAttribute($voteImageProps);
            }
        }
        $viewParamsArray['activeNonQuizVotes'] = $activeNonQuizVotes;
        $viewParamsArray['appParamsForJSArray']              = json_encode($appParamsForJSArray);
        return view($this->getFrontendTemplateName() . '.home', $viewParamsArray);
    } // public function index() // home page

    public function msg()
    {
        $viewParamsArray                        = $appParamsForJSArray = $this->getAppParameters(false, []);
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
        $viewParamsArray['text']                = session()->get('text');
        if ( empty($viewParamsArray['text']) ) {
            $viewParamsArray['text'] = 'Testing Frontend text ';
        }

        if (empty($viewParamsArray['text'])) {
            return redirect()->route('home');
        }
        $viewParamsArray['action'] = session()->get('action');
        $viewParamsArray['type']   = session()->get('type');

        return view($this->getFrontendTemplateName() . '.msg', $viewParamsArray);
    }


    public function public_profile_view($user_id)
    {
        $viewParams = $appParamsForJSArray = $this->getAppParameters(false, ['csrf_token', 'site_name', 'site_heading', 'site_subheading', 'account_register_details_text']);
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');



        $publicUserProfile         = User::find($user_id);
        if ($publicUserProfile === null) {
            return View($this->getFrontendTemplateName() . '.msg', ['text' => 'User with id # "' . $user_id . '" not found !', 'type' => 'danger', 'action' => ''], $viewParams);
        }


        $viewParams['userProfile'] = $publicUserProfile;
        $profileUserSiteSubscriptions = UsersSiteSubscription
            ::getByUserId($publicUserProfile->id)
            ->getByActive(true, 'site_subscriptions')
            ->select( "users_site_subscriptions.*", 'site_subscriptions.name as site_subscription_name', 'site_subscriptions.vote_category_id',
                $this->vote_categories_tb.".name as vote_category_name" )
            ->leftJoin( "site_subscriptions", "site_subscriptions.id", "=", "users_site_subscriptions.site_subscription_id" )
            ->leftJoin( $this->vote_categories_tb, $this->vote_categories_tb.'.id', '=', 'site_subscriptions.vote_category_id')
            ->orderBy('site_subscription_name', 'asc')
            ->get();
        $fullPhotoData = User::setUserFullPhotoProps($publicUserProfile->id, $publicUserProfile->full_photo, true);

        $publicUserChatParticipants= ChatParticipant
            ::getByUser($user_id)
            ->select( "chat_participants.*", "chats.name as chat_name", "chats.description as chat_description", "chats.status as chat_status" )
            ->leftJoin( 'chats', 'chats.id', '=', 'chat_participants.chat_id')
            ->orderBy('chat_name', 'desc')
            ->get();

        $openedTodos  = [];
        $tempOpenedTodos  = Todo
            ::getByCompleted('0')
            ->getByForUserId($user_id)
            ->orderBy('completed', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach( $tempOpenedTodos as $next_key=>$nextTempOpenedTodo ) {
            $openedTodos[]= [
                'id'       => $nextTempOpenedTodo->id,
                'text'       => $nextTempOpenedTodo->text,
                'priority'       => $nextTempOpenedTodo->priority,
                'priority_label' => Todo::getTodoPriorityLabel($nextTempOpenedTodo->priority)
            ];
        }
        $viewParams['openedTodos']                   = $openedTodos;
        $viewParams['publicUserProfile']             = $publicUserProfile;
        $viewParams['profileUserSiteSubscriptions']  = $profileUserSiteSubscriptions;
//        $viewParams['avatarData'] = $avatarData;
        $viewParams['fullPhotoData']                 = $fullPhotoData;
        $viewParams['publicUserChatParticipants']    = $publicUserChatParticipants;

//        echo '<pre>public_profile_view $user_id::'.print_r($user_id,true).'</pre>';
//        echo '<pre>$viewParams::'.print_r($viewParams,true).'</pre>';


        return view($this->getFrontendTemplateName() . '.public_profile_view', $viewParams);
    }


    public function get_logged_user()
    {
        if ( ! Auth::check()) {
            return response()->json([
                'error_code'   => 1,
                'message'      => "Not authenticated",
            ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        $loggedUser = Auth::user();
        return response()->json(['error_code' => 0, 'message' => '', 'loggedUser'=>$loggedUser], HTTP_RESPONSE_OK);
    }
    
    public function logout()
    {
        $request = request();
        $request->session()->flush();
        Auth::logout();
        return redirect('/login');
    }


    // Route::get('/show_banner_image/{banner_id}/{banner_text}/{banner_url}/{banner_view_type}', ['as' => 'site.viewImage', 'uses' => 'HomeController@test2']); //app/Http/Controllers/HomeController.php
    public function generate_banner_image($banner_id, $banner_text, $banner_logo, $banner_short_descr, $banner_view_type)
    {  // example : https://laracasts.com/discuss/channels/laravel/how-i-can-do-dynamic-banner?page=0

        $text_color= '#151515';
        $bannersBackgroundImage   = config('app.bannersBackgroundImage');
        $banner_text= urldecode($banner_text);
        $banner_short_descr= urldecode($banner_short_descr);
        $background_image= 'banners/blue_medium_1.png';

        if ( !empty($bannersBackgroundImage) ) {
            foreach( $bannersBackgroundImage as $next_key=>$nextBannerBackgroundImage ) {
                if ( (int)$nextBannerBackgroundImage['view_type'] == (int)$banner_view_type ) {
                    $text_color= $nextBannerBackgroundImage['text_color'];
//                    $shadow_text_color= $nextBannerBackgroundImage['shadow_text_color'];
                    $background_image= $nextBannerBackgroundImage['background_image'];
                    break;
                }
            }
        }
//        $this->debToFile(print_r($background_image,true),' generate_banner_image  -1 $background_image::');
//        $this->debToFile(print_r($text_color,true),' generate_banner_image  -2 $text_color::');

        $img = \Image::make( public_path($background_image) );
        $pageContentImageProps = Banner::setBannerLogoProps($banner_id, $banner_logo, true);
        if (count($pageContentImageProps) > 0) {
            $watermark = \Image::make( storage_path() . '/app/public/' . $pageContentImageProps['image_path'] );
            $img->insert($watermark, 'top-right', 15, 30);
        }

        $img->text($banner_text, 20 /* x */, 60 /* y */, function ($font) use($text_color){
//            $font->file( public_path('fonts/roboto/Roboto_regular.ttf') );
            $font->file( public_path('fonts/Shadowed/GrutchShaded.ttf') ); //file:///_wwwroot/lar/votes/public/fonts/Shadowed/GrutchShaded.ttf
            $font->size(54);
            $font->color($text_color);
        });

//        $img->text($banner_text, 23 /* x */, 63 /* y */, function ($font) use($shadow_text_color){
//            $font->file( public_path('fonts/roboto/Roboto_regular.ttf') );
//            $font->size(54);
//            $font->color($shadow_text_color);
//        });

        if ( !empty($banner_short_descr) ) {
            $img->text($banner_short_descr, 20 /* x */, 150 /* y */, function ($font) use ($text_color) {
                $font->file(public_path('fonts/roboto/Roboto_regular.ttf'));
                $font->size(24);
                $font->color($text_color);
            });
        }

        $response = $img->psrResponse('png', 100);
//        var_dump($response);
        return $response;

    }
    public function test(Request $request)
    {

//        $request = request();

        $requestData= $request->all();
        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
        if ( !empty($requestData['input_form_datetime']) ) {
            echo '<pre>$requestData[\'input_form_datetime\']::'.print_r(  Carbon::parse($requestData['input_form_datetime'])->toRfc3339String()  ,true).'</pre>';
        }
        //             $startDateTime = Carbon::parse($request->start_date)->toRfc3339String();

        $viewParamsArray                        = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        $pdfGenerationOptions                   = config('app.pdfGenerationOptions');
        $viewParamsArray['fontsList']           = !empty($pdfGenerationOptions['fontsList']) ? $pdfGenerationOptions['fontsList'] : [];
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
        $viewParamsArray['usersArray']               = $this->SetArrayHeader(['' => ' -Select User- '], User::getUsersSelectionArray());
//        $viewParamsArray['downloadsList']     = $downloadsList;
        return view($this->getFrontendTemplateName() . '.test', $viewParamsArray);
    }



    public function test_generate_pdf_by_content_test()
    {
        $request = request();

        $requestData= $request->all();
        $is_debug= false;
        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
        /*         <input type="hidden" id="pdf_content" name="pdf_content" value="">
        <input type="hidden" id="pdf_filename" name="pdf_filename" value="">
        <input type="hidden" id="option_output_file_format" name="option_output_file_format" value="">
 */
        $pdf_content= !empty($requestData['pdf_content']) ? $requestData['pdf_content'] : '';
        echo '<pre>$pdf_filename::'.print_r($pdf_filename,true).'</pre>';

        $pdf_filename= !empty($requestData['pdf_filename']) ? $requestData['pdf_filename'] : 'file';
        echo '<pre>$pdf_filename::'.print_r($pdf_filename,true).'</pre>';

        $pdf_filename= !empty($requestData['pdf_filename']) ? $requestData['pdf_filename'] : 'file';
        echo '<pre>$pdf_filename::'.print_r($pdf_filename,true).'</pre>';

        die("-1 XXZ876");
        Browsershot::html('<h1>Hello world!!</h1>')->save('example.pdf');
        return;

//        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
//        data      : { "filename": "profile_filename.pdf", "text": $("#div_profile_content").html(), "_token": this_csrf_token },
//
//        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';

        $selected_font_name_key= !empty($requestData['selected_font_name_key']) ? $requestData['selected_font_name_key'] : '';
        $pdf_content= !empty($requestData['pdf_content']) ? $requestData['pdf_content'] : '';

        $pdf_filename= !empty($requestData['pdf_filename']) ? $requestData['pdf_filename'] : '';

        $cbx_attach_font_file= !empty($requestData['cbx_attach_font_file']) ? $requestData['cbx_attach_font_file'] : '';
        if ($is_debug) {
            echo '<pre>$selected_font_name_key::'.print_r($selected_font_name_key,true).'</pre>';
            echo '<pre>$pdf_content::'.print_r($pdf_content,true).'</pre>';
            echo '<pre>$pdf_filename::'.print_r($pdf_filename,true).'</pre>';
            echo '<pre>$cbx_attach_font_file::' . print_r($cbx_attach_font_file, true) . '</pre>';
            die("-1 XXZ55");
        }

        $page_orientation = 'P';/* P or Portrait (default) L or Landscape */
        $page_format = 'A4'; // A4  A6

        $page_lang = 'en';
        $page_unicode = true;
        $page_encoding = 'UTF-8';
        $page_margins = array(5, 5, 5, 8);
        $page_pdfa = false;

        $html2pdf = new Html2Pdf(  $page_orientation, $page_format, $page_lang, $page_unicode, $page_encoding, $page_margins, $page_pdfa  );

        $style_definition= '';
        if ( $selected_font_name_key == 'themify' and $cbx_attach_font_file ) {
            $html2pdf->addFont('themify', '', public_path('/fonts/themify/themify.ttf'));  //. file at /public/fonts/themify/themify.ttf
        }

        if ( $selected_font_name_key == 'dejavu_sans' and $cbx_attach_font_file ) {
            $html2pdf->addFont('DejaVu Sans', '', public_path('/fonts/DejaVuSans/DejaVuSans-Bold.ttf'));  //. file at /public/fonts/DejaVuSans/DejaVuSans-Bold.ttf
        }

        if ( $selected_font_name_key == 'simple_line_icons' and $cbx_attach_font_file ) {
            $html2pdf->addFont('DejaVu Sans', '', public_path('/fonts/simple-line-icons/Simple-Line-Icons.ttf'));  //. /public/file at /fonts/simple-line-icons/Simple-Line-Icons
            //.ttf
        }

        if ( $selected_font_name_key == 'open_sans' and $cbx_attach_font_file ) {
            $html2pdf->addFont('Open sans', '', public_path('/fonts/opensans/opensans.ttf'));  //. file at /public/fonts/opensans/opensans.ttf
        }


        if ( $selected_font_name_key == 'source_sans_pro' and $cbx_attach_font_file ) {
            $html2pdf->addFont('Source sans', '', public_path('/fonts/source-sans/sourcesanspro-regular-webfont.ttf'));  //. /public/file at /fonts/source-sans/sourcesanspro-regular-webfont.ttf
        }

        if ( $selected_font_name_key == 'roboto' and $cbx_attach_font_file ) {
            $style_definition= '<style>
            @font-face {
  font-family:\'Roboto\';
  src:url(\'/fonts/roboto/Roboto_regular.ttf\');
  font-style:normal
}
</style>';
            $html2pdf->addFont('Roboto', '', public_path('/fonts/roboto/Roboto_regular.ttf'));  //. /public/file at /fonts/roboto/Roboto_regular.ttf
        }


//        $html2pdf->addFont(  'courier'  );
//        $html2pdf->addFont(  'helvetica'  );
//        $html2pdf->addFont(  'symbol'  );
//        $html2pdf->addFont(  'times'  );
//        $html2pdf->writeHTML( htmlspecialchars_decode($pdf_content) );
//        $this->debToFile(print_r($pdf_content,true),' test_generate_pdf_by_content_test  -10 $pdf_content::');
        $this->debToFile(print_r($selected_font_name_key,true),' test_generate_pdf_by_content_test  -6 $selected_font_name_key::');
        $this->debToFile(print_r($pdf_filename,true),' test_generate_pdf_by_content_test  -7 $pdf_filename::');
        $this->debToFile(print_r($cbx_attach_font_file,true),' test_generate_pdf_by_content_test  -8 $cbx_attach_font_file::');

        $this->debToFile(print_r($style_definition,true),' test_generate_pdf_by_content_test  -9 $style_definition::');
        $this->debToFile(print_r($pdf_content,true),' test_generate_pdf_by_content_test  -10 $pdf_content::');

        $html2pdf->writeHTML( $style_definition.$pdf_content );


        $html2pdf->output($pdf_filename, 'D');
        return response()->json(['message' => "File '".$pdf_filename."' was generated !", 'error_code' => 0], HTTP_RESPONSE_OK);
    } // test_generate_pdf_by_content_test


    public function sendEmail()
    {
        //        $message->{$type}($recipient['address'], $recipient['name']);
        $to_name= 'Nilov Sergey';
        $to= 'nilov@softreactor.com';
//        $to= [ 'address'=> 'nilov@softreactor.com', 'name'=> $to_name ];


        $subject= 'My site subject lorem...';
        $val1= ' $val1 lorem value';
        $val2= ' $val1 lorem value';
        $additiveVars= ['name'=> 'Serge At home', 'val1' => $val1, 'val2' => $val2];

//        \Mail::to($to)->send( new SendgridMail(), ['name' => "testdata"] );
//        dd('Small Email sent # 400');

        //    public function __construct( $view_name, $to= '', $subject= '', $val3= '')

        \Mail::to($to)->send( new SendgridMail( 'mailBlade', $to, $subject , $additiveVars ) );
        dd('Small Email sent # 401');
    }


    public function format_currency($val)
    {
        return response()->json(['error_code' => 0, 'message' => '', 'formatted_currency' => $this->getFormattedCurrency($val)], HTTP_RESPONSE_OK);
    }

}
