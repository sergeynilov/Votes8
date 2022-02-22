<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Settings;
use App\PageContentImage;
use App\PageContent;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\ContactUsRequest;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use App\ActivityLog;
use App\User;
use App\Download;
use App\UserGroup;
use App\ContactUs;
use App\Mail\SendgridMail;
use Response;
use App\library\CheckValueType;


class PageController extends MyAppController
{
    use funcsTrait;
    private $users_tb;
    private $page_contents_tb;

    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->page_contents_tb= with(new PageContent)->getTable();
    }

    public function page_news($news_slug)
    {
        return $this->showPageContent($news_slug, true);
    } // public function page_news()

    public function page_contact_us()
    {
        return $this->showPageContent('contact-us', false);
    } // public function page_contact_us()

    public function page_about()
    {
        return $this->showPageContent('about', false);
    } // public function page_about()

    public function privacy_policy()
    {
        return $this->showPageContent('privacy-policy', false);
    } // public function privacy_policy()

    public function page_security_privacy()
    {
        return $this->showPageContent('security-privacy', false);

    } // public function page_security_privacy()

    public function page_warranty_and_service()
    {
        return $this->showPageContent('warranty-and-service', false);

    } // public function page_warranty_and_service()

    public function page_terms_of_service()
    {
        return $this->showPageContent('terms-of-service', false);

    } // public function page_terms_of_service()


    public function showPageContent($page_slug, $show_other_pages = false)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        $pageContent     = PageContent
            ::select( $this->page_contents_tb.'.*', 'username as creator_username')
            ->getBySlug($page_slug)
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->page_contents_tb.'.creator_id')
            ->first();

        if ($pageContent === null or ! $pageContent->published) {
            return View($this->getFrontendTemplateName() . '.msg', ['text' => 'Page "' . $page_slug . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }

        if ($pageContent->page_type == 'E' and ! empty($pageContent->source_type) and ! empty($pageContent->source_url)) {
            return Redirect::to($pageContent->source_url);
        }
        $other_news_on_limit   = Settings::getValue('other_news_on_limit', CheckValueType::cvtInteger, 20);
        $pageContentImageProps = PageContent::setPageContentImageProps($pageContent->id, $pageContent->image, false);
        if (count($pageContentImageProps) > 0) {
            $pageContent->setPageContentImagePropsAttribute($pageContentImageProps);
        }

        $otherPageContents = [];
        if ($show_other_pages) {
//            $otherPageContents = PageContent
//                ::select(  $this->page_contents_tb.'.*', $this->users_tb.'.username' )
//                ->getByPageType('N')
//                ->getByPublished(1)
//                ->where( $this->page_contents_tb.'.id', '!=', $pageContent->id )
//                ->orderBy('created_at', 'desc')
//                ->join($this->users_tb, $this->users_tb.'.id', '=', $this->page_contents_tb.'.creator_id')
//                ->limit($other_news_on_limit)
//                ->get()
//                ->map(function ($nextTempOtherPageContent, $key) {
//                    $nextTempOtherPageContentImageProps = PageContent::setPageContentImageProps($nextTempOtherPageContent->id, $nextTempOtherPageContent->image, false);
//                    if (count($nextTempOtherPageContentImageProps) > 0) {
//                        $nextTempOtherPageContent->setPageContentImagePropsAttribute($nextTempOtherPageContentImageProps);
//                    }
//                    return $nextTempOtherPageContent;
//                })
//                ->all();
        }


        $downloadsList     = [];
        if( $page_slug == 'about' ) {
            $tempDownloadsList     = Download
                ::getByActive( true)
                ->get();

            foreach( $tempDownloadsList as $nextTempDownload ) {
                $downloadsList[]= [
                    'id' => $nextTempDownload->id,
                    'title' => $nextTempDownload->title,
                    'file' => $nextTempDownload->file,
                    'price' => $nextTempDownload->price,
                    'price_info' => $nextTempDownload->price_info,
                    'description' => $nextTempDownload->description,
                    'extension_image_url' => $this->getFileExtensionsImageUrl($nextTempDownload->file), //     public function getFileExtensionsImageUrl(string $filename):
                    'image_preview_width' => $page_content_image_preview_width= with (new Settings())->getImgPreviewWidth(),

                ];
            }
//        echo '<pre>$downloadsList::'.print_r($downloadsList,true).'</pre>';
//        die("-1 XXZ");

            $viewParamsArray                        = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
            $pdfGenerationOptions                   = config('app.pdfGenerationOptions');
            $viewParamsArray['fontsList']           = !empty($pdfGenerationOptions['fontsList']) ? $pdfGenerationOptions['fontsList'] : [];
            $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
            $viewParamsArray['usersArray']               = $this->SetArrayHeader(['' => ' -Select User- '], User::getUsersSelectionArray());
//            echo '<pre>$downloadsList::'.print_r($downloadsList,true).'</pre>';

        } // // if( $page_slug == 'about' ) {

        $viewParamsArray['downloadsList']     = $downloadsList;
        $pageContentVideos     = [];
        $pageContentImages     = [];
        $tempPageContentImages = PageContentImage
            ::getByPageContent($pageContent->id)
            ->orderBy('is_main', 'desc')
            ->get();

        $page_content_image_preview_width = with(new PageContentImage())->getImgPreviewWidth();
        foreach ($tempPageContentImages as $next_key => $nextTempPageContentImage) {
            $pageContentImageVideoProps                        = PageContentImage::setPageContentImageImageProps($nextTempPageContentImage->page_content_id,
                $nextTempPageContentImage->filename,
                false);
            $pageContentImageVideoProps['image_preview_width'] = $page_content_image_preview_width;
            if ( ! empty($pageContentImageVideoProps['file_width']) and $pageContentImageVideoProps['file_width'] < $page_content_image_preview_width) {
                $pageContentImageVideoProps['image_preview_width'] = $pageContentImageVideoProps['file_width'];
            }
            if (count($pageContentImageVideoProps) > 0) {
                $pageContentImageVideoProps['page_content_id'] = $nextTempPageContentImage->id;
                $pageContentImageVideoProps['info']            = $nextTempPageContentImage->info;
                $pageContentImageVideoProps['created_at']      = $nextTempPageContentImage->created_at;
                $nextTempPageContentImage->setPageContentImageImagePropsAttribute($pageContentImageVideoProps);
            }
            if ($nextTempPageContentImage->is_video) {
                $pageContentVideos[] = $nextTempPageContentImage;
            } else {
                $pageContentImages[] = $nextTempPageContentImage;
            }
        }

//        echo '<pre>$downloadsList::'.print_r($downloadsList,true).'</pre>';
//        die("-1 XXZ");
        $viewParamsArray['pageContentImages'] = $pageContentImages;
        $viewParamsArray['pageContentVideos'] = $pageContentVideos;

        $viewParamsArray['page_slug']             = $page_slug;
        $viewParamsArray['medium_slogan_img_url'] = config('app.medium_slogan_img_url');
        $viewParamsArray['pageContent']           = $pageContent;
        $viewParamsArray['otherPageContents']     = $otherPageContents;
        $viewParamsArray['pageContentImageProps'] = $pageContentImageProps;
        $viewParamsArray['appParamsForJSArray']   = json_encode($appParamsForJSArray);

        return view($this->getFrontendTemplateName() . '.page.show_page_content', $viewParamsArray);
    } // private function showPageContent($page_slug, $show_other_pages= false)


    public function contacts_us_post(ContactUsRequest $request)
    {
        $requestData = $request->all();

        DB::beginTransaction();
//        try {
        $newContactUs               = new ContactUs();
        $newContactUs->author_name  = $requestData['author_name'];
        $newContactUs->author_email = $requestData['author_email'];
        $newContactUs->message      = $requestData['message'];
        $newContactUs->save();

        $newActivityLog              = new ActivityLog();
        $request                     = request();
        $newActivityLog->description = 'Contact Us was sent from ip ' . ($request->ip()) . " with '" . $newContactUs->author_name . "' username, '" . $newContactUs->author_email . "' email ";
        $newActivityLog->subject_id  = $newContactUs->id;
        $newActivityLog->log_name    = $newContactUs->author_name;
        $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_CONTACT_US_SENT;
        $newActivityLog->properties  = '';
        $newActivityLog->save();

        $user_was_registered = false;
        $verification_token  = '';


        $similarUserByEmail = User::getSimilarUserByEmail($requestData['author_email']);
        $similarUserByName  = User::getSimilarUserByEmail($requestData['author_name']);
        if (empty($similarUserByEmail) and empty($similarUserByName)) {
            $newUser           = new User();
            $newUser->username = $requestData['author_name'];
            $newUser->email    = $requestData['author_email'];
            $newUser->status   = "N";
            $newUser->save();
            $user_was_registered = true;
            UserVerification::generate($newUser);

            $this->user_groups_tb= with(new UserGroup)->getTable();

            DB::table($this->user_groups_tb)->insert([
                'user_id'  => $newUser->id,
                'group_id' => USER_ACCESS_USER
            ]);

            $verification_token = $newUser->verification_token;
        }

        DB::commit();

        $site_name    = Settings::getValue('site_name');
        $subject      = 'Contact us was sent at ' . $site_name . ' site ';
        $additiveVars = [
            'to_user_name'        => $requestData['author_name'],
            'message_text'        => $requestData['message'],
            'user_was_registered' => $user_was_registered,
            'verification_token'  => $verification_token,
        ];

        $attachFiles= [
//            public_path('uploads/user-registration-files/rules-of-our-site.pdf'),
//            public_path('uploads/user-registration-files/terms.doc'),
            public_path('uploads/user-registration-files/our-services.doc'),
        ];

        \Mail::to($newContactUs->author_email)->send(new SendgridMail('emails/contact_us_was_sent', $newContactUs->author_email, '', $subject, $additiveVars, $attachFiles));

        return redirect()->route('home-msg', [])->with([
            'text'   => 'Your message was added ! The administration of our site would answer soon.',
            'type'   => 'success',
            'action' => ''
        ]);
//        } catch (\Exception $e) {
        DB::rollback();
        $this->setFlashMessage($e->getMessage(), 'danger');

        return Redirect
            ::back()
            ->withErrors([$e->getMessage()])
            ->withInput([]);
//        }

    } // public function contacts_us_post()


    /*** NEWS BLOCK START  ***/

    public function all_news()
    {
        $request= request();
        $page = $request->input('page');
        if (empty($page)) $page   = 1;
        $viewParamsArray          = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name']);

        $news_per_page                      = Settings::getValue('news_per_page', CheckValueType::cvtInteger, 10);
        $home_page_ref_items_per_pagination = Settings::getValue('home_page_ref_items_per_pagination', CheckValueType::cvtInteger, 10);
        $newsList = PageContent
            ::select( $this->page_contents_tb.'.*', $this->users_tb.'.username')
            ->getByPageType( 'N' )
            ->getByPublished( true )
            ->orderBy('created_at', 'desc')
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->page_contents_tb.'.creator_id')
            ->paginate( $news_per_page , array('*'), 'page', $page)
            ->onEachSide((int)($home_page_ref_items_per_pagination / 2));

        $all_news_count = PageContent
            ::getByPageType( 'N' )
            ->getByPublished( true )
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->page_contents_tb.'.creator_id')
            ->count();

        $viewParamsArray['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParamsArray['newsList']              = $newsList;
        $viewParamsArray['all_news_count']        = $all_news_count;

        return view($this->getFrontendTemplateName() . '.page.all_news', $viewParamsArray);
    } // private function all_news ()


    public function all_external_news()
    {
        $infinite_scroll_rows_per_scroll_step     = Settings::getValue('infinite_scroll_rows_per_scroll_step', CheckValueType::cvtInteger, 20); ;
        $viewParamsArray                          = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name']);
        $appParamsForJSArray['infinite_scroll_rows_per_scroll_step'] = $infinite_scroll_rows_per_scroll_step;

        $allExternalNews = PageContent
            ::select( $this->page_contents_tb.'.*', $this->users_tb.'.username' )
            ->getByPageType( 'E' )
            ->getByPublished( true )
            ->orderBy('id', 'desc')
            ->offset( 0 )->take($infinite_scroll_rows_per_scroll_step)
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->page_contents_tb.'.creator_id')
            ->get();

        $all_external_news_count = PageContent
            ::getByPageType( 'E' )
            ->getByPublished( true )
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->page_contents_tb.'.creator_id')
            ->count();

        $viewParamsArray['appParamsForJSArray']           = json_encode($appParamsForJSArray);
        $viewParamsArray['allExternalNews']               = $allExternalNews;
        $viewParamsArray['external_news_loaded_count']    = count($allExternalNews);
        $viewParamsArray['all_external_news_count']       = $all_external_news_count;

        return view($this->getFrontendTemplateName() . '.page.all_external_news', $viewParamsArray);
    } // private function all_external_news ()


    // get_all_external_news_listing
    public function get_all_external_news_listing($offset)   // data listing with next chunk of rows
    {
        $infinite_scroll_rows_per_scroll_step= Settings::getValue('infinite_scroll_rows_per_scroll_step', CheckValueType::cvtInteger, 20);
        $allExternalNews = PageContent
            ::select( $this->page_contents_tb.'.*', $this->users_tb.'.username' )
            ->getByPageType( 'E' )
            ->getByPublished( true )
            ->orderBy('id', 'desc')
            ->offset( $offset )->take($infinite_scroll_rows_per_scroll_step)
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->page_contents_tb.'.creator_id')
            ->get();
        $viewParamsArray['allExternalNews']          = $allExternalNews;
        return view($this->getFrontendTemplateName() . '.page.all_external_news_infinite_scroll_listing', $viewParamsArray);
    }

    /*** NEWS BLOCK END  ***/


    public function file_download($download_id)
    {
        $download= Download::find($download_id);
//        echo '<pre>$download_id::'.print_r($download_id,true).'</pre>';
//        with(new self)->info( Download::getDownloadFilePath($download_id, $download->file),'Download::getDownloadFilePath($download_id, $download->file)::' );
        $download_file_path = storage_path('app/public/' . Download::getDownloadFilePath($download_id, $download->file));
//        with(new self)->info( $download_file_path,'$download_file_path::' );
//        echo '<pre>$download_file_path::'.print_r($download_file_path, true).'</pre>';
//        die("-1 XXZ");
        return \Response::download( $download_file_path);
    } //\Response::json();

/* Method App\Http\Controllers\PageController::file_download does not exist. */

}
