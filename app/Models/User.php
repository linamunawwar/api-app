<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\SocialAuth;
use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this
            ->belongsToMany('App\Models\Role')
            ->withTimestamps();
    }

    public function users()
    {
        return $this
            ->belongsToMany('App\Models\User')
            ->withTimestamps();
    }

    public function socialAccounts()
    {
      return $this->hasMany(SocialAccount::class);
    }

    public function authorizeRoles($roles)
    {
      if ($this->hasAnyRole($roles)) {
        return true;
      }
      abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles)
    {
      if (is_array($roles)) {
        foreach ($roles as $role) {
          if ($this->hasRole($role)) {
            return true;
          }
        }
      } else {
        if ($this->hasRole($roles)) {
          return true;
        }
      }
      return false;
    }

    public function hasRole($role)
    {
      if ($this->roles()->where('name', $role)->first()) {
        return true;
      }
      return false;
    }

    public static function createOrFind($request)
    {
      // dd($request->password);
      $findUser = SocialAuth::where('provider', $request->media)->where('provider_id', $request->id)->first();
      if ($findUser == null) {
          $user = new User;

          $user->name              = $request->nickname;
          $user->email             = $request->email==null?$request->nickname:$request->email;
          $user->email_verified_at = null;
          $user->password          = bcrypt('12345678');
          $user->role_id           = 2;
          $user->remember_token    = null;
          $user->created_at        = date('Y-m-d H:i:s');
          
          if ($user->save()) {
            $userSocial = new SocialAuth;

            $userSocial->user_id                      = $user->id;
            $userSocial->twitter_screen_name          = $request->name;
            $userSocial->twitter_oauth_token          = $request->token;
            $userSocial->twitter_oauth_token_secrete  = $request->tokenSecret;
            $userSocial->provider                     = 'Twitter';
            $userSocial->provider_id                  = $request->id;

            if ($userSocial->save()) {
              $user = User::where('id', $user->id)->first();
              return $user;
            }
          }
      } else {
        $user = User::where('id', $findUser->user_id)->first();
        return $user;
      }
      

      if ($user) {
        return $user->id;
      }
    }
}
