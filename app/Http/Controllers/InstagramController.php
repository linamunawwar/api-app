<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\InstagramAuth;
use GuzzleHttp\Client;

class InstagramController extends Controller
{

    public $authData;

    public $auth_table = 'instagram_auths';

    private $client_id = "324650489667076";
    private $client_secret = "e6b85401ce263228cd69145ba40f6bad";
    private $redirect_uri = 'https://google.com/';

    public function __construct(){

    }

    public function generateIGToken(){

        $ig_atu = "https://api.instagram.com/oauth/access_token";

        $ig_data = [];

        $ig_data['client_id'] = "324650489667076"; //replace with your Instagram app ID

        $ig_data['client_secret'] = "e6b85401ce263228cd69145ba40f6bad"; //replace with your Instagram app secret

        $ig_data['grant_type'] = 'authorization_code';

        $ig_data['redirect_uri'] = url('/'). '/google.com/'; //create this redirect uri in your routes web.php file

        $ig_data['code'] = "AQBuFM20lg09jPo5yPLqm9IpvLopEIBIGlxaICvqLvvBtsIsrj0CBYGC2MS_SUyoc_SNWJUrRVJDZNENlQU9zvGEuvAp4GxyeRdQvC68TCFNp9Huz_PdOLfcBZc_fvEzFhzy365b14_FA4hHarVYcqJvvSfyPiw_vK7Dj-PkKXotUeD64wwOzbHJIOAuFLZAxB3FFk5p0I79ke4jZNV2IAst3IbAMZD99i3k1oNz0dxjTQ"; //this is the code you received in step 1 after app authorization

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $ig_atu);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($ig_data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $ig_auth_data = curl_exec($ch);

        curl_close ($ch);

        $ig_auth_data = json_decode($ig_auth_data, true);
        dd($ig_auth_data);

        if (!isset($ig_auth_data['error_message'])) {

            $this->authData['access_token'] = $ig_auth_data['access_token'];

            $this->authData['user_id'] = $ig_auth_data['user_id'];

            $ig_auth = new InstagramAuth();
            $ig_auth->access_token = $this->authData['access_token'];
            $ig_auth->user_id = $this->authData['user_id'];
            $ig_auth->valid_till = time();
            $ig_auth->expires_in = 3600;
            $ig_auth->save();

        }

    }


    public function refreshIGToken($short_access_token){

        $client_secret = "63a0fad8218c422116c7f861fbf683a0"; //replace with your Instagram app secret

        $ig_rtu = 'https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret='.$client_secret.'&access_token='.$short_access_token;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $ig_rtu);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $ig_new = curl_exec($ch);

        curl_close ($ch);

        $ig_new = json_decode($ig_new, true);

