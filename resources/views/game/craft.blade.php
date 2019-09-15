@extends('app', [
	'active' => '/craft',
	'css' => [
		// 'pages/sling',
	],
	'js' => [
		// 'pages/sling',
	]
])

@section('topContent')
	<div class='minor-media mb-3' hidden>
		<img src='/assets/{{ config('game.slug') }}/cover.jpg' alt='{{ config('game.data.name') }}'>
		<div class='text'>
			<h1>Crafting</h1>
		</div>
	</div>
@endsection

@section('content')

		<div id='craft'>

			<h4>Collect</h4>

			<style>
				.sources {
					margin-left: 40px;
				}
			</style>

			<div class='card'>
				<div class='card__header'>
					Central Shroud - Bentbranch
				</div>
				<div class='card__content'>
					<div class='row'>
						<div class='col'>
							<div class='item'>
								<div class='info'>
									<div class='form-group tally float-right'>
										<big>
											<small class='text-muted'>x</small>7
										</big>
										<label class='checkbox' style='width: 24px;'>
											<input type='checkbox' value='option1' checked='checked'>
											<span class='checkbox-indicator' style='width: 24px; height: 24px; top: -10px;'></span>
										</label>
									</div>
									<div class='title'>
										[Image]
										<big class='mr-2'>Elm Log</big>
										<i class='fas fa-mountain text-muted' data-toggle='tooltip' title='Available Elsewhere'></i>
										<i class='fas fa-piggy-bank text-muted' data-toggle='tooltip' title='Available Elsewhere'></i>
										<i class='fas fa-ellipsis-v ml-2'></i>
									</div>
								</div>
								<div class='sources'>
									<div class=''>
										<i class='fas fa-tree'></i> <code>20,20</code> Mature Tree - <code>55%</code>
									</div>
									<div class=''>
										<i class='fas fa-skull-crossbones'></i> <code>8,13</code> Dragon - <code>60%</code>
									</div>
								</div>
							</div>
							<div class='item'>
								<div class='info'>
									<div class='tally float-right'>
										7 [x]
									</div>
									<div class='title'>
										[Image]
										<big class='mr-2'>Feather</big>
										<i class='far fa-dot-circle text-muted' data-toggle='tooltip' title='Location Options Limited'></i>
										<i class='fas fa-ellipsis-v ml-2'></i>
									</div>
								</div>
								<div class='sources'>
									<div class=''>
										<i class='fas fa-mountain'></i> <code>x,y</code> Mining Node
									</div>
									<div class=''>
										<i class='fas fa-piggy-bank'></i> <code>x,y</code> Animal Skin Shop - <code>4<i class='fas fa-coins ml-1'></i></code>
									</div>
								</div>
							</div>
						</div>
						<div class='col'>
							Map!
						</div>
					</div>
				</div>
				<div class='card__header'>
					Area B - Region Y
				</div>
				<div class='card__content'>
					<div class='row'>
						<div class='col'>
							<div class='item'>
								<div class='info'>
									<div class='tally float-right'>
										7 [x]
									</div>
									<div class='title'>[Image] Acorn</div>
								</div>
								<div class='sources'>
									<div class=''>
										[i] <code>x,y</code> Node Name
									</div>
									<div class=''>
										[i] <code>x,y</code> NPC/Shop Name
									</div>
								</div>
							</div>
							<div class='item'>
								& others hidden
							</div>
						</div>
						<div class='col'>
							Map!
						</div>
					</div>
				</div>
				<div class='card__header'>
					Ignored Items&hellip;
				</div>
				<div class='card__content'>
					[Image] [Image]
				</div>
			</div>










		</div>

@endsection
