<?php

namespace App\Http\Controllers;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\funcsTrait;

class GraphController extends Controller
{
    private $api;
    use funcsTrait;
    public function __construct(Facebook $fb)
    {
        parent::__construct();
        $this->middleware(function ($request, $next) use ($fb) {
            $fb->setDefaultAccessToken(Auth::user()->token);
            $this->api = $fb;
            return $next($request);
        });
    }

    public function retrieveUserProfile(){
        try {
            $params = "first_name,last_name,age_range,gender";
            $user = $this->api->get('/me?fields='.$params)->getGraphUser();
            dd($user);

        } catch (FacebookSDKException $e) {
        }
    }

    public function publishToProfile(Request $request){
        $new_message= $request->message;
        $new_message= 'EEE $new_message';
        $this->debToFile(print_r($new_message,true),' publishToProfile  -1 $new_message::');
        try {
            $response = $this->api->post('/me/feed', [
                'message' => $new_message
            ])->getGraphNode()->asArray();
            if($response['id']){
                $this->debToFile(print_r($response['id'],true),' publishToProfile  -2 $response[\'id\']::');
                // post created
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
    public function publishToProfileWithImage(Request $request){
        $new_message= $request->message;
        $new_message= 'EEE $new_message';
        $absolute_image_path = '/var/www/larave/storage/app/images/lorde.png';
        $this->debToFile(print_r($new_message,true),' publishToProfile  -1 $new_message::');
        try {
            $response = $this->api->post('/me/feed', [
                'message' => $new_message,
                'source'    =>  $this->api->fileToUpload('/path/to/file.jpg')
            ])->getGraphNode()->asArray();

            if($response['id']){
                // post created
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
    public function getPageAccessToken($page_id){
        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $this->api->get('/me/accounts', Auth::user()->token);
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        try {
            $pages = $response->getGraphEdge()->asArray();
            foreach ($pages as $key) {
                if ($key['id'] == $page_id) {
                    return $key['access_token'];
                }
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }

    public function publishToPage(Request $request){

        $page_id = 'YOUR_PAGE_ID';

        try {
            $post = $this->api->post('/' . $page_id . '/feed', array('message' => $request->message), $this->getPageAccessToken($page_id));

        $post = $post->getGraphNode()->asArray();

        dd($post);

    } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
    
}