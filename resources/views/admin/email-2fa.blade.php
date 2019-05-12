@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Setting</div>
                    <div class="card-body">
                        <form action="{{ route('twoFAverificationwithtoken') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="email" class="col-md-8 col-form-label">Please enter the code which sent to your email.</label>
                            <div class="col-md-4 text-right">
                                <input type="text" name="code" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="userId" value="{{ $userId }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">
                            <label for="email" class="col-md-8 col-form-label"></label>
                            <div class="col-md-4 text-right">
                                <input type="submit" value="Send" class="btn btn-primary">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

