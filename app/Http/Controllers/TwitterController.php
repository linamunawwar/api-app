<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\SocialAuth;
use App\Models\User;
use Socialite;
use Session;

class TwitterController extends Controller
{

    public function __construct()
    {
    }

    private $consumerKey = "xCtAHgU6RwikqPdqmotJRdacT";
    private $consumerSecret = "Yh25mmLfi8u8B9dTXS6IJOtWiXMb8mchnGoYuAbrCO4jHxiMeL";

    // Authorization Twitter
    public function twitter_connect(){
        $provider = 'twitter';
        return Socialite::driver($provider)->redirect();
    }

    public function twitter_callback(){
        $twitterSocial =   Socialite::driver('twitter')->user();
        $twitterSocial->media = "twitter";

        $findUser = User::createOrFind($twitterSocial);
        
        if ($findUser == 1) {
            return redirect()->route('twitter');
        }
    }

       

    public function index()
    {
        return view('twitter.index');
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


    public function user_tweet()
    {
        return view('twitter.userTweet.index');
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
