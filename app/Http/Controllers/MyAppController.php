<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Traits\funcsTrait;
use App\Settings;
use App\library\CheckValueType;


class MyAppController extends BaseController
{
    protected $data_strip_tags = true;
    protected $clear_doubled_spaces = true;
    protected $stripslashes = true;
    use funcsTrait;
    public function __construct()
    {
    }

    protected function getAppParameters(bool $isBackend= false, array $additiveParameters= [], array $predefinedParametersArray= [] )
    {
//        echo '<pre>$additiveParameters::'.print_r($additiveParameters,true).'</pre>';
        $retArray= [];
        $skipSettings= ['csrf_token', 'empty_img_url'];
        if ( $isBackend ) {
            $retArray['csrf_token']          = csrf_token();
            $retArray['backend_home_url']    = config('app.backend_home_url', '');
            $backend_per_page_value          = Settings::getValue('backend_per_page', CheckValueType::cvtInteger, 10);
            $backend_todo_tasks_popup        = Settings::getValue('backend_todo_tasks_popup', CheckValueType::cvtBool, "N");
            $retArray['backend_per_page']    = $backend_per_page_value;
            $retArray['current_admin_template'] = $this->getBackendTemplateName();
//            echo '<pre>$backend_todo_tasks_popup::'.print_r($backend_todo_tasks_popup,true).'</pre>';
//            die("-1 XXZ");
            $retArray['backend_todo_tasks_popup'] = $backend_todo_tasks_popup;
        } else {
            $retArray['frontend_home_url']      = config('app.frontend_home_url', '');
            $retArray['frontend_template_name'] = $this->getFrontendTemplateName();
        }

        if ( in_array('csrf_token',$additiveParameters) ) {
            $retArray['csrf_token'] = csrf_token();
        }

        if ( in_array('empty_img_url',$additiveParameters) ) {
            $retArray['empty_img_url']  = config('app.empty_img_url');
        }

//        echo '<pre>$retArray::'.print_r($retArray,true).'</pre>';
//        echo '<pre>$skipSettings::'.print_r($skipSettings,true).'</pre>';
//        echo '<pre>00 $additiveParameters::'.print_r($additiveParameters,true).'</pre>';
//        die("-1 XXZ");
        foreach( $skipSettings as $next_key=> $next_setting ) {
            if ( !empty($additiveParameters[$next_setting]) ) {
                unset($additiveParameters[$next_setting]);
            }
        }
//        echo '<pre>33 $additiveParameters::'.print_r($additiveParameters,true).'</pre>';
        $settingValues              = Settings::getSettingsList( $additiveParameters , -2, __FILE__, __LINE__ );
        if ( !empty($settingValues) ) {
            foreach( $settingValues as $next_key=>$next_value ) {
                $retArray[$next_key] = $next_value;
            }
        }

        foreach( $skipSettings as $next_setting ) {
            if ( !empty($settingValues[$next_setting]) ) {
                unset($settingValues[$next_setting]);
            }
        }

        foreach( $predefinedParametersArray as $next_key=>$next_value ) {
            $retArray[$next_key] = $next_value;
        }
        foreach( $additiveParameters as $next_additive_parameter_name ) {
            if ( !isset($retArray[$next_additive_parameter_name]) ) {
                $retArray[$next_additive_parameter_name]= '';
            }
        }

        $action = app('request')->route()->getAction();
        $controller = class_basename($action['controller']);
        list($controller, $action) = explode('@', $controller);
        $retArray['current_controller_name']= $controller;
        $retArray['current_action_name']= $action;

        if ( empty($retArray['site_name']) ) {
            $settingValues              = Settings::getSettingsList(['site_name'], -3, __FILE__, __LINE__);
            if ( !empty($settingValues['site_name']) ) {
                $retArray['site_name'] = $settingValues['site_name'];
            }
        }

        if ( empty($retArray['copyright_text']) ) {
            $settingValues              = Settings::getSettingsList(['copyright_text'], -4, __FILE__, __LINE__);
            if ( !empty($settingValues['copyright_text']) ) {
                $retArray['copyright_text'] = $settingValues['copyright_text'];
            }
        }
        $retArray['is_developer_comp']       = $this->isDeveloperComp();
//        echo '<pre>$this->isRunningUnderDocker()::'.print_r($this->isRunningUnderDocker(),true).'</pre>';
//        die("-1 XXZ===");
        $retArray['is_running_under_docker'] = $this->isRunningUnderDocker();
        if ( !empty( $_REQUEST['is_debug']) ) {
            $retArray['is_debug'] = $_REQUEST['is_debug'];
            $retArray['is_developer_comp'] = 1;
        }
        return $retArray;
    }


}
