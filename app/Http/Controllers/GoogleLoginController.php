<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    protected  $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        $social_user = Socialite::driver('google')->user();
        if($social_user->email == null){
            return Socialite::driver('google')->with(['auth_type' => 'rerequest'])->redirect();
        }

        $user_data = $this->user->findBy('social_account_id', $social_user->id);
        if(!$user_data){
            $existing_user = $this->user->findBy('email', $social_user->email);

            if($existing_user) {
                $login_user = $existing_user;
            }else{
                $new_user_data['account_type'] = 'S';
                $new_user_data['social_account_type'] = 'G';
                $new_user_data['social_account_id'] = $social_user->id;
                $new_user_data['user_type_id'] = '2';
                $new_user_data['name'] = $social_user->name;
                $new_user_data['email'] = $social_user->email;
                $new_user_data['username'] = $social_user->email;
                $new_user_data['password'] = \Hash::make('amit');
                $new_user_data['image'] = $social_user->avatar_original;
                $new_user_data['is_active'] = 1;
                $login_user = $this->user->create($new_user_data);
            }
        }else{
            $login_user = $user_data;
        }

        Auth::loginUsingId($login_user->id);
        return redirect('dashboard');

        // $user->token;
    }
}
