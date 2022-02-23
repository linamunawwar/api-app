<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Socialite;
use Auth;

class TwitterAuthenticationController extends Controller
{
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
        
        if ($findUser) {
            Auth::login($findUser);

            return redirect()->route('twitter');
        }
    }
}
