@extends('app')

@section('banner')
    <h1>Register</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="card">
            {!! Form::model(Auth::user(), ['method' => 'PUT', 'url' => '/account/update']) !!}

                <div class='card-block'>
                    <div class='row form-group'>
                        {!! Form::label('current_password', 'Current Password', ['class' => 'col-md-4']) !!}
                        <div class="col-md-6">
                            {!! Form::password('current_password', ['required' => 'required', 'placeholder' => 'Required']) !!}
                        </div>
                    </div>
                </div>

                <hr class='m-t-0 m-b-0'>

                <div class="card-block">
                    <div class='row form-group'>
                        {!! Form::label('username', 'Username', ['class' => 'col-md-4']) !!}
                        <div class="col-md-6">
                            {!! Form::text('username', null, ['required' => 'required']) !!}
                        </div>
                    </div>
                    <div class='row form-group'>
                        {!! Form::label('email', 'Email Address', ['class' => 'col-md-4']) !!}
                        <div class="col-md-6">
                            {!! Form::text('email', null, ['required' => 'required']) !!}
                        </div>
                    </div>
                </div>

                <hr class='m-t-0 m-b-0'>

                <div class='card-block'>
                    <div class='row form-group'>
                        {!! Form::label('password', 'Change Password', ['class' => 'col-md-4']) !!}
                        <div class="col-md-6">
                            {!! Form::password('password', ['placeholder' => 'Optional']) !!}
                        </div>
                    </div>
                    <div class='row form-group'>
                        {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-md-4']) !!}
                        <div class="col-md-6">
                            {!! Form::password('password_confirmation', ['placeholder' => 'Optional']) !!}
                        </div>
                    </div>
                </div>

                <hr class='m-t-0 m-b-0'>

                <div class='card-block'>
                    <div class="row form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-floppy-o"></i> Update
                            </button>
                        </div>
                    </div>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
