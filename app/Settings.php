<?php

namespace App;

use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;
use App\library\CheckValueType;

class Settings extends MyAppModel
{
    protected $table      = 'settings';
    protected $primaryKey = 'id';
    public $timestamps = false;

    use funcsTrait;
    private static $yesNoChoiceValueArray = Array("Y" => 'Yes', "N" => 'No');
    private static $textFields =    [
        'account_register_details_text',
        'account_register_avatar_text',
        'account_register_subscriptions_text',
        'account_register_confirm_text',
        'account_contacts_us_text',
    ];

    protected $fillable = [
        'name',
        'value',
    ];


    protected static $img_preview_width = 160;
    protected static $img_preview_height = 120;


    protected $settingsFileOnRegistrationPropsArray = [];    // file-on-registrations
    protected $file_on_registration_filename_max_length = 255;

    protected static $uploads_file_on_registration_files_dir = 'user-registration-files';
    protected static $files_extention_dir = '/images/FileExtentions/';

//    public function __construct()
//    {
//    }


    public static function getYesNoChoiceValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$yesNoChoiceValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }

    public static function getYesNoChoiceLabel(string $yes_no_choice): string
    {
        if ( ! empty(self::$yesNoChoiceValueArray[$yes_no_choice])) {
            return self::$yesNoChoiceValueArray[$yes_no_choice];
        }

        return self::$yesNoChoiceValueArray[0];
    }

    public static function scopeGetByName($query, $name)
    {
        return $query->where(with(new Settings)->getTable() . '.name', '=', $name);
    }

    public static function getValue($name, int $checkValueType= null, $default_value = null)
    {
        // return ''; // COMMENT
        $settingsValue = Settings::getByName($name)->first();

        if (empty($settingsValue->value)) {
            return $default_value;
        }
        if ( $checkValueType == CheckValueType::cvtInteger and !with(new Settings)->isValidInteger($settingsValue->value) and !empty($default_value)) {
            return $default_value;
        }
        if ( $checkValueType == CheckValueType::cvtFloat and !with(new Settings)->isValidFloat($settingsValue->value) and !empty($default_value)) {
            return $default_value;
        }
        if ( $checkValueType == CheckValueType::cvtBool and !with(new Settings)->isValidBool($settingsValue->value) and !empty($default_value)) {
            return $default_value;
        }
        return $settingsValue->value;
    }

    public static function getSettingsList(array $filtersArray, $index = '', $file = '', $line = '')
    {
//        return [];   // COMMENT
        $is_debug = false;//in_array('account_register_details_text',$filtersArray);
        if ($is_debug) {
            echo '<pre>$index::' . print_r($index, true) . '</pre>';
            echo '<pre>self::$textFields::' . print_r(self::$textFields, true) . '</pre>';
        }
        $settings_table_name = with(new Settings)->getTable();
        $quoteModel          = Settings::from( $settings_table_name );

        $textFiltersArray           = [];

        /* Set filter condition for all nonempty values in $filtersArray */
        if ( ! empty($filtersArray) and is_array($filtersArray)) {
            foreach ($filtersArray as $next_key => $next_filter_field) {
                if (in_array($next_filter_field, self::$textFields)) {
                    $textFiltersArray[] = $next_filter_field;
                    unset($filtersArray[$next_key]);
                }
            }
//            echo '<pre>$filtersArray::'.print_r($filtersArray,true).'</pre>';
            $quoteModel->whereIn( 'name', $filtersArray);
//            die("-1 XXZ");
        }

        if ($is_debug) {
            echo '<pre>$textFiltersArray::' . print_r($textFiltersArray, true) . '</pre>';
            echo '<pre>$filtersArray::' . print_r($filtersArray, true) . '</pre>';
//            die("-1 XXZ==");
        }
        $settingsValuesList = $quoteModel->get();
        $retArray           = [];
        foreach ($settingsValuesList as $next_key => $nextSettingsValue) {
            $retArray[$nextSettingsValue->name] = $nextSettingsValue->value;
        }
        if ( ! empty($textFiltersArray) and is_array($textFiltersArray)) {
            $settingsTextValues = SettingsText::getSettingsTextList($textFiltersArray);
            foreach ($settingsTextValues as $next_key => $nexSettingsTextValue) {
                $retArray[$nexSettingsTextValue->name] = $nexSettingsTextValue->text;
            }
        }

        return $retArray;
    }

    public static function getSimilarSettingsByName(string $name, int $id = null, bool $return_count = false)
    {
        $quoteModel = Settings::whereRaw(Settings::myStrLower('name', false, false) . ' = ' . Settings::myStrLower(Settings::mysqlEscape($name), true, false));
        if ( ! empty($id)) {
            $quoteModel = $quoteModel->where('id', '!=', $id);
        }
        if ($return_count) {
            return $quoteModel->get()->count();
        }
        $retRow = $quoteModel->get();
        if (empty($retRow[0])) {
            return false;
        }

        return $retRow[0];
    }


    public static function updateSettings(array $dataArray): array
    {
        foreach ($dataArray as $next_settings_name => $next_settings_value) {
            $similarSettings = Settings::getSimilarSettingsByName($next_settings_name);
            if ($similarSettings === false) {
                $similarSettings       = new Settings();
                $similarSettings->name = $next_settings_name;
            }
            $similarSettings->value = $next_settings_value;
            $similarSettings->save();
        }

        return ['error_code' => 0, 'errorsList' => [], 'success_message' => "Settings were updated"];
    }

    public function getImgPreviewWidth(): int
    {
        return self::$img_preview_width;
    }

    public function getImgPreviewHeight(): int
    {
        return self::$img_preview_height;
    }

    public function getFilesExtentionDir(): string
    {
        return self::$files_extention_dir;
    }


    public function getUuploadsFileOnRegistrationFiles_Dir(): string
    {
        return self::$uploads_file_on_registration_files_dir;
    }

    public static function setSettingsFileOnRegistrationProps(string $file = null, bool $skip_non_existing_file = false): array
    {
        if (empty($file) and $skip_non_existing_file) {
            return [];
        }
        $imagesExtensions = config('app.images_extensions');

        $filename_extension = strtolower(with(new Settings)->getFilenameExtension($file));
        $file_was_found = true;

        $dir_path       = self::$uploads_file_on_registration_files_dir;
        $file_full_path = $dir_path . '/' . $file;
        $file_exists = ( ! empty($file) and Storage::disk('local')->exists('public/' . $file_full_path));

        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_was_found = false;
            $file_full_path = config('app.empty_img_url');
        }

        $extension_filename   = '';
        $fileExtensionsImages = config('app.fileExtensionsImages');
        $file_path = $file_full_path;
        $is_image = false;
        if (in_array($filename_extension, $imagesExtensions)) {
            $is_image = true;
        } else {
            foreach ($fileExtensionsImages as $next_extension => $next_extension_file) {
                if (strtolower($next_extension) == $filename_extension) {
                    $extension_filename = Settings::$files_extention_dir . $next_extension_file;
                }
            }
        }

        if ($file_exists) {
            $filePropsArray = [
                'file_on_registration'      => $file,
                'file_on_registration_path' => $file_path,
                'file_on_registration_url'  => '/storage/' . $dir_path . '/' . $file,
                'image_preview_width'       => with(new Settings)->getImgPreviewWidth(),
                'filename_extension'        => $filename_extension,
                'extension_filename'        => $extension_filename,
                'is_image'                  => $is_image
            ];
            $file_full_path = base_path() . '/storage/app/public/' . $file_path;
        } else {
            $filePropsArray = [
                'file_on_registration'      => $file,
                'file_on_registration_path' => $file_path,
                'file_on_registration_url'  => $file_full_path
            ];
            $file_full_path = base_path() . '/public/' . $file_path;
        }

        if ( ! empty($previewSizeArray['width'])) {
            $filePropsArray['preview_width']  = $previewSizeArray['width'];
            $filePropsArray['preview_height'] = $previewSizeArray['height'];
        }
        $settingsImageImgProps = with(new Settings)->getCFImageProps($file_full_path, $filePropsArray);

        if ( ! $file_was_found) {
            $settingsImageImgProps['file_info'] = 'File "' . $file . '" not found ';
        }

        return $settingsImageImgProps;

    } // public static function setSettingsFileOnRegistrationProps(string $file = null, bool $skip_non_existing_file = false): array

    /* get additional properties of file_on_registration : path, url, size etc... */
    public function getSettingsFileOnRegistrationPropsAttribute(): array
    {
        return $this->settingsFileOnRegistrationPropsArray;
    }

    /* set additional properties of file_on_registration : path, url, size etc... */
    public function setSettingsFileOnRegistrationPropsAttribute(array $settingsFileOnRegistrationPropsArray)
    {
        $this->settingsFileOnRegistrationPropsArray = $settingsFileOnRegistrationPropsArray;
    }

    // setSettingsFileOnRegistrationPropsAttribute

    //         return Settings::getValidationRulesArray( $request->get('tab_name_to_submit') );
    public static function getValidationRulesArray($tab_name_to_submit = ''): array
    {
//        with(new Settings)->debToFile(print_r( $tab_name_to_submit,true),' getValidationRulesArray  -1 $tab_name_to_submit::');
        $validationRulesArray = [
            'common_settings' => [
                'site_name'         => 'required|max:255',
                'site_heading'      => 'required|max:255',
                'site_subheading'   => 'required|max:255',
                'copyright_text'    => 'required|max:255',
                'contact_us_email'  => 'required|email',
                'contact_us_phone'  => 'required',
                'noreply_email'     => 'required|email',
                'support_signature' => 'required',

            ],
            'site_content'    => [
                'home_page_ref_items_per_pagination'  => 'required|numeric',
                'news_per_page'                       => 'required|numeric',
                'account_register_details_text'       => 'nullable',
                'account_register_avatar_text'        => 'nullable',
                'account_register_subscriptions_text' => 'nullable',
                'account_register_confirm_text'       => 'nullable',
                'account_contacts_us_text'            => 'nullable',
            ],
            'administration_part' => [
                'backend_per_page'         => 'required|numeric',
                'backend_todo_tasks_popup' => 'nullable',
            ],

            'users' => [
                'verification_token_days_expired' => 'required|numeric',
                'allow_facebook_authorization'    => 'nullable',
                'allow_google_authorization'      => 'nullable',
                'allow_linkedin_authorization'    => 'nullable',
                'allow_github_authorization'      => 'nullable',
                'allow_twitter_authorization'     => 'nullable',
                'allow_instagram_authorization'   => 'nullable',
        ],

            'votes' => [
//                'quizQualityOptions'=> '',
                'most_rating_quiz_quality_on_homepage' => 'required|numeric',
                'most_votes_taggable_on_homepage'      => 'required|numeric',
            ],
            'news'  => [
                'latest_news_on_homepage'              => 'required|numeric',
                'infinite_scroll_rows_per_scroll_step' => 'required|numeric',
                'similar_news_on_limit'                => 'required|numeric',
                'other_news_on_limit'                  => 'required|numeric',
                'feed_items_on_limit'                  => 'required|numeric',
                'feed_import_creator_id'               => 'required|numeric', // Users selection
            ],
            'services'  => [
                'last_sitemapping_results'              => 'nullable',
            ],
        ];

        $returnValidationRules= ! empty($validationRulesArray[$tab_name_to_submit]) ? $validationRulesArray[$tab_name_to_submit] : $validationRulesArray;
//        with(new Settings)->debToFile(print_r( $returnValidationRules,true),'  TEXT  -1 $returnValidationRules::');
        return $returnValidationRules;
    }

}