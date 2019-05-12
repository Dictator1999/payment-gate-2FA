@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Live Chat</div>
                    <div class="card-body chat-card p-0">
                        <div class="user-list w-25 float-left">
                            <table class="table mb-0">
                                @foreach($users as $user)
                                    @if($user->name != Auth::user()->name)
                                        <tr>
                                            <td><a href="{{ route('live.chat',['id'=>$user->id,'name'=>$user->name]) }}" class="text-right">{{ $user->name }}</a></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                        <div class="w-75 chat-box float-left">
                            <div class="m-auto w-50 h-100 mt-5 pt-5">
                                <h1 class="pt-5">No user seleted.</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
