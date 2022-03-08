<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\SocialAuth;
use App\Models\User;
use Socialite;
use Session;
use PDF;
use Auth;

class TwitterController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    private $consumerKey = "TLAlw9Kk7zJ6uwsI4MmGjUdwb";
    private $consumerSecret = "29LVVNVhYFPwSbEXtIvvqWdN1Nv48pjpxAlE2MlzOQSrzkowOq";     

    public function index()
    {
        $notif = 1;
        $user = User::where('id', \Auth::user()->id)->first();
        $socialAuth = SocialAuth::where('user_id', $user->id)->first();
        if ($socialAuth == null) {
            $notif = 0;
        }
        return view('twitter.index', ['notif' => $notif]);
    }

    public function fetch_twitter(Request $request)
    {
        $string = $request->input('tweetField') ?? "Dev Api";


        $twitter = SocialAuth::query()->first();
        $push = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $twitter->twitter_oauth_token, $twitter->twitter_oauth_token_secrete);
        $push->setTimeouts(10, 15);
        $push->ssl_verifypeer = true;
        $push->get("search/tweets", [
            "q" => "$string", 
            "count" => "100"
        ]);

        $response = response()->json($push->getLastBody());

        return $response;
    }

    public function telusuri($id)
    {
        return view('twitter.telusuri', ['id' => $id]);
    }

    public function show_tweet($id)
    {
        $twitter = SocialAuth::query()->first();
        $push = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $twitter->twitter_oauth_token, $twitter->twitter_oauth_token_secrete);
        $push->setTimeouts(10, 15);
        $push->ssl_verifypeer = true;
        $push->get('statuses/show', [
            'id' => $id,
            "tweet_mode" => "extended"
        ]);        
        
        $response = response()->json($push->getLastBody());
        return $response;
    }

    public function retweets_tweet($id)
    {
        $twitter = SocialAuth::query()->first();
        $push = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $twitter->twitter_oauth_token, $twitter->twitter_oauth_token_secrete);
        $push->setTimeouts(10, 15);
        $push->ssl_verifypeer = true;
        $push->get('statuses/retweets', [
            'id' => $id,
            "tweet_mode" => "extended",
            "count" => "100"
        ]);        
        
        $response = response()->json($push->getLastBody());
        return $response;
    }

    public function combineData($response, $data)
    {
        $valBefore = null;
        $tempPos = null;
        $out = [];
        $i = 0;
        $j = 2;
        $out[$i] = [
            "id"        => $data->original->id, 
            "text"      => $data->original->user->name, 
            "title"     => "Tweet At <br>".date("d F Y", strtotime($data->original->created_at)), 
            "width"     => "350", 
            "height"    => "100", 
            "dir"       => "horizontal", 
            "img"       => $data->original->user->profile_image_url,
            "posisi"    => 1, 
            "parent"    => 0, 
        ];
        $i++;
        foreach($response as $k => $subarray) {
            foreach($subarray as $kk => $vv) {
                if ($kk == 'created_at') {
                    $vv = date('d F Y', strtotime($vv));
                    if ($valBefore == $vv) {
                        $out[$i] = [
                            "id"        => $subarray->id, 
                            "text"      => $subarray->user->name, 
                            "title"     => date("d F Y", strtotime($subarray->created_at)), 
                            "width"     => "350", 
                            "height"    => "100", 
                            "dir"       => "vertical", 
                            "img"       => $subarray->user->profile_image_url,
                            "posisi"    => $subarray->id, 
                            "parent"    => $tempPos
                        ];
                    }else {
                        $out[$i] = [
                            "id"        => $subarray->id, 
                            "text"      => $vv, 
                            "title"     => date("d F Y", strtotime($subarray->created_at)),  
                            "dir"       => "vertical", 
                            "posisi"    => $j++, 
                            "parent"    => 1
                        ];
                        $i++;

                        $out[$i] = [
                            "id"        => $subarray->id, 
                            "text"      => $subarray->user->name, 
                            "title"     => date("d F Y", strtotime($subarray->created_at)), 
                            "width"     => "350", 
                            "height"    => "100", 
                            "dir"       => "vertical", 
                            "img"       => $subarray->user->profile_image_url,
                            "posisi"    => $subarray->id, 
                            "parent"    => $out[$i-1]['posisi']
                        ];
                        $tempPos = $out[$i-1]['posisi'];
                    }
                    $valBefore = $vv;
                }
            }
            $i++;
        }

        // dd($out);
        return $out;
    }

    public function retweets_download($id)
    {
        $data = self::show_tweet($id); 

        $twitter = SocialAuth::query()->first();
        $push = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $twitter->twitter_oauth_token, $twitter->twitter_oauth_token_secrete);
        $push->setTimeouts(10, 15);
        $push->ssl_verifypeer = true;
        $push->get('statuses/retweets', [
            'id' => $data->original->retweeted_status->id_str,
            "count" => "100"
        ]);  

        $response = response()->json($push->getLastBody());
        $response = $response->original;

        usort($response, function($a, $b) {
            return strtotime($a->retweeted_status->created_at) - strtotime($b->retweeted_status->created_at);
        });

        $data = self::show_tweet($data->original->retweeted_status->id_str);
        $response = self::combineData($response, $data);
        
        // dd($response);
        return view('twitter.download', ["response" => $response]);
        
        // $pdf = PDF::loadview('twitter.download', ["response" => $response])
        //         ->setPaper('a4', 'potrait');
    	// return $pdf->stream();
    }


    public function user_tweet()
    {
        $notif = 1;
        $user = User::where('id', \Auth::user()->id)->first();
        $socialAuth = SocialAuth::where('user_id', $user->id)->first();
        if ($socialAuth == null) {
            $notif = 0;
        }

        return view('twitter.userTweet.index', ['notif' => $notif]);
    }

    public function userTweet(Request $request)
    {
        $string = $request->input('nickName') ?? "devproject22";


        $twitter = SocialAuth::query()->first();
        $push = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $twitter->twitter_oauth_token, $twitter->twitter_oauth_token_secrete);
        $push->setTimeouts(10, 15);
        $push->ssl_verifypeer = true;
        $push->get("statuses/user_timeline", [
            "screen_name" => "$string", 
            "tweet_mode" => "extended",
            "count" => "100"
        ]);

        $response = response()->json($push->getLastBody());

        return $response;
    }
}
