<?php
namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\userverification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class settingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $user = User::find(Auth::user()->id);
        return view('admin.setting')->with([
            'user'=>$user,
        ]);
    }

   public function saveTwoFaEmail(){
        $user = User::find(Auth::user()->id);
       $user->phoneTwoFaStatus = '0';
        if($user->emailTwoFaStatus === '1'){
            $user->emailTwoFaStatus = '0';
            $user->save();
            return "<p class='alert alert-danger'>E-mail Two Factor authintication disabled.</p>";
        }else{
            $user->emailTwoFaStatus = '1';
            $user->save();
            return "<p class='alert alert-success'>E-mail Two Factor authintication enabled.</p>";
        }
   }
   public function saveTwoFaPhone(){
        $user = User::find(Auth::user()->id);
        $user->emailTwoFaStatus = '0';
        if($user->phoneTwoFaStatus === '1'){
            $user->phoneTwoFaStatus = '0';
            $user->save();
            return "<p class='alert alert-danger'>Phone Two Factor authintication disabled.</p>";
        }else{
            $user->phoneTwoFaStatus = '1';
            $user->save();
            return "<p class='alert alert-success'>Phone Two Factor authintication enabled.</p>";
        }
   }

}
