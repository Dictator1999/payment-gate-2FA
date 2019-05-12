@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Message</div>
                    <div class="card-body">
                        @if(isset($dangerMsg))
                        {!! $dangerMsg !!}
                        @endif
                        @if(isset($sucMsg))
                        {!! $sucMsg !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
