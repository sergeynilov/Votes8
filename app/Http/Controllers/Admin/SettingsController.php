<?php

namespace App\Http\Controllers\Admin;

use App\MyAppModel;
use DB;
use Auth;
use Session;
use Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ImageOptimizer;

use App\Http\Controllers\MyAppController;
use App\User;
use App\Settings;
use App\SettingsText;
use App\Http\Requests\SettingsUpdateRequest;
use App\Http\Traits\funcsTrait;

class SettingsController extends MyAppController
{

    use funcsTrait;

    private $users_tb;
    private $settings_tb;

    private $fieldsByTabs = [];
    private $textFields = [];

    public function __construct()
    {
        $this->users_tb    = with(new User)->getTable();
        $this->settings_tb = with(new Settings)->getTable();
        $this->settings_text_tb = with(new SettingsText)->getTable();

        $this->fieldsByTabs = Settings::getValidationRulesArray();
        $this->textFields   = [
            'account_register_details_text',
            'account_register_avatar_text',
            'account_register_subscriptions_text',
            'account_register_confirm_text',
            'account_contacts_us_text',
        ];


    } //public function __construct()

    /*** SETTINGS VIEW BLOCK START  ***/
    public function index()
    {
        $request     = request();
        $requestData = $request->all();
        $active_tab = ! empty($requestData['active_tab']) ? $requestData['active_tab'] : '';

        $viewParams                          = $this->getAppParameters(true, ['csrf_token']);
        $viewParams['frontend_home_url']     = config('app.frontend_home_url', '');
        $viewParams['appParamsForJSArray']   = json_encode($viewParams);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');
        $loggedUser                          = Auth::user();
        $viewParams['loggedUser'] = $loggedUser;
        foreach ($this->fieldsByTabs['common_settings'] as $next_common_settings_key => $next_common_settings_validation_rule) {
            $settingsValue                                              = Settings::getValue($next_common_settings_key, null, '');
            $viewParams['common_settings_' . $next_common_settings_key] = $settingsValue;
        }

        foreach ($this->fieldsByTabs['site_content'] as $next_site_content_key => $next_site_content_validation_rule) {
            if (in_array($next_site_content_key, $this->textFields)) {
                $settingsValue = SettingsText::getText($next_site_content_key, '');
            } else {
                $settingsValue = Settings::getValue($next_site_content_key, null, '');
            }
            $viewParams['site_content_' . $next_site_content_key] = $settingsValue;
        }

        foreach ($this->fieldsByTabs['administration_part'] as $next_administration_part_key => $next_administration_part_validation_rule) {
            $settingsValue = Settings::getValue($next_administration_part_key, null, '');
            $viewParams['administration_part_' . $next_administration_part_key] = $settingsValue;
        }

        foreach ($this->fieldsByTabs['votes'] as $next_votes_key => $next_votes_validation_rule) {
            $settingsValue = Settings::getValue($next_votes_key, null, '');
            $viewParams['votes_' . $next_votes_key] = $settingsValue;
        }

        $settings = Settings::getByName('showQuizQualityOptions')->first();
        if ($settings !== null) {
            $settings_showQuizQualityOptions_str = $settings->value;
            $arr                                 = $this->splitStrIntoArray($settings_showQuizQualityOptions_str, ';');
            $settingsShowQuizQualityOptions      = [];

            foreach ($arr as $next_key => $next_value) {
                $settingsShowQuizQualityOptions[] = [
                    'quiz_quality_id'    => $next_key,
                    'quiz_quality_label' => $next_value
                ];
            }
            $request->session()->put('settingsShowQuizQualityOptions', $settingsShowQuizQualityOptions);
        }

        foreach ($this->fieldsByTabs['news'] as $next_news_key => $next_news_validation_rule) {
            $settingsValue                        = Settings::getValue($next_news_key, null, '');
            $viewParams['news_' . $next_news_key] = $settingsValue;
        }

        foreach ($this->fieldsByTabs['users'] as $next_users_key => $next_users_validation_rule) {
            $settingsValue                          = Settings::getValue($next_users_key, null, '');
            $viewParams['users_' . $next_users_key] = $settingsValue;
        }

        foreach ($this->fieldsByTabs['services'] as $next_users_key => $next_users_validation_rule) {
            $settingsValue                          = Settings::getValue($next_users_key, null, '');
            $viewParams['services_' . $next_users_key] = $settingsValue;
        }

        $servicesLastSitemappingResults = Settings::getByName('last_sitemapping_results')->first();
        if ( !empty($servicesLastSitemappingResults) ) {
            $viewParams[ 'services_' . $next_users_key . '_updated_at' ]= $this->getCFFormattedDateTime( $servicesLastSitemappingResults->updated_at );
        }

        $viewParams['usersArray'] = $this->SetArrayHeader(['' => ' -Select User- '], User::getUsersSelectionArray());
        $viewParams['loggedUser'] = $loggedUser;
        $viewParams['active_tab'] = $active_tab;

        return view($this->getBackendTemplateName() . '.admin.settings.index', $viewParams);
    } // public function index()


