@extends('app')

@section('banner')
    <h1>Admin Panel</h1>
@endsection

@section('content')

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="card">
            <h3 class='card-header'>
            	Admin Panel
            </h3>
            <div class="card-block">

            	<a href='/admin/users' class='btn btn-primary'>User Control</a>

            </div>
        </div>
    </div>
</div>

@endsection
