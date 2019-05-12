<?php

namespace App\Http\Controllers;

use App\Inbox;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

session_start();

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        return view("chat.index")->with([
            'users'=>$users,
        ]);
    }

    public function userInbox($id,$name)
    {
        //Session::put(Auth::user()->id,$id);
        $users = User::all();
        $reciaver = User::find($id);
        return view("chat.inbox")->with([
            'users'=>$users,
            'reciaver'=>$reciaver,
        ]);
    }

    public function saveMsg(Request $request)
    {
        //return Session::get(Auth::user()->id);
        $this->validator($request->all())->validate();
        $this->saveData($request);
    }

    protected function saveData($request)
    {
        $inbox = new Inbox();
        $inbox->senderId = Auth::user()->id;
        $inbox->recieverId = $request['reciverId'];
        $inbox->msg = $request->msg;
        $inbox->save();
        return "Data Save";
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'msg' => ['required', 'string']
        ]);
    }

    public function showMsg($id){
        $msg = Inbox::where("recieverId",$id)
                     //->where("senderId",Auth::user()->id)
                     ->orderBy('id','desc')
                     ->get();
        if($msg){
            $data="";
            $data .="<table>";
            foreach($msg as $result){
                $data .="<tr><td>".$result->msg."</td></tr>";
            }
            $data .="</table>";
            echo $data;
        }else{
            echo "No data found.";
        }
    }
}
