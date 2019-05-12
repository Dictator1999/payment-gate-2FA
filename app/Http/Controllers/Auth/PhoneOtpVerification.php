<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;

class PhoneOtpVerification extends Controller
{
    public function index(){

        Nexmo::message()->send([
            'to'   => '+8801819075764',
            'from' => '+8801754780545',
            'text' => 'Hi sakib, Your phone recently hacked by dictator team'
        ]);
    }
}