        if (!isset($ig_new['error'])) {

            $this->authData['access_token'] = $ig_new['access_token'];

            $this->authData['expires_in'] = $ig_new['expires_in'];

            DB::table('instagram_auths')
            ->where('access_token', '<>', '')
            ->update([
                'access_token'  => $ig_new['access_token'],
                'valid_till'    => time(),
                'expires_in'    => $ig_new['expires_in']
            ]);

        }

    }


    public function getMedia(){

        /*check token available and valid*/

        $igData = DB::table('instagram_auths');

        if ($igData->count() > 0) {

            $igDataResult = $igData->first();

            $curTimeStamp = time();

            if (($curTimeStamp-$igDataResult->valid_till) >= $igDataResult->expires_in) {
                
                $this->refreshIGToken($igDataResult->access_token);

            }else{

                $this->authData['access_token'] = $igDataResult->access_token;
                $this->authData['user_id'] = $igDataResult->user_id;

            }

        }else{
            $this->generateIGToken();
        }

        /*check token available and valid*/

        $ig_graph_url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,username,timestamp,caption&access_token='.$this->authData['access_token'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $ig_graph_url);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $ig_graph_data = curl_exec($ch);

        curl_close ($ch);

        $ig_graph_data = json_decode($ig_graph_data, true);

        $ig_photos = [];

        if (!isset($ig_graph_data['error'])) {

            foreach ($ig_graph_data['data'] as $key => $value) {

                if ($value['media_type'] == 'IMAGE') {
                    $ig_photos[] = $value['media_url'];
                }
                
            }

        }

        //use this if want json response
        //return response()->json($igPhotos);

        return $ig_photos;

    }

    public function redirectToInstagramProvider()
    {
        // $appId = config('services.instagram.client_id');
        // $redirectUri = urlencode(config('services.instagram.redirect'));
        return redirect()->to("https://api.instagram.com/oauth/authorize?app_id={$this->client_id}&redirect_uri={$this->redirect_uri}&scope=user_profile,user_media&response_type=code");
    }

    public function instagramProviderCallback(Request $request)
    {
        $code = $request->code;
        if (empty($code)) return redirect()->route('home')->with('error', 'Failed to login with Instagram.');

        $client = new Client();

        // Get access token
        $response = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
            'form_params' => [
                'app_id' => $this->client_id,
                'app_secret' => $this->client_secret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirect_uri,
                'code' => $code,
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            return redirect()->route('home')->with('error', 'Unauthorized login to Instagram.');
        }

        $content = $response->getBody()->getContents();
        $content = json_decode($content);

        $this->authData['access_token'] = $content->access_token;
        $this->authData['user_id'] = $content->user_id;
        $find_user = InstagramAuth::where('user_id',$this->authData['user_id'])->first();
        if($find_user){
            InstagramAuth::where('user_id',$this->authData['user_id'])->update([
                'access_token' => $this->authData['access_token'],
                'valid_till'=> time(),
                'expires_in'=>3600
            ]);
        }else{
            $ig_auth = new InstagramAuth();
            $ig_auth->access_token = $this->authData['access_token'];
            $ig_auth->user_id = $this->authData['user_id'];
            $ig_auth->valid_till = time();
            $ig_auth->expires_in = 3600;
            $ig_auth->save();
        }

        // Get user info
        $response = $client->request('GET', "https://graph.instagram.com/me?fields=id,username,account_type&access_token={$this->authData['access_token']}");

        $content = $response->getBody()->getContents();
        $oAuth = json_decode($content);

        // Get instagram user name 
        $username = $oAuth->username;
        dd($oAuth);
        // do your code here
    }

    public function getUserInfo()
    {
        $igData = DB::table('instagram_auths');

        if ($igData->count() > 0) {

            $igDataResult = $igData->first();

            $curTimeStamp = time();

            if (($curTimeStamp-$igDataResult->valid_till) >= $igDataResult->expires_in) {
                
                $this->refreshIGToken($igDataResult->access_token);

            }else{

                $this->authData['access_token'] = $igDataResult->access_token;
                $this->authData['user_id'] = $igDataResult->user_id;

            }

        }else{
            $this->generateIGToken();
        }
        
        $client = new Client();
        // Get user info
        $response = $client->request('GET', 'https://graph.instagram.com/me?fields=id,username,account_type&access_token='.$this->authData['access_token']);


        $content = $response->getBody()->getContents();
        $oAuth = json_decode($content);

        return view('instagram.index',['oAuth'=> $oAuth]);
    }

    public function getSearchHashtag()
    {
        return view('instagram.hashtag');
    }

    public function postSearchHashtag()
    {
        $igData = DB::table('instagram_auths');

        if ($igData->count() > 0) {

            $igDataResult = $igData->first();

            $curTimeStamp = time();

            if (($curTimeStamp-$igDataResult->valid_till) >= $igDataResult->expires_in) {
                
                $this->refreshIGToken($igDataResult->access_token);

            }else{

                $this->authData['access_token'] = $igDataResult->access_token;
                $this->authData['user_id'] = $igDataResult->user_id;

            }

        }else{
            $this->generateIGToken();
        }
        
        $client = new Client();
        // Get hashtag search
        // $response = $client->request('GET', 'https://graph.facebook.com/v12.0/ig_hashtag_search?user_id='.$this->authData['user_id'].'&q=bluebottle&access_token='.$this->authData['access_token']);

        // $content = $response->getBody()->getContents();
        // $data = json_decode($content);
        $data['user'][1]='@user1';
        $data['post'][1]='#hashtag#hashtag#hashtag';
        $data['user'][2]='@user1';
        $data['post'][2]='#hashtag#hashtag#hashtag';
        $data['user'][3]='@user1';
        $data['post'][3]='#hashtag#hashtag#hashtag';
        $data['user'][4]='@user1';
        $data['post'][4]='#hashtag#hashtag#hashtag';
        $data['user'][5]='@user1';
        $data['post'][5]='#hashtag#hashtag#hashtag';
        return view('instagram.hashtag',['data'=> $data]);
    }
}