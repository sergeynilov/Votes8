<?php namespace App\library {

    use Auth;
    use DB;
    use Carbon\Carbon;
    use App\User;
    use App\Chat;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Storage;
    use App\Vote;
    use App\VoteItem;
    use App\VoteItemUsersResult;
    use App\ContactUs;
    use App\SiteSubscription;
    use App\Settings;
    use App\Payment;
    use App\Http\Traits\funcsTrait;

    class viewFuncs // https://laravel.com/docs/5.4/blade
    {
        use funcsTrait;
        

        public function wrpConcatArray($arr, $splitter = ',', $skip_empty = true, $skip_last_delimiter = true) : string
        {
            return $this->concatArray($arr, $splitter, $skip_empty, $skip_last_delimiter);
        }


        public function wrpFormattedCurrency($val) : string
        {
            return $this->getFormattedCurrency($val);
        }


        public function getFooterParams() : array
        {
            return [
                'csrf_token'=> csrf_token(),
                'backend_home_url'=> config('app.backend_home_url', ''),
                'empty_img_url'=> config('app.empty_img_url', '') ];
        }

        // btn-primary
        public function showAppIcon(string $icon, $icon_type, $title= '', $id= '') : string
        {
            $appTitleIconsList  = config('app.appTitleIconsList');
            if ( empty($appTitleIconsList[$icon]) ) return '';
            if ($icon_type=='warning') {
                return '<i class="' . $appTitleIconsList[$icon] . ' app_title_icon_warning " title="'.$title.'" '.(!empty($id)?' id="'.$id.'"':'').'></i>';
            }
            if ($icon_type=='transparent_on_white') {
                return '<i class="' . $appTitleIconsList[$icon] . ' app_title_icon_transparent_on_white" title="'.$title.'" '.(!empty($id)?' id="'.$id.'"':'').'></i>';
            }
            if ($icon_type=='selected_dashboard') {
                return '<i class="' . $appTitleIconsList[$icon] . ' app_title_icon_selected_dashboard" title="'.$title.'" '.(!empty($id)?' id="'.$id.'"':'').' ></i>';
            }
            return '<i class="'.$appTitleIconsList[$icon].' app_title_icon" title="'.$title.'" ></i>';
        }

        public function wrpIsFakeEmail(string $email) : string
        {
            return $this->isFakeEmail($email);
        }

        public function getNewVotesCount() : int
        {
            $votes_count = Vote
                ::getByStatus('N', true)
                ->count();
            return $votes_count;
        }

        public function getNewContactUsCount() : int
        {
            $contact_us_count = 11;//with ( new ContactUs)->scopeGetByAccepted(0)->count();
            return $contact_us_count;
        }

        public function addHttpPrefix(string $url) : string
        {
            return $this->makeAddHttpPrefix($url);
        }

        public function file(string $input_id,  string $classes= '', array $propsArray= []) : string
        {
            $props_str= '';
            foreach( $propsArray as $next_key=>$next_value ) {
                if ( empty($next_value) or empty($next_key)) continue;
                $props_str.= $next_key . '="' .$next_value.'" ';
            }
            return '<input  class="'.$classes.'"  id="'.$input_id. '" name="'.$input_id.'"  type="file"> ' . $props_str;
        }

        public function textarea(string $input_id, $value, string $classes= '', array $propsArray= []) : string
        {
            $props_str= '';
            foreach( $propsArray as $next_key=>$next_value ) {
                if ( empty($next_value) or empty($next_key)) continue;
                $props_str.= $next_key . '="' .$next_value.'" ';
            }
            return '<textarea class="'.$classes.'" id="'.$input_id. '" name="'.$input_id.'" ' . $props_str .' >'.$value.'</textarea>';
        }

        public function select(string $input_id, array $optionsArray,  $selection_value, string $classes= '', array $propsArray= []) : string {
            $props_str= '';
            $input_name= $input_id;
            foreach( $propsArray as $next_key=>$next_value ) {
                if ( ( empty($next_value) and strlen($next_value)==0 ) or empty($next_key)) continue;
                if ( strtolower($next_key) == 'id' ) {
                    $input_id= $next_value;
                    continue;
                }
                if ( strtolower($next_key) == 'name' ) {
                    $input_name= $next_value;
                    continue;
                }
                $props_str.= $next_key . '="' .$next_value.'" ';
            }

            $options_str= '';
            foreach( $optionsArray as $key_id => $key_label ) {
                $label_code= '';
                if ( ( empty($key_id) and strlen($key_id)==0 ) and !empty($key_label) ) {
                    $label_code= 'label="'.$key_label.'"';
                    $key_label= '';
                }
                if ( is_array($selection_value) ) {

                    $is_checked= false;
                    foreach( $selection_value as $next_value ) {
                        if ( in_array($key_id,$selection_value) ) {
                            $is_checked= true;
                            break;
                        }
                    }
                    $options_str .= '<option value="' . $key_id . '" ' . $label_code. ( $is_checked ? ' selected ' : '') . '>' . $key_label . '</option>';
                } else {
                    $options_str .= '<option value="' . $key_id . '" ' . $label_code . ( (string)$key_id == (string)$selection_value ? ' selected ' : '' ) . '>' . $key_label . '</option>';
                }
            }
            $ret_html= '<select class="'.$classes.'" id="'.$input_id. '" name="'.$input_name.'" ' . $props_str .' >' . $options_str . '</select>';
//            echo '<pre>$ret_html::'.print_r(htmlspecialchars($ret_html),true).'</pre>';
            return $ret_html;
        }

        public function text(string $input_id, $value, string $classes= '', array $propsArray= []) : string {
            $props_str= '';
            $input_name= $input_id;
            foreach( $propsArray as $next_key=>$next_value ) {
                if ( empty($next_value) or empty($next_key)) continue;
                if ( strtolower($next_key) == 'id' ) {
                    $input_id= $next_value;
                    continue;
                }
                if ( strtolower($next_key) == 'name' ) {
                    $input_name= $next_value;
                    continue;
                }
                $props_str.= $next_key . '="' .$next_value.'" ';
            }
            return '<input class="'.$classes.'" value="'.htmlspecialchars($value).'" id="'.$input_id. '" name="'.$input_name.'" type="text" ' . $props_str .' >';
        }

        public function password(string $input_id, string $classes= '', array $propsArray= []) : string {
            $props_str= '';
            foreach( $propsArray as $next_key=>$next_value ) {
                if ( empty($next_value) or empty($next_key)) continue;
                $props_str.= $next_key . '="' .$next_value.'" ';
            }
            return '<input class="'.$classes.'" id="'.$input_id. '" name="'.$input_id.'" type="password" ' . $props_str .' >';
        }

    /* show styling checkbox with value, default label and title(optional) */
        public function showStylingCheckbox(string $input_id, $value, bool $checked= false, $title= '', array $propsArray= []) : string {
            $props_str= '';
            foreach( $propsArray as $next_key=>$next_value ) {
                if ( empty($next_value) or empty($next_key)) continue;
                if ( $next_key == 'additive_class' ) continue;
                $props_str.= $next_key . '="' .$next_value.'" ';
            }
            $additive_class= '';
            if ( !empty($propsArray['additive_class']) ) {
                $additive_class= $propsArray['additive_class'];
            }
            return '
           <input class="editable_field styling_checkbox '.$additive_class.' " value="'.$value.'" id="'.$input_id.
                   '" name="'.$input_id.'" type="checkbox" '.( $checked?' checked ':'' ) . $props_str .' >
           <label for="'.$input_id.'">'.$title.'</label>';
        }


        public function wrpCapitalize($str) {
            return $this->Capitalize($str);
        }

        ////// USER BLOCK START /////

/*        public function checkUserLogged() : bool {
            return Auth::check();
        } // public function checkUserLogged() : bool {*/


        public function checkLoggedUserHasImage() : bool {
            if (!Auth::check()) return false;
            $loggedUser= Auth::user();
            if ( empty($loggedUser->avatar) ) return false;
            $dir_path = User::getUserAvatarPath($loggedUser->id, $loggedUser->avatar);
            $file_exists    = Storage::disk('local')->exists('public/' . $dir_path);
            return $file_exists;
        }

        public function getLoggedUserImage() : string {
            if (!Auth::check()) return '';
            $loggedUser= Auth::user();
            return '/storage/' . User::getUserAvatarPath($loggedUser->id, $loggedUser->avatar);
        }

        public function getLoggedUserFullPhoto() : string {
            if (!Auth::check()) return '';
            $loggedUser= Auth::user();
            return '/storage/' . User::getUserFullPhotoPath($loggedUser->id, $loggedUser->full_photo);
        }

        public function getLoggedUserDisplayName() : string {
            if (!Auth::check()) return '';
            $loggedUser= Auth::user();
            $ret= $loggedUser->full_name;
            if (!empty($ret)) return ( $this->isDeveloperComp() ? $loggedUser->id.' : ' : '' ) . $ret;
            return ( $this->isDeveloperComp() ? $loggedUser->id.' : ' : '' ) . $loggedUser->username;
        }

        public function getLoggedUserEmail() : string {
            if (!Auth::check()) return '';
            $loggedUser= Auth::user();
            return ( $this->isDeveloperComp() ? $loggedUser->id.' : ' : '' ) . $loggedUser->email;
        }

        public function getLoggedUserDisplayAccessGroupsName() : string {
            if (!Auth::check()) return '';
            $loggedUser= Auth::user();
            return User::getUsersGroupsByUserId($loggedUser->id, true);
        }

        public function loggedUserHasAdminAccess(): bool
        {
            $has_access = false;
            if (Auth::check()) {
                $loggedUserAccessGroups = session('loggedUserAccessGroups');
//                $logged_user_ip = session('logged_user_ip');
                if ( ! empty($loggedUserAccessGroups) and is_array($loggedUserAccessGroups)) {
                    foreach ($loggedUserAccessGroups as $next_key => $nextLoggedUserAccessGroup) {
                        if ($nextLoggedUserAccessGroup['group_id'] == USER_ACCESS_ADMIN) {
                            $has_access = true;
                        }
                    }
                } // if ( !empty($loggedUserAccessGroups) and is_array($loggedUserAccessGroups) ) {
            }
            return $has_access;
        } // public function loggedUserHasAdminAccess() : bool {

        public function loggedUserHasSubscription(): bool
        {
            if (Auth::check()) {
                $loggedUserSubscription = session('loggedUserSubscription');
                if ( !empty($loggedUserSubscription) ) {
                    return true;
                }
            }
            return false;
        } // public function loggedUserHasSubscription() : bool {


        public function showLoggedUserSubscription(): string
        {
//            return ( $this->isRunningUnderDocker() ? '<span style="color: red;">Docker</span>' : '');
            if ( !$this->loggedUserHasSubscription() ) return '';
            $loggedUserSubscription = session('loggedUserSubscription');
            $mysql_to_carbon_datetime_format       = config('app.mysql_to_carbon_datetime_format', 'Y-m-d H:i:s');
            $subscription_expire_warning_count     = config('app.subscription_expire_warning_count', 3);
            $now = Carbon::now();
            echo '<pre>$subscription_expire_warning_count::'.print_r($subscription_expire_warning_count,true).'</pre>';
            $icon_type= 'transparent_on_white';
//            echo '<pre>showLoggedUserSubscription $loggedUserSubscription::'.print_r($loggedUserSubscription,true).'</pre>';
            if ( $loggedUserSubscription->is_free ) {

                $trialEndsAt= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $loggedUserSubscription->trial_ends_at);
                if ( $trialEndsAt->isPast() ) {
                    $current_subscription_text = $loggedUserSubscription->service_subscription_name . ' is expired';
                    $icon_type= 'warning';
                } else {
//                    $date = Carbon::parse('2016-09-17 11:00:00');

                    $diff = $trialEndsAt->diffInDays($now);
                    echo '<pre>$diff::'.print_r($diff,true).'</pre>';
//                    die("-1 XXZ");
                    if ( $subscription_expire_warning_count>= $diff ) {
                        $current_subscription_text = $loggedUserSubscription->service_subscription_name . ', active till ' . $this->getFormattedDate
                            ($loggedUserSubscription->trial_ends_at). '. ' . $diff . ' day(s) left';
                        $icon_type= 'warning';
                    } else {
                        $current_subscription_text = $loggedUserSubscription->service_subscription_name . ', active till ' . $this->getFormattedDate
                            ($loggedUserSubscription->trial_ends_at);
                    }
                }

            } else {

                $endsAt= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $loggedUserSubscription->ends_at);
                if ( $endsAt->isPast() ) {
                    $current_subscription_text = $loggedUserSubscription->service_subscription_name . ' is expired';
                    $icon_type= 'warning';
                } else {
                    $current_subscription_text = $loggedUserSubscription->service_subscription_name . ', active till ' . $this->getFormattedDate($loggedUserSubscription->ends_at);
                }
                
            }
//            echo '<pre>$current_subscription_text::'.print_r($current_subscription_text,true).'</pre>';
/*     [attributes:protected] => Array
        (
            [id] => 5
            [user_id] => 6
            [name] => Votes services
            [stripe_id] => sub_G6R9MKHCSxbASD
            [stripe_status] => active
            [stripe_plan] => plan_G2hLjiYeoQ87cu
            [quantity] => 1
            [trial_ends_at] => 2019-12-03 06:31:24
            [ends_at] =>
            [source_service_subscription_id] => 1
            [is_free] => 1
            [created_at] => 2019-11-02 06:31:24
            [updated_at] => 2019-11-02 06:31:24
            [service_subscription_name] => Free
            [service_subscription_active] => 1
            [service_subscription_is_premium] => 0
            [service_subscription_is_free] => 1
            [service_subscription_description] => Free description Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
            [service_subscription_color] => #3e3e3e
            [service_subscription_background_color] => #cccccc
            [service_subscription_price] =>
        ) */
//            die("-1 XXZ+++");
            return  $this->showAppIcon('subscription', $icon_type,'Subscribed '. $current_subscription_text ) ;
//            return ( $this->isRunningUnderDocker() ? '<i class="fa fa-cubes" style="color: red;" title="Running Under Docker"></i>' : '');
        }

        public function showRunningUnderSymbol(): string
        {
//            return ( $this->isRunningUnderDocker() ? '<span style="color: red;">Docker</span>' : '');
            return ( $this->isRunningUnderDocker() ? '<i class="fa fa-cubes" style="color: red;" title="Running Under Docker"></i>' : '');
        }

        public function showAppVersion(): string
        {
            $app_version= '';
            if ( file_exists(public_path('app_version.txt')) ) {
                $app_version = File::get( 'app_version.txt');
            }
            return $app_version;
        }


        public function getUserDisplayAccessGroupsName($user_id) : string
        {
            if (empty($user_id)) {
                return '';
            }

            return User::getUsersGroupsByUserId($user_id, true);
        }

        public function wrpGetUserSexLabel($sex)
        {
            return User::getUserSexLabel($sex);
        }

        ////// USER BLOCK END /////


        ////// SITE SUBSCRIPTION BLOCK START /////
        public function wrpGetSiteSubscriptionActiveLabel(string $active): string
        {
            return SiteSubscription::getSiteSubscriptionActiveLabel($active);
        }
        ////// SITE SUBSCRIPTION BLOCK END /////


        ////// VOTE ITEM BLOCK START /////
        public function wrpGetVoteItemIsCorrectLabel(string $contact_us): string
        {
            return VoteItem::getVoteItemIsCorrectLabel($contact_us);
        }
        ////// VOTE ITEM BLOCK END /////


        ////// CONTACT US BLOCK START /////
        public function wrpGetContactUsAcceptedLabel(string $contact_us): string
        {
            return ContactUs::getContactUsAcceptedLabel($contact_us);
        }
        ////// CONTACT US BLOCK END /////


        ////// VOTE ITEM USERS RESULT BLOCK START /////
        public function wrpGetVoteItemUsersResultIsCorrectLabel(string $is_correct): string
        {
            return VoteItemUsersResult::getVoteItemUsersResultIsCorrectLabel($is_correct);
        }
        ////// VOTE ITEM USERS RESULT BLOCK END /////


        ////// VOTE BLOCK START /////
        public function wrpGetVoteIsHomepageLabel(string $is_homepage): string
        {
            return Vote::getVoteIsHomepageLabel($is_homepage);
        }
        ////// VOTE BLOCK END /////



        ////// PAYMENT BLOCK START /////
        public function wrpGetPaymentTypeLabel(string $payment_type): string
        {
            return Payment::getPaymentTypeLabel($payment_type);
        }
        ////// PAYMENT BLOCK END /////


        ////// EDITOR INPUTS BLOCK BEGIN /////



        public function clearFix(): string
        {
            return '
		<span class="visible-sm">
			<div class="clearfix visible-sm-block"></div>
        </span>
		<span class="visible-md">
			<div class="clearfix visible-md-block"></div>
		</span>
		<span class="visible-lg">
			<div class="clearfix visible-lg-block"></div>
		</span>
            
            ';
        }

        public function clearFix2Columns($odd, $index0): string
        {
            if ( ! $odd) {
                return '
		<span class="visible-sm">
			<div class="clearfix visible-sm-block"></div>
        </span>
		<span class="visible-md">
			<div class="clearfix visible-md-block"></div>
		</span>
		<span class="visible-lg">
			<div class="clearfix visible-lg-block"></div>
		</span>
            
            ';
            }

            return '';
        }

        /*
            $listing_icon_size= Settings::getValue('listing_icon_size');
            $ret_str = '    <a class="fancybox-thumb" title="'.$filename_info.'" rel="fancybox-thumb" href="'.$filename_url.'" title="'.$filename_text.'" alt="'.$filename_text.'">
		<img SRC="'.$filename_url.'" width="'.$listing_icon_size.'px" height="'.$listing_icon_size.'px">
	</a>';

            return $ret_str;
 */

        /* show button with image and js-code from right of the input */
        public function blockImageIconAtRightEnd($img_src, $js_function = '', $hint_text = '', $style_code = '', $link_id = '', $image_class = ''): string
        {
            return '
    </td>
		<td >
	    	 <a class="fancybox-thumb" title="' . $hint_text . '" rel="fancybox-thumb" href="' . $img_src . '" title="' . $hint_text . '" alt="' . $hint_text . '">
		    	<img src="' . $img_src . '" alt="' . $hint_text . '" title="' . $hint_text . '" class="' . $image_class . '" onclick="' . $js_function . '">
		    </a>	
		</td>
	</tr>
</table>            ';
        }

        /* show image from right of the input */
        public function blockImageAtRightEnd($img_src, $js_function = '', $hint_text = '', $style_code = '', $link_id = '', $image_class = ''): string
        {
            return '
    </td>
		<td >
			<img src="' . $img_src . '" alt="' . $hint_text . '" title="' . $hint_text . '" class="' . $image_class . '" onclick="' . $js_function . '">
		</td>
	</tr>
</table>            ';
        }


        public function blockButtonAtRightStart(): string
        {
            return '
    <table>
	<tr>
		<td style="width: 99%">';
        }


        public function blockButtonAtRightEnd($js_function, $hint_text, string $style_code = '', string $link_id = '', string $button_class = ''): string
        {
            return '</td>
		<td >
			<a class="a_block_button_link" ' . (! empty($link_id) ? 'id="' . $link_id : '') . '" onclick="' . $js_function . '" data-toggle="tooltip" data-html="true" data-placement="top" title=""
			data-original-title="' . $hint_text . '"><i class="' . (! empty($button_class) ? $button_class : ' fa fa-edit ') . '"  style="' . (! empty($style_code) ? $style_code : '  ') . '"></i></a>
		</td>
	</tr>
</table>'; // padding-bottom: 20px;
        }


        public function blockOnlyCheckboxButtonAtRightEnd(string $additive_id = ''): string
        {
            return '
                                                        </td>
                                                        <td width="5%" >

                                                            <label class="btn btn-primary btn-sm p-10 checked active">
                                                                <input class="only_checkbox" value="1" autocomplete="off" id="cbx_clear_image' . $additive_id . '" name="cbx_clear_image' . $additive_id . '" type="checkbox" style="display: none;">
                                                                <span class="fa fa-check" aria-hidden="true"></span>Clear
                                                            </label>


                                                        </td>
                                                        </tr>
                                                    </table>
            
            
            '; // padding-bottom: 20px;
        }


        public function blockCheckBoxAtRightStart(): string
        {
            return '
    <table>
	<tr>
		<td style="width: 90%">';
        }

        public function blockCheckBoxAtRightEnd($js_function, $hint_text, string $style_code = '', string $checkbox_id = '', string $checkbox_class = ''): string
        {
            return '</td>
		<td >
             <input class="only_checkbox" value="1" autocomplete="off" id="' . $checkbox_id . '" name="cbx_clear_image" type="checkbox" style="' /*. (!empty($style_code) ? $style_code : ' padding-bottom: 20px; '  ) */ . '" >&nbsp;' . $hint_text . '

		</td>
	</tr>
</table>';
        }


