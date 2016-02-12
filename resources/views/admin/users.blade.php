@extends('app')

@section('banner')
    <h1>Admin Panel | Users</h1>
@endsection

@section('content')

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="card">
            <h3 class='card-header m-b-0'>
            	User List
            </h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{!! $user->valid_advanced_crafter_entries->count() ? '<span class="text-success">Advanced!</span>' : '' !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class='card-block'>
                <nav>
                    {!! $users->links() !!}
                </nav>
            </div>
        </div>
    </div>
</div>

@endsection
