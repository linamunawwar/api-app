<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\SocialAuth;

class TwitterController extends Controller
{
    private $consumerKey = "xCtAHgU6RwikqPdqmotJRdacT";
    private $consumerSecret = "Yh25mmLfi8u8B9dTXS6IJOtWiXMb8mchnGoYuAbrCO4jHxiMeL";

    // Authorization Twitter
    public function twitter_connect(Request $request)
    {
        $callback = route('media.callback');

        $_twitter_connect = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
        $_access_token = $_twitter_connect->oauth('oauth/request_token', ['oauth_callback' => $callback]);
        $_route = $_twitter_connect->url('oauth/authorize', ['oauth_token' => $_access_token['oauth_token']]);

        return redirect($_route);
    }

    public function twitter_callback(Request $request)
    {
        $response = $request->all();

        $oauth_token = $response['oauth_token'];
        $oauth_verifier = $response['oauth_verifier'];

        $_twitter_connect = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $oauth_token, $oauth_verifier);
        $token = $_twitter_connect->oauth('oauth/access_token', ['oauth_verifier' => $oauth_verifier]);
        
        $oauth_token = $token['oauth_token'];
        $screen_name = $token['screen_name'];
        $oauth_token_secrete = $token['oauth_token_secret'];


        $save = SocialAuth::query()->updateOrCreate(
            ['twitter_screen_name' => $screen_name],
            [
                'twitter_oauth_token' => $oauth_token,
                'twitter_oauth_token_secrete' => $oauth_token_secrete,
            ]
        );

        return redirect()->route('user.index');

        // $this->MessageToTwitter($oauth_token, $oauth_token_secrete);
    }

    

    public function index()
    {
        return view('twitter.index');
    }

    public function fetch_twitter(Request $request)
    {
        $string = $request->input('tweetSearch') ?? "Dev Api";

        $twitter = SocialAuth::query()->first();
        $push = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $twitter->twitter_oauth_token, $twitter->twitter_oauth_token_secrete);
        $push->setTimeouts(10, 15);
        $push->ssl_verifypeer = true;
        $push->get("search/tweets", [
            "q" => "$string", 
            "count" => "6"
        ]);

        $response = response()->json($push->getLastBody());

        // foreach ($response as $key => $value) {
        //     dd($value);
        // }

        return $response;
    }
}
