@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="social-auth-links text-center mb-3">
                            <p>- OR -</p>
                            <a href="{{ route('media.twitter') }}" class="btn btn-block btn-info mb-1" style="color: #FFF;">
                                <i class="fab fa-twitter mr-2"></i> Sign in using Twitter
                            </a>

                            <a href="{{ route('instagram.login') }}" class="btn btn-block mb-1" style="color: #FFF; background-color: #bc2a8d;">
                                <i class="fab fa-instagram mr-2"></i> Sign in using Instagram
                            </a>
                            <div id="fb-root"></div>
                            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v12.0&appId=632917004494593&autoLogAppEvents=1" nonce="h4v2tDsS"></script>

                            <div class="fb-login-button" data-width="" data-size="large" data-button-type="continue_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false"></div>


                            <fb:login-button 
                              scope="public_profile,email"
                              onlogin="checkLoginState();">
                            </fb:login-button>
                            <script>
                              window.fbAsyncInit = function() {
                                FB.init({
                                  appId      : '632917004494593',
                                  cookie     : true,
                                  xfbml      : true,
                                  version    : 'v12.0'
                                });
                                  
                                FB.AppEvents.logPageView();   
                                  
                              };

                              (function(d, s, id){
                                 var js, fjs = d.getElementsByTagName(s)[0];
                                 if (d.getElementById(id)) {return;}
                                 js = d.createElement(s); js.id = id;
                                 js.src = "https://connect.facebook.net/en_US/sdk.js";
                                 fjs.parentNode.insertBefore(js, fjs);
                               }(document, 'script', 'facebook-jssdk'));

                              FB.getLoginStatus(function(response) {
                                    statusChangeCallback(response);
                                });

                              function checkLoginState() {
                                  FB.getLoginStatus(function(response) {
                                    statusChangeCallback(response);
                                  });
                                }
                            </script>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
