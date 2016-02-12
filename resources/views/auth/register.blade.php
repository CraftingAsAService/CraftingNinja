@extends('app')

@section('banner')
    <h1>Register</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-10 col-md-offset-1">
            <div class="card">
                <div class="card-block">
                    {!! Form::open(['url' => '/register', 'class' => 'form-horizontal']) !!}

                        <div class="row form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="col-md-4 form-control-label">Username</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 form-control-label">Email Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 form-control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 form-control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> Register
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>

                <hr class='m-t-0 m-b-0'>

                <div class="card-block">

                    <div class='row'>
                        <div class='col-md-6 col-md-offset-4 card-text'>
                            We respect your <a href='/privacy'>privacy</a>!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
