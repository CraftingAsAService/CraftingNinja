@extends('app')

@section('banner')
    <h1>Login</h1>
@stop

@section('content')


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="card">
                <div class="card-block">
                    {!! Form::open(['url' => '/login', 'class' => 'form-horizontal']) !!}

                        <fieldset class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 form-control-label">Email Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </fieldset>

                        <fieldset class="row form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 form-control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </fieldset>

                        <fieldset class="row form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" checked="checked"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="row form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                </button>
                                <div class='m-t-1'>
                                    <small><a href="{{ url('/password/reset') }}">Forgot Your Password?</a></small>
                                </div>
                            </div>
                        </fieldset>
                    {!! Form::close() !!}
                </div>

                <hr class='m-t-0 m-b-0'>

                <div class="card-block">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-4">
                            <a class="btn btn-secondary" href="{{ url('/register') }}">Create an Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
