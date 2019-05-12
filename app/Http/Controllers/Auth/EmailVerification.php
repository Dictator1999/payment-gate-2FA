<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\userverification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EmailVerification extends Controller
{
    use RegistersUsers;

    public function emailVerify($token){
        $userVelidate = userverification::where('emailVerificationTool',$token)->first();
        if($userVelidate){
            $this->foundLink($userVelidate);
            return view('auth.otpsend')->with([
                'sucMsg'=>'<span class="alert alert-success d-block">Congrage. You have successfully verify your account.</span>',
            ]);
        }else{
            return view('auth.otpsend')->with([
                'dangerMsg'=>'<span class="alert alert-danger d-block">Your verification not valid.</span>'
            ]);
        }
    }

    protected function foundLink($userVelidate){
       if(Carbon::now()->subMinutes(30) < $userVelidate->emailVerificationToolSendAt){
           $this->mainJob($userVelidate);
       }else{
           return view('auth.otpsend')->with([
               'dangerMsg'=>'<span class="alert alert-danger d-block">Your verification link has been expired.</span>'
           ]);
       }
    }

    protected function mainJob($userVelidate){
        $userInfo = User::find($userVelidate->userId);
        if(
            $userInfo->isEmailVerified === 0
            AND $userVelidate->emailVerificationTool != ''
            AND $userVelidate->email_verified_at === NULL
          ){
             $this->allOk($userInfo,$userVelidate);
             $this->afterVerificationEmail($userInfo);
        }else{
            return view('auth.otpsend')->with([
                'dangerMsg'=>'<span class="alert alert-danger d-block">Your request is not valid.</span>'
            ]);
        }
    }

    protected function allOk($userInfo,$userVelidate){
        $userInfo->isEmailVerified = true;
        $userVelidate->email_verified_at = Carbon::now();
        $userVelidate->emailVerificationTool = '';
        $userVelidate->save();
        $userInfo->save();
    }
}
