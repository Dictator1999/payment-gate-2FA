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
                                            <td><a id="user" href="{{ route('live.chat',['id'=>$user->id,'name'=>$user->name]) }}" class="text-right">{{ $user->name }}</a></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                        <div class="w-50 pb-0 chat-card float-left">
                            <div class="h-10 w-100 reciaver-name text-center">
                                <h4 class="pb-0 pt-2">{{ $reciaver->name }}</h4>
                                <small class="p-0 ">{{ $reciaver->email }}</small>
                            </div>
                            <div class="chat-box px-2" id="chatBox">

                            </div>
                            <form id="inboxForm" name="inboxForm">
                                <input type="hidden" name="reciverId" value="{{ $reciaver->id }}">
                            {{ csrf_field() }}
                            <div class="input-group mr-sm-2 px-2">
                                <input id="msg" name="msg" type="text" class="form-control" placeholder="Enter Message">
                                <div class="input-group-prepend">
                                    <div id="msgSubmit" class="input-group-text" style="background: #fff; cursor: pointer;"><i class="text-primary fa fa-telegram-plane"></i></div>
                                </div>
                            </div>
                            </form>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $("#msgSubmit").click(function(){
                                    $.ajax({
                                        url:"{{ route('live.chat.send') }}",
                                        method:'POST',
                                        data:$("#inboxForm").serialize(),
                                        success:function(data){
                                            console.log(data);
                                            $("#msg").val("");
                                        }
                                    });
                                });

                                setInterval(function(){
                                     $("#chatBox").load("{{ route('live.chat.data',['id'=>$reciaver->id]) }}").fadeIn('slow');
                                },2000);
                            });
                        </script>
                        <div class="w-25 h-100 float-left user-info">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
