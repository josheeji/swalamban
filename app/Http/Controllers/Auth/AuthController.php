<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/19/18
 * Time: 4:41 PM
 */

namespace App\Http\Controllers\Auth;

use App\Helper\Helper;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\Repositories\AdminRepository;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use RedirectsUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';


    /**
     * The Admin repository implementation.
     *
     * @var AdminRepository
     */
    protected $admin;

    /**
     * Create a new authentication controller instance.
     *
     * @param  AdminRepository $admin
     */
    public function __construct(AdminRepository $admin)
    {
        $this->admin = $admin;
        $this->middleware('guest:admin', ['except' => 'getLogout']);
    }

    public function redirectLogin()
    {
        return redirect()->route('admin.login');
    }

    public function getLogin()
    {
        return view('auth.admin.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => 1
        ], $request->remember)) {

//            if(Auth::user()->userType && Auth::user()->userType->access) {
//                $access = Helper::resetCollectionFirstArrayInstanceGetRole((Auth::user()->userType->access)->groupBy('module_id'));
//                session()->put('access', $access);
//            }
            return redirect()->intended('admin/dashboard')
                ->with('flash_notice', 'You have successfully logged in.');
        }
        return redirect()->back()
            ->withInput($request->only('email_address', 'remember'))
            ->with('flash_error', 'These credentials do not match our records.');

    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('admin.login')
            ->with('flash_notice', 'You are successfully logged out.');
    }
    public function resetPassword(Request $request) {
        dd();
    }

}