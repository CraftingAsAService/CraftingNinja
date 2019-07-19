@extends('app')

@section('content')
<div class='container'>
	<div class='row justify-content-center'>
		<div class='col-md-8'>
			<div class='card'>
				<div class='card__header'>
					<h4>
						{{ __('LOGIN TO YOUR ACCOUNT') }}
					</h4>
				</div>
				<div class='card__content'>
					<div class='row'>
						<div class='col-6 offset-3'>
							<a href='/login/google' class='btn btn-gplus btn-icon btn-block'><i class='fab fa-google'></i> Sign In via Google</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
