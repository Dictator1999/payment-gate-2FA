@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Setting</div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="email" class="col-md-8 col-form-label">Email Two Factor Authintication</label>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="checkbox" id="emailId" name="emailTwoFA"
{{--                                     value="@if($user->emailTwoFaStatus === '1'){{ '0' }}@elseif($user->emailTwoFaStatus === '0'){{ '1' }}@endif"--}}
                                    @if($user->emailTwoFaStatus === '1')
                                        checked
                                    @endif
                                    onclick="emailTwoFa()"
                                    >
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-8 col-form-label">Phone Two Factor Authintication</label>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="checkbox" id="phoneId" name="phoneTwoFA"
{{--                                       value="@if($user->phoneTwoFaStatus === '1'){{ '0' }}@elseif($user->phoneTwoFaStatus === '0'){{ '1' }}@endif"--}}
                                       @if($user->phoneTwoFaStatus === '1')
                                       checked
                                        @endif
                                        onclick="phoneTwoFa()"
                                    >
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <span id="txtHint"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function emailTwoFa() {
            var xhttp = new XMLHttpRequest();
            var serverPage = "<?php echo route('saveTwoFaEmail');?>";
            xhttp.open("GET",serverPage);
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("txtHint").innerHTML = xhttp.responseText;
                    document.getElementById('phoneId').checked=false;
                }
            };
            xhttp.send();
        }
        function phoneTwoFa() {
            var xhttp = new XMLHttpRequest();
            var serverPage = "<?php echo route('saveTwoFaPhone');?>";
            xhttp.open("get",serverPage);
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("txtHint").innerHTML = xhttp.responseText;
                    document.getElementById('emailId').checked=false;
                }
            };
            xhttp.send();
        }
    </script>
@endsection

