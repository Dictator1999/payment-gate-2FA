<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    ///E-mail OTP verification start
    public function emailCodeWithToken(Request $request){
       $this->email2faValidator($request);
       $user = User::where('id',$request->userId)
                    ->where('email',$request->email)
                    ->where('emailTwoFaToken',$request->token)
                    ->where('emailTwoFaCode',$request->code)
                    ->first();
       if($user){
           $this->emailOtpOk($user);
           return redirect('/home');
       }else{
           return view('auth.otpsend')->with([
               'dangerMsg'=>'<span class="alert alert-danger d-block">Your verification not valid.</span>'
           ]);
       }
    }

    protected function email2faValidator($request){
        $request->validate([
            'userId' => 'required',
            'email' => 'required|email',
            'token' => 'required|string',
            'code' => 'required|string',
        ]);
    }

    protected function emailOtpOk($user){
        $user->emailTwoFaCode = '';
        $user->emailTwoFaToken = '';
        $user->emailCodeSendAt = null;
        $user->save();
        $this->email2faDataUpdate($user);
    }

    protected function email2faDataUpdate($user){
        $this->guard()->login($user);
    }

    ///E-mail OTP verification end


    ///Phone OTP verification start
    public function phoneCodeWithToken(Request $request){
        $this->phone2faValidator($request);
        $user = User::where('id',$request->userId)
            ->where('email',$request->email)
            ->where('phoneTwoFaToken',$request->token)
            ->where('phoneTwoFaCode',$request->code)
            ->first();
        if($user){
            $this->phoneOtpOk($user);
            return redirect('/home');
        }else{
            return view('auth.otpsend')->with([
                'dangerMsg'=>'<span class="alert alert-danger d-block">Your verification not valid.</span>'
            ]);
        }
    }

    protected function phoneOtpOk($user){
        $user->phoneTwoFaCode = '';
        $user->phoneTwoFaToken = '';
        $user->phoneCodeSendAt = null;
        $user->save();
        $this->phone2faDataUpdate($user);
    }

    protected function phone2faDataUpdate($user){
        $this->guard()->login($user);
    }

    protected function phone2faValidator($request){
        $request->validate([
            'userId' => 'required',
            'email' => 'required|email',
            'token' => 'required|string',
            'code' => 'required|string',
        ]);
    }

    ///Phone OTP verification end
}