    public function update(SettingsUpdateRequest $request)
    {
        $requestData        = $request->all();
        $tab_name_to_submit = $requestData['tab_name_to_submit'];
        $update_label       = '';


        if (strtolower($tab_name_to_submit) == 'common_settings') {

            foreach ($this->fieldsByTabs['common_settings'] as $next_common_settings_key => $next_common_settings_validation_rule) {
                $settings = Settings::getByName($next_common_settings_key)->first();
                if ($settings === null) {
                    with(new self)->info( "-1 INSIDE:" . print_r($requestData['common_settings_' . $next_common_settings_key], true) );
                    DB::table($this->settings_tb)->insert([
                        'name' => $next_common_settings_key,
                        'value' =>  ' ',
                    ]);
                    $settings = Settings::getByName($next_common_settings_key)->first();
                }
                $settings->value      = !empty($requestData['common_settings_' . $next_common_settings_key]) ? $requestData['common_settings_' . $next_common_settings_key] : "";
                $settings->updated_at = Carbon::now(config('app.timezone'));
                $settings->save();
            }
            $update_label = 'Common settings';
        } // if (strtolower($tab_name_to_submit) == 'common') {


        if (strtolower($tab_name_to_submit) == 'site_content') {
            foreach ($this->fieldsByTabs['site_content'] as $next_site_content_key => $next_site_content_validation_rule) {
//                with(new self)->info($next_site_content_key, '$next_site_content_key::');
                if (in_array($next_site_content_key, $this->textFields)) {
//                    with(new self)->info(-1);
                    $settings = SettingsText::getByName($next_site_content_key)->first();
                    if ($settings === null) {
//                        with(new self)->info( "-20 INSIDE : " . print_r($next_site_content_key, true) );
                        DB::table($this->settings_text_tb)->insert([
                            'name' => $next_site_content_key,
                            'text' =>  ' ',
                        ]);
                        $settings = SettingsText::getByName($next_site_content_key)->first();
//                    $settings->name = $next_votes_key;
                    }

                } else {
//                    with(new self)->info(-2);
                    $settings = Settings::getByName($next_site_content_key)->first();
                    if ($settings === null) {
//                        with(new self)->info( "-20 INSIDE : " . print_r($next_site_content_key, true) );
                        DB::table($this->settings_tb)->insert([
                            'name' => $next_site_content_key,
                            'value' =>  ' ',
                        ]);
                        $settings = Settings::getByName($next_site_content_key)->first();
                    }
                }
                if (in_array($next_site_content_key, $this->textFields)) {
                    with(new self)->info(-3);
                    $settings->text = $requestData['site_content_' . $next_site_content_key];
                } else {
                    with(new self)->info(-4);
                    $settings->value = !empty($requestData['site_content_' . $next_site_content_key]) ? $requestData['site_content_' . $next_site_content_key] : "";
                }
                $settings->updated_at = Carbon::now(config('app.timezone'));
                $settings->save();
            }
            $update_label = 'Site content settings';
        } // if (strtolower($tab_name_to_submit) == 'sitecontent') {


        if (strtolower($tab_name_to_submit) == 'administration_part') {
            foreach ($this->fieldsByTabs['administration_part'] as $next_administration_part_key => $next_administration_part_validation_rule) {
                $settings = Settings::getByName($next_administration_part_key)->first();
                if ($settings === null) {
                    DB::table($this->settings_tb)->insert([
                        'name' => $next_administration_part_key,
                        'value' =>  ' ',
                    ]);
                    $settings = Settings::getByName($next_administration_part_key)->first();
                }
                $settings->value      = !empty($requestData['administration_part_' . $next_administration_part_key]) ? $requestData['administration_part_' .
                                                                                                                                 $next_administration_part_key]: "";
                $settings->updated_at = Carbon::now(config('app.timezone'));
                $settings->save();
            }
            $update_label = 'Site administration part settings';
        } // if (strtolower($tab_name_to_submit) == 'administrationpart') {


        if (strtolower($tab_name_to_submit) == 'users') {
            foreach ($this->fieldsByTabs['users'] as $next_users_key => $next_users_validation_rule) {

                // users_files_on_registration
                echo '<pre>$next_users_key::' . print_r($next_users_key, true) . '</pre>';
                with(new self)->info($next_users_key, '$next_users_key::');
                $default_key = true;
                if ($next_users_key == 'files_on_registration') {
                    $default_key = false;
                }

                $media_name = '';
                $pos        = strpos($next_users_key, '_authorization');
                with(new self)->info($pos, '$pos::::::::::');
                if ($pos > 0) {
                    $a = $this->pregSplit('/_authorization/', $next_users_key);
                    with(new self)->info($a, '$a::');
                    if ( ! empty($a[0])) {
                        $media_name = $a[0];
                        echo '<pre>$media_name::' . print_r($media_name, true) . '</pre>';
                        with(new self)->info($media_name, '$media_name::');
//                        $default_key = false;
                    }

//                    $default_key= false;
                }
//                with(new self)->info( $media_name,'$media_name::' );
                with(new self)->info($default_key, '$default_key::');
                if ( ! empty($media_name) and empty($requestData['users_' . $next_users_key])) { // media authorization was not checked so we need to remove it
                    $settings = Settings::getByName($next_users_key)->first();
//                    echo '<pre>$settings::' . print_r($settings, true) . '</pre>';
                    if ($settings !== null) {
                        $settings->delete();
                    }
                }

                // verification_token_days_expired
                //cbx_users_allow_facebook_authorization
                echo '<pre>$default_key::' . print_r($default_key, true) . '</pre>';
                if ($default_key and ! empty($requestData['users_' . $next_users_key])) {
                    $settings = Settings::getByName($next_users_key)->first();
                    echo '<pre>++++++++$settings::' . print_r($settings, true) . '</pre>';
                    with(new self)->info($settings, '$settings::');
//                    if ($settings === null) {
//                        $settings       = new Settings();
//                        $settings->name = 'FFF';//$next_users_key;
//                    }
                    if ($settings === null) {
//                        with(new self)->info( "-3 INSIDE : " . print_r($requestData[$next_users_key], true)  );
                        DB::table($this->settings_tb)->insert([
                            'name' => $next_users_key,
                            'value' =>  ' ',
                        ]);
                        $settings = Settings::getByName($next_users_key)->first();
//                    $settings->name = $next_votes_key;
                    }
                    echo '<pre>$next_users_key::' . print_r($next_users_key, true) . '</pre>';
                    with(new self)->info($next_users_key, '$next_users_key::');
                    with(new self)->info($requestData['users_' . $next_users_key], '$requestData[\'users_\' . $next_users_key]::');
//                    echo '<pre>$requestData[\'users_\' . $next_users_key]::'.print_r($requestData['users_' . $next_users_key],true).'</pre>';
                    $settings->value = !empty($requestData['users_' . $next_users_key]) ? $requestData['users_' . $next_users_key] : "";
//                    $settings->updated_at = Carbon::now(config('app.timezone'));
//                    die("-1 XXZ");
                    $settings->save();
                }
            }
            $update_label = 'User\'s settings';
        } // if (strtolower($tab_name_to_submit) == 'users') {


        if (strtolower($tab_name_to_submit) == 'votes') {
            foreach ($this->fieldsByTabs['votes'] as $next_votes_key => $next_votes_validation_rule) {
//                echo '<pre>$next_votes_key::'.print_r($next_votes_key,true).'</pre>';
                with(new self)->info( $next_votes_key,'$next_votes_key::' );
                $settings = Settings::getByName($next_votes_key)->first();
                with(new self)->info( $settings,'$settings::' );
//                echo '<pre>$settings::'.print_r($settings,true).'</pre>';
                if ($settings === null) {
                    with(new self)->info( " -4 INSIDE : " . print_r($requestData['votes_' . $next_votes_key], true)   );
                    DB::table($this->settings_tb)->insert([
                        'name' => $next_votes_key,
                        'value' =>  ' ',
                    ]);
                    $settings = Settings::getByName($next_votes_key)->first();
//                    $settings->name = $next_votes_key;
                }
                with(new self)->info( $requestData['votes_' . $next_votes_key],'$requestData[\'votes_\' . $next_votes_key]::' );
                $settings->value      = !empty($requestData['votes_' . $next_votes_key]) ? $requestData['votes_' . $next_votes_key] : "";
//                $settings->updated_at = Carbon::now(config('app.timezone'));
                $settings->save();
            }
            $settingsShowQuizQualityOptions = Session::get('settingsShowQuizQualityOptions', []);
//            echo '<pre>$settingsShowQuizQualityOptions::'.print_r($settingsShowQuizQualityOptions,true).'</pre>';
            $options_str = '';
            foreach ($settingsShowQuizQualityOptions as $nextSettingsShowQuizQualityOption) {
                $options_str .= $nextSettingsShowQuizQualityOption['quiz_quality_id'] . '=' . $nextSettingsShowQuizQualityOption['quiz_quality_label'] . ';';
            }







            $settings = Settings::getByName('showQuizQualityOptions')->first();
//            echo '<pre>$settings::'.print_r($settings,true).'</pre>';
            if ($settings === null) {
                DB::table($this->settings_tb)->insert([
                        'name' => 'showQuizQualityOptions',
                        'value' =>  '',
                ]);
                $settings = Settings::getByName('showQuizQualityOptions')->first();
//                $settings->name = 'showQuizQualityOptions';
            }
            with(new self)->info( $options_str,'$options_str::' );
            $settings->value      = !empty($options_str) ? $options_str : "";
//            $settings->updated_at = Carbon::now(config('app.timezone'));
            $settings->save();





//            die("-1 XXZ");
            $update_label = 'Votes settings';
        } //if (strtolower($tab_name_to_submit) == 'news') {

        if (strtolower($tab_name_to_submit) == 'news') {
            foreach ($this->fieldsByTabs['news'] as $next_news_key => $next_news_validation_rule) {
                echo '<pre>$next_news_key::' . print_r($next_news_key, true) . '</pre>';
                $settings = Settings::getByName($next_news_key)->first();
                echo '<pre>$settings::' . print_r($settings, true) . '</pre>';
                if ($settings === null) {
                    DB::table($this->settings_tb)->insert([
                        'name' => $next_news_key,
                        'value' =>  '',
                    ]);
                    $settings = Settings::getByName($next_news_key)->first();
//                $settings->name = 'showQuizQualityOptions';
                }
                $settings->value      = !empty($requestData['news_' . $next_news_key]) ? $requestData['news_' . $next_news_key] : "";
                $settings->updated_at = Carbon::now(config('app.timezone'));
                $settings->save();
            }
            $update_label = 'News settings';
        } //if (strtolower($tab_name_to_submit) == 'news') {

        $this->setFlashMessage($update_label . ' updated successfully !', 'success', 'Settings');
        return redirect()->route('admin.settings.index', ['active_tab' => $tab_name_to_submit]);
    } // public function update_details(ProfileUserDetailsRequest $request)

//
//                        tab_name_to_submit

//    public function update_details(ProfileUserDetailsRequest  $request)
//    {
//        $loggedUser = Auth::user();
//        $requestData = $request->all();
//        $loggedUser->first_name = $requestData['first_name'];
//        $loggedUser->last_name  = $requestData['last_name'];
//        $loggedUser->phone      = $requestData['phone'];
//        $loggedUser->website    = $requestData['website'];
//        $loggedUser->notes      = $requestData['notes'];
//        $loggedUser->sex        = $requestData['sex'];
//        $loggedUser->updated_at = Carbon::now(config('app.timezone'));
//        $loggedUser->save();
//        $this->setFlashMessage('Profile updated successfully !', 'success', 'Profile');
//
//        return Redirect::route('profile-view');
//    } // public function update_details(ProfileUserDetailsRequest $request)

    /*** SETTINGS TO PDF BLOCK END  ***/


    // SETTINGS QUIZ QUALITY OPTIONS BLOCK BEGIN
    public function get_settings_show_quiz_quality_options_listing()
    {
        $settingsShowQuizQualityOptions                    = Session::get('settingsShowQuizQualityOptions', []);
        $viewParamsArray                                   = $appParamsForJSArray = $this->getAppParameters(true, []);
        $viewParamsArray['settingsShowQuizQualityOptions'] = $settingsShowQuizQualityOptions;

        $html = view($this->getBackendTemplateName() . '.admin.settings.show_quiz_quality_options_listing', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_settings_show_quiz_quality_options_listing


    public function settings_show_quiz_quality_option_destroy()
    {
        $request         = request();
        $quiz_quality_id = $request->get('quiz_quality_id');
//            $this->debToFile(print_r( $quiz_quality_id,true),'  TEXT  -1 $quiz_quality_id::');
        try {
            $settingsShowQuizQualityOptions = Session::get('settingsShowQuizQualityOptions', []);
//                $this->debToFile(print_r( $settingsShowQuizQualityOptions,true),'  TEXT  -1 $settingsShowQuizQualityOptions::');
            foreach ($settingsShowQuizQualityOptions as $next_key => $nextSettingsShowQuizQualityOptions) {
//                    $this->debToFile(print_r( $nextSettingsShowQuizQualityOptions['id'],true),'  TEXT  -1 $nextSettingsShowQuizQualityOptions[\'id\']::');
                if ($nextSettingsShowQuizQualityOptions['quiz_quality_id'] == $quiz_quality_id) {
                    unset($settingsShowQuizQualityOptions[$next_key]);
                }
            }
            $request->session()->put('settingsShowQuizQualityOptions', $settingsShowQuizQualityOptions);
        } catch (Exception $e) {
            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'settingsShowQuizQualityOptions' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function settings_show_quiz_quality_option_destroy

    public function add_settings_show_quiz_quality()
    {
        $request = request();
        try {
            $add_new_quiz_quality_id    = $request->get('add_new_quiz_quality_id');
            $add_new_quiz_quality_label = $request->get('add_new_quiz_quality_label');

            $settingsShowQuizQualityOptions = Session::get('settingsShowQuizQualityOptions', []);
//            $this->debToFile(print_r($settingsShowQuizQualityOptions, true), '  TEXT  -1 $settingsShowQuizQualityOptions::');
            foreach ($settingsShowQuizQualityOptions as $next_key => $nextSettingsShowQuizQualityOptions) {
//                $this->debToFile(print_r($nextSettingsShowQuizQualityOptions['id'], true), '  TEXT  -1 $nextSettingsShowQuizQualityOptions[\'id\']::');
                if ($nextSettingsShowQuizQualityOptions['quiz_quality_id'] == $add_new_quiz_quality_id) {
                    return response()->json(['error_code' => 1, 'message' => 'Quiz quality id # "' . $add_new_quiz_quality_id . '" already used!'],
                        HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
                }
                if ($nextSettingsShowQuizQualityOptions['quiz_quality_label'] == $add_new_quiz_quality_label) {
                    return response()->json(['error_code' => 1, 'message' => 'Quiz quality label "' . $add_new_quiz_quality_label . '" already used!'],
                        HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
                }
            }


            $settingsShowQuizQualityOptions[] = [
                'quiz_quality_id'    => $add_new_quiz_quality_id,
                'quiz_quality_label' => $add_new_quiz_quality_label
            ];
            //         data: { "add_new_quiz_quality_id": add_new_quiz_quality_id, "add_new_quiz_quality_label": add_new_quiz_quality_label, "_token": this_csrf_token},

            $request->session()->put('settingsShowQuizQualityOptions', $settingsShowQuizQualityOptions);
        } catch (Exception $e) {
            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function add_settings_show_quiz_quality

    // SETTINGS QUIZ QUALITY OPTIONS BLOCK END


    // SETTINGS FILE ON REGISTRATIONS BLOCK START


    public function get_settings_related_file_on_registrations()
    {
        $fileOnRegistrations         = [];
        $user_registration_files_str = Settings::getValue('userRegistrationFiles');
        $tempUserRegistrationFiles   = array_unique($this->pregSplit('/;/', $user_registration_files_str));

//        $file_on_registration_preview_width = with(new Settings())->getImgPreviewWidth();

//        echo '<pre>$file_on_registration_preview_width::'.print_r($file_on_registration_preview_width,true).'</pre>';
        foreach ($tempUserRegistrationFiles as $next_key => $next_file_on_registration) {
            $next_file_on_registration = trim($next_file_on_registration);
            if (empty($next_file_on_registration)) {
                continue;
            }
            $fileOnRegistrationProps = Settings::setSettingsFileOnRegistrationProps($next_file_on_registration, false);
            $fileOnRegistrations[]   = $fileOnRegistrationProps;

        }

//        $this->debToFile(print_r($fileOnRegistrations, true), '  TEXT  -1 $fileOnRegistrations::');
        $viewParamsArray                                = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['fileOnRegistrations']         = $fileOnRegistrations;
        $viewParamsArray['file_on_registrations_count'] = count($fileOnRegistrations);
        $html                                           = view($this->getBackendTemplateName() . '.admin.settings.files_on_registration', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function public function get_settings_related_file_on_registrations()


    // upload_settings_file_on_registration_to_tmp_file_on_registration
    public function upload_settings_file_on_registration_to_tmp_file_on_registration()
    {
        $unique_session_id = Session::getId();
//        $this->debToFile(print_r($unique_session_id, true), '  TEXT  - $unique_session_id::');
//        die("-1 XXZ $unique_session_id");


        $request     = request();
        $requestData = $request->all();
//        $this->debToFile(print_r($requestData, true), '  upload_settings_file_on_registration_to_tmp_file_on_registration  --0 $requestData::');

//        $this->debToFile(print_r($_FILES, true), '  upload_settings_file_on_registration_to_tmp_file_on_registration  -21 $_FILES::');

        $UPLOADS_TPM_SETTINGS_FILE_ON_REGISTRATION_IMAGES_DIR = 'tmp/' . with(new Settings)->getTable();


        $dst_tmp_directory    = $UPLOADS_TPM_SETTINGS_FILE_ON_REGISTRATION_IMAGES_DIR . '_' . $unique_session_id;
        $tmp_dest_dirname_url = '/' . $UPLOADS_TPM_SETTINGS_FILE_ON_REGISTRATION_IMAGES_DIR . '_' . $unique_session_id;
        $src_filename         = $_FILES['files']['tmp_name'][0];


        $extension_filename = '';
        $imagesExtensions   = config('app.images_extensions');
        $is_image           = false;

//        $this->debToFile(print_r($src_filename, true), '  $tmpSettingsImagesDirs  -24 $src_filename::');
        $img_basename = Settings::checkValidImgName($_FILES['files']['name'][0], 0, true);
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
            $FilenameInfo = $this->getImageShowSize($src_filename, with(new Settings)->getImgPreviewWidth(), with(new Settings)->getImgPreviewHeight());
        } else {
            $next_extension_file = $this->getFileExtensionsImageUrl($img_basename); //     public function getFileExtensionsImageUrl(string $filename):
            $extension_filename = $next_extension_file;
//            $this->debToFile(print_r($extension_filename, true), '  -20 $extension_filename::');
            $FilenameInfo = $this->getImageShowSize(public_path($extension_filename), with(new Settings)->getImgPreviewWidth(), with(new Settings)->getImgPreviewHeight());
        }
//        $this->debToFile(print_r($FilenameInfo, true), '  $tmpSettingsImagesDirs  -27 $FilenameInfo::');
        $dest_filename = 'public/' . $tmp_dest_filename;
//        $this->debToFile(print_r($dest_filename, true), '  upload_image_to_settings_file_on_registration  --2 $dest_filename::');
        Storage::disk('local')->put($dest_filename, File::get($src_filename));
        ImageOptimizer::optimize( storage_path().'/app/'.$dest_filename, null );

        $filesize = filesize($tmp_dest_filename_path);
        $fileOnRegistrationProps = with(new Settings)->getCFImageProps($tmp_dest_filename_path, []);
//        $this->debToFile(print_r($fileOnRegistrationProps, true), '  $tmpSettingsImagesDirs  -28 $fileOnRegistrationProps::');
        $resArray = [
            "files" =>
                [
                    "short_name"   => $img_basename,
                    "name"         => $tmp_dest_filename,
                    "size"         => $filesize,
                    'FilenameInfo' => $FilenameInfo,
                    'file_info'    => ! empty($fileOnRegistrationProps['file_info']) ? $fileOnRegistrationProps['file_info'] : '',
                    "size_label"   => $this->getNiceFileSize($filesize),
                    "info_message" => $info_message,
                    "url"          => ($is_image ? ('/storage' . $tmp_dest_dirname_url . '/' . $img_basename . '?tm=' . time()) : $extension_filename),
                ]
        ];
        echo json_encode($resArray);
    }  // upload_settings_file_on_registration_to_tmp_file_on_registration


    public function upload_settings_image_to_file_on_registration()
    {
        $request     = request();
        $requestData = $request->all();

        $file_on_registration_tmp_path = $requestData['hidden_selected_file_on_registration'];
//        $this->debToFile(print_r($file_on_registration_tmp_path, true), '  TEXT  -1 $file_on_registration_tmp_path::');
        $file_on_registration_basename = Settings::checkValidImgName(basename($file_on_registration_tmp_path), 0, true);

        $result_user_registration_files_str = '';
        $userRegistrationSettings     = Settings::getByName('userRegistrationFiles')->first();
        $user_registration_files_str  = ! empty($userRegistrationSettings->value) ? $userRegistrationSettings->value : '';
        $currentUserRegistrationFiles = array_unique($this->pregSplit('/;/', $user_registration_files_str));
//        $this->debToFile(print_r($user_registration_files_str, true), '  TEXT  -1 $user_registration_files_str::');

        $is_file_already_uploaded = false;
        foreach ($currentUserRegistrationFiles as $next_current_user_registration_file) {
            if ($next_current_user_registration_file == $file_on_registration_basename) {
                $is_file_already_uploaded = true;
            } else {
                $result_user_registration_files_str.= $next_current_user_registration_file.';';
//                $this->debToFile(print_r($result_user_registration_files_str, true), '  TEXT  -11 $result_user_registration_files_str::');
            }
        }
        if ( ! $is_file_already_uploaded) {
            $result_user_registration_files_str .= $file_on_registration_basename . ';';
        }

        if ($is_file_already_uploaded) {
            return response()->json(['error_code' => 1, 'message' => "File '".$file_on_registration_basename."' is already uploaded !", 'file_on_registration' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

//        $this->debToFile(print_r( $result_user_registration_files_str,true),'  TEXT  -33 $result_user_registration_files_str::');
//        $this->debToFile(print_r( strlen($result_user_registration_files_str),true),'  TEXT  -34 strlen($result_user_registration_files_str)::');

        if (strlen($result_user_registration_files_str) > 255) {
            return response()->json(['error_code' => 1, 'message' => "Too much files uploaded !", 'file_on_registration' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
//        $this->debToFile(print_r($is_file_already_uploaded, true), '  TEXT  -22 $is_file_already_uploaded::');
//
        $dest_filename = 'public/' . with(new Settings)->getUuploadsFileOnRegistrationFiles_Dir() . '/' . $file_on_registration_basename;
//        $this->debToFile(print_r($dest_filename, true), '  TEXT  -10 $dest_filename::');
        Storage::disk('local')->put($dest_filename, File::get(storage_path('/app/public/') . $file_on_registration_tmp_path));
        ImageOptimizer::optimize( storage_path().'/app/'.$dest_filename, null );


        DB::beginTransaction();
        try {

            if ( empty($userRegistrationSettings->name) ) {
//                $userRegistrationSettings       = new Settings();
//                $userRegistrationSettings->name = 'userRegistrationFiles';
                DB::table($this->settings_tb)->insert([
                    'name' => 'userRegistrationFiles',
                    'value' =>  ' ',
                ]);
                $userRegistrationSettings = Settings::getByName('userRegistrationFiles')->first();

//                $this->debToFile(print_r($result_user_registration_files_str, true), '  TEXT  -NEW $result_user_registration_files_str::');
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

        return ['message' => '', 'error_code' => 0, 'file_on_registration' => $file_on_registration_basename];
    } //public function upload_settings_image_to_file_on_registration()


    public function delete_settings_file_on_registration_destroy()
    {
        $request              = request();
        $file_on_registration = $request->get('file_on_registration');
//        $this->debToFile(print_r($file_on_registration, true), '  TEXT  -1 $file_on_registration::');
            $userRegistrationSettings = Settings::getByName('userRegistrationFiles')->first();
            $user_registration_files_str = !empty($userRegistrationSettings->value) ? $userRegistrationSettings->value : '';
            $tempUserRegistrationFiles   = array_unique($this->pregSplit('/;/', $user_registration_files_str));

            $result_user_registration_files_str= '';
//            $this->debToFile(print_r($tempUserRegistrationFiles, true), '  TEXT  -1 $tempUserRegistrationFiles::');
            foreach ($tempUserRegistrationFiles as $next_key => $next_user_registration_file) {
//                $this->debToFile(print_r($next_user_registration_file, true), '  TEXT  -1 $next_user_registration_file::');
                if ( trim($next_user_registration_file) == trim($file_on_registration) ) {
//                    $this->debToFile(print_r($next_user_registration_file, true), '  UNSET $next_user_registration_file::');
                    unset($tempUserRegistrationFiles[$next_key]);

                    $filename_to_delete = 'public/' . with(new Settings)->getUuploadsFileOnRegistrationFiles_Dir() . '/' . $file_on_registration;
//                    $this->debToFile(print_r($filename_to_delete, true), '  TEXT  -10 $filename_to_delete::');
                    Storage::delete($filename_to_delete);
                } else {
                    $result_user_registration_files_str.= $next_user_registration_file.';';
                }
            }


        DB::beginTransaction();
        try {

            if ($userRegistrationSettings === null) {
                $userRegistrationSettings       = new Settings();
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
    } //     public function delete_settings_file_on_registration_destroy

    // SETTINGS FILE ON REGISTRATIONS BLOCK END


    // SET DEVELOPERS MODE ON/OFF BLOCK BEGIN

    public function set_developers_mode_on()
    {
        $request         = request();
        $request->session()->put('app_developers_mode', true);
        return response()->json(['error_code' => 0, 'message' => '', 'app_developers_mode' => 1], HTTP_RESPONSE_OK);
    } // public function set_developers_mode_on

    public function set_developers_mode_off()
    {
        Session::forget('app_developers_mode');
        return response()->json(['error_code' => 0, 'message' => '', 'app_developers_mode' => ''], HTTP_RESPONSE_OK);
    } // public function set_developers_mode_off

    // SET DEVELOPERS MODE ON/OFF BLOCK END

    // LOGS BLOCK END
    public function view_laravel_log()
    {
        $laravel_log= base_path('storage/logs/laravel.log');
        if ( file_exists($laravel_log) ) {
            $laravel_log_content= File::get($laravel_log);
            $laravel_log_content= preg_replace('/\r\n?/', "<br>", $laravel_log_content);
            return response()->json(['error_code' => 0, 'message' => '', 'laravel_log_content' => $laravel_log_content], HTTP_RESPONSE_OK);
        } else {
            return response()->json(['error_code' => 0, 'message' => 'laravel log file not found !', 'laravel_log' => $laravel_log], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    } // public function view_laravel_log

    public function delete_laravel_log()
    {
        $laravel_log= base_path('storage/logs/laravel.log');
        if ( file_exists($laravel_log) ) {
            unlink($laravel_log);
            return response()->json(['error_code' => 0, 'message' => '', 'laravel_log' => $laravel_log], HTTP_RESPONSE_OK);
        } else {
            return response()->json(['error_code' => 0, 'message' => 'laravel log file not found !', 'laravel_log' => $laravel_log], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => '', 'laravel_log' => $laravel_log], HTTP_RESPONSE_OK);
    } // public function delete_laravel_log



    public function view_paypal_log()
    {
        $paypal_log= base_path('storage/logs/paypal_log.txt');
        if ( file_exists($paypal_log) ) {
            $paypal_log_content = str_replace('execute($data) -1 $data:', '<strong>execute($data) -1 $data:</strong>', File::get($paypal_log) );
            $paypal_log_content= preg_replace('/\r\n?/', "<br>", $paypal_log_content);
            return response()->json(['error_code' => 0, 'message' => '', 'paypal_log_content' => $paypal_log_content], HTTP_RESPONSE_OK);
        } else {
            return response()->json(['error_code' => 0, 'message' => 'Paypal log file not found !', 'paypal_log' => $paypal_log], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    } // public function view_paypal_log

    public function delete_paypal_log()
    {
        $paypal_log= base_path('storage/logs/paypal_log.txt');

        if ( file_exists($paypal_log) ) {
            unlink($paypal_log);
            return response()->json(['error_code' => 0, 'message' => '', 'paypal_log' => $paypal_log], HTTP_RESPONSE_OK);
        } else {
            return response()->json(['error_code' => 0, 'message' => 'Paypal log file not found !', 'paypal_log' => $paypal_log], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    } // public function delete_paypal_log



////////////
    public function view_logging_deb()
    {
        $logging_deb= base_path('storage/logs/logging_deb.txt');
        if ( file_exists($logging_deb) ) {
            $logging_deb_content = str_replace('execute($data) -1 $data:', '<strong>execute($data) -1 $data:</strong>', File::get($logging_deb) );
            $logging_deb_content= preg_replace('/\r\n?/', "<br>", $logging_deb_content);
            return response()->json(['error_code' => 0, 'message' => '', 'logging_deb_content' => $logging_deb_content], HTTP_RESPONSE_OK);
        } else {
            return response()->json(['error_code' => 0, 'message' => 'Logging deb file not found !', 'logging_deb' => $logging_deb], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    } // public function view_logging_deb

    public function delete_logging_deb()
    {
        $logging_deb= base_path('storage/logs/logging_deb.txt');

        if ( file_exists($logging_deb) ) {
            unlink($logging_deb);
            return response()->json(['error_code' => 0, 'message' => '', 'logging deb' => $logging_deb], HTTP_RESPONSE_OK);
        } else {
            return response()->json(['error_code' => 0, 'message' => 'Logging deb file not found !', 'logging_deb' => $logging_deb], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    } // public function delete_logging_deb
////////


    public function view_sql_tracing_log()
    {
        $sql_tracing_log= base_path('storage/logs/sql-tracing-.txt');
        if ( file_exists($sql_tracing_log) ) {
            $sql_tracing_log_content = str_replace('Time ', '<strong>Time </strong>', File::get($sql_tracing_log) );
            $sql_tracing_log_content= preg_replace('/\r\n?/', "<br>", $sql_tracing_log_content);
            return response()->json(['error_code' => 0, 'message' => '', 'sql_tracing_log_content' => $sql_tracing_log_content], HTTP_RESPONSE_OK);
        } else {
            return response()->json(['error_code' => 0, 'message' => 'Sql tracing log file not found !', 'sql_tracing_log' => $sql_tracing_log],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    } // public function view_sql_tracing_log

    public function delete_sql_tracing_log()
    {
        $sql_tracing_log= base_path('storage/logs/sql-tracing-.txt');

        if ( file_exists($sql_tracing_log) ) {
            unlink($sql_tracing_log);
            return response()->json(['error_code' => 0, 'message' => '', 'sql_tracing_log' => $sql_tracing_log], HTTP_RESPONSE_OK);
        } else {
            return response()->json(['error_code' => 0, 'message' => 'Sql tracing log file not found !', 'sql_tracing_log' => $sql_tracing_log],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    } // public function delete_sql_tracing_log

    // LOGS BLOCK END


}