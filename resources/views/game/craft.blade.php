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
					<h4><i class='fa fa-map -desize mr-2'></i>Unknown Locations</h4>
				</div>
				<div class='card__content'>
					...
				</div>
				<div class='card__header'>
					<h4><i class='fas fa-map-marked -desize mr-2'></i>Central Shroud - Bentbranch</h4>
				</div>
				<div class='card__content'>
					<div class='row'>
						<div class='col'>
							<div class='item'>
								<div class='info'>
									<div class='form-group tally float-right'>
										<i class='fas fa-cogs ml-2 mr-2'></i>
										<input type='number' placeholder='0' min='0' max='7'><big><code> / </code>7</big>
										{{-- <big>
											<small class='text-muted'>x</small>7
										</big> --}}
										{{-- <div class='input-group' style='width: 120px;'>
											<input type='number' class='form-control' placeholder='0'>
											<div class='input-group-append'>
												<span class='input-group-text' id='basic-addon2'></span>
											</div>
										</div> --}}
										<label class='checkbox ml-2' style='width: 24px;'>
											<input type='checkbox' value='option1' checked='checked'>
											<span class='checkbox-indicator' style='width: 24px; height: 24px; top: -10px;'></span>
										</label>
									</div>
									<div class='title'>
										<img src='/assets/{{ config('game.slug') }}/item/1234.png' alt='' width='32' height='32' class='mr-1'>
										<big class='mr-2'>Elm Log</big>
									</div>
								</div>
								<div class='sources'>
									<div class=''>
										<i class='fas fa-tree'></i> <code>20,20</code> Mature Tree - <code>55%</code>
									</div>
									<div class=''>
										<i class='fas fa-skull-crossbones'></i> <code>8,13</code> Dragon - <code>60%</code>
									</div>
									<div class='text-muted small'>
										<i class='fas fa-mountain'></i>
										<i class='fas fa-piggy-bank mr-1'></i>
										available elsewhere
									</div>
								</div>
							</div>
							<div class='item'>
								<div class='info'>
									<div class='form-group tally float-right'>
										<i class='far fa-dot-circle text-muted' data-toggle='tooltip' title='Location Options Limited'></i>
										<i class='fas fa-cogs ml-2 mr-2'></i>
										<big>
											<small class='text-muted'>x</small>22
										</big>
										<label class='checkbox ml-2' style='width: 24px;'>
											<input type='checkbox' value='option1' checked='checked'>
											<span class='checkbox-indicator' style='width: 24px; height: 24px; top: -10px;'></span>
										</label>
									</div>
									<div class='title'>
										<img src='/assets/{{ config('game.slug') }}/item/2211.png' alt='' width='32' height='32' class='mr-1'>
										<big class='mr-2'>Feather</big>
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