//    {{ components.blockButtonAtRightStart() }}
//    enter regexp : <input id="input_show_decision_{{ $nextRegexpItem['id'] }}" value="" style="font-family: DejaVu Sans Mono; font-size: 14px;">
//                                                            {{ components.blockButtonAtRightEnd("javascript:editCMSItem('homepage_cms_page_1');", 'Edit selected page in CMS Editor') }}

        ////// EDITOR INPUTS BLOCK END /////


        ////// CMS ITEM BLOCK BEGIN /////
        /* return CmsItem published label from CmsItem's published based on db enum key value/label implementation */
        /*        public function getCmsItemPublishedLabel(string $published) : string {
                    return CmsItem::getCmsItemPublishedLabel($published);
                }
                public function getCmsItemPageTypeLabel(string $page_type) : string {
                    return CmsItem::getCmsItemPageTypeLabel($page_type);
                }*/
        ////// CMS ITEM BLOCK END /////


        public function wrpGetUserStatusLabel(string $status): string
        {
            return User::getUserStatusLabel($status);
        }

        public function wrpGetUserVerifiedLabel(string $verified): string
        {
            return User::getUserVerifiedLabel($verified);
        }
        ////// USER BLOCK END /////

        ////// DATETIME FORMATTING BLOCK START /////
        public function getFormattedDate($date, string $datetime_format = 'mysql', string $output_format= ''): string
        {
            return $this->getCFFormattedDate( $date, $datetime_format, $output_format );
        }

        public function getFormattedDateTime($date, string $datetime_format = 'mysql', string $output_format= ''): string
        {
//            echo '<pre>getFormattedDateTime -9 $output_format ::'.print_r($output_format,true).'</pre><br>';
            return $this->getCFFormattedDateTime($date, $datetime_format, $output_format);
        }


        ////// CHATS BLOCK END /////
        public function wrpGetChatStatusLabel(string $status): string
        {
            return Chat::getChatStatusLabel($status);
        }
        ////// CHATS BLOCK END /////


        ////// COMMON BLOCK BEGIN /////


        /**
         * Limits a phrase to a given number of characters.
         *
         * @param   string   phrase to limit characters of
         * @param   integer  number of characters to limit to
         * @param   string   end character or entity
         * @param   boolean  enable or disable the preservation of words while limiting
         *
         * @return  string
         */
        public function limitChars($str, $limit = 100, $end_char = null, $preserve_words = false): string
        {
            $end_char = ($end_char === null) ? '&#8230;' : $end_char;

            $limit = (int)$limit;

            if (trim($str) === '' OR strlen($str) <= $limit) {
                return $str;
            }

            if ($limit <= 0) {
                return $end_char;
            }

            if ($preserve_words == false) {
                return rtrim(substr($str, 0, $limit)) . $end_char;
            }
            // TO FIX AND DELETE SPACE BELOW
            preg_match('/^.{' . ($limit - 1) . '}\S* /us', $str, $matches);

            return rtrim($matches[0]) . (strlen($matches[0]) == strlen($str) ? '' : $end_char);
        }

        /**
         * Limits a phrase to a given number of words.
         *
         * @param   string   phrase to limit words of
         * @param   integer  number of words to limit to
         * @param   string   end character or entity
         *
         * @return  string
         */
        public function limitWords($str, $limit = 100, $end_char = null): string
        {
            $limit    = (int)$limit;
            $end_char = ($end_char === null) ? '&#8230;' : $end_char;

            if (trim($str) === '') {
                return $str;
            }

            if ($limit <= 0) {
                return $end_char;
            }

            preg_match('/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', $str, $matches);

            // Only attach the end character if the matched string is shorter
            // than the starting string.
            return rtrim($matches[0]) . (strlen($matches[0]) === strlen($str) ? '' : $end_char);
        }



        /* concating long string in $max_length range with substitition chars($add_str), button and possible JS-code $additive_code*/
        public function concatStr(string $str, int $max_length = 0, string $add_str = ' ...', $show_help = false, $strip_tags = true, $additive_code = ''): string
        {
            if ($strip_tags) {
                $str = strip_tags($str);
            }
            $ret_html = $this->limitChars($str, (! empty($max_length) ? $max_length : $this->m_concat_str_max_length), $add_str);
            if ($show_help and strlen($str) > $max_length) {
//			$str = appFuncs::nl2br2($str);
//            $str = addslashes(appFuncs::nl2br2($str));
                $ret_html .= '<i class=" a_link fa fa-object-group" style="font-size:larger;" ' . $additive_code . ' ></i>';
//            $ret_html .= '<i class=" a_link fa fa-life-bouy" style="font-size:larger;" onclick="javascript:showDownloadableDescription(9);"></i>';
//            $ret_html .= '&nbsp;<button type="button" class="btn"  style="padding:1px; border:1px dotted grey;" data-toggle="tooltip" data-placement="bottom" title="'.$str.'">+</button>';
//            echo '<pre>=========$ret_html::'.print_r($ret_html,true).'</pre>';
            }
//        echo '<pre>$ret_html::'.print_r(htmlspecialchars($ret_html),true).'</pre>';
//        die("-1 XXZ");
            return $ret_html;
        }

        public static function nl2br2($string, string $replace_with = "<br>"): string
        {
            if (empty($string)) return '';
            $string = str_replace(array("\r\n", "\r", "\n"), $replace_with, $string);
            return $string;
        }

        public static function getSystemInfo(bool $add_system_info = true): string
        {
            $system_info = '';
            if ($add_system_info) {
                $system_info = '<br>' .
                               'PHP : <b> ' . phpversion() . '</b><br>' .
                               'Apache version:<b> ' . apache_get_version() . '</b><br>';
            }

            $string     = ' Laravel <b>' . app()::VERSION . '</b><br>' .
                          'DEBUG:<b>' . env('APP_DEBUG') . '</b><br>' .
                          'ENV:<b> ' . env('APP_ENV') . '</b><br>' .
                          'DB_CONNECTION:<b> ' . env('DB_CONNECTION') . '</b><br>' .
                          'Prefix:<b>' . \DB::getTablePrefix() . '</b>' . $system_info;
            $db_version = '';
            if (env('DB_CONNECTION') == 'pgsql') {
                $v = DB::select(' SELECT version(); ');
                if ( ! empty($v[0]->version)) {
                    $db_version = 'Db Version:<b>' . $v[0]->version . '</b>';
                }
            }

            return $string . $db_version;
        }
        ////// COMMON BLOCK END /////


    }
}