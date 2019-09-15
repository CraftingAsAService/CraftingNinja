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

			<div class='card'>
				<div class='card__header'>
					Central Shroud - Bentbranch
				</div>
				<div class='card__content'>
					<div class='row'>
						<div class='col'>
							<div class='item'>
								<div class='info'>
									<div class='tally float-right'>
										7 [x]
									</div>
									<div class='title'>
										[Image]
										<span>Elm Log</span>
									</div>
								</div>
								<div class='sources'>
									<div class=''>
										[i] <code>20,20</code> Mature Tree, 60%
									</div>
									<div class=''>
										[i] <code>8,13</code> Animal Skin Shop, 4 gil
									</div>
								</div>
							</div>
							<div class='item'>
								<div class='info'>
									<div class='tally float-right'>
										7 [x]
									</div>
									<div class='title'>[Image] Feather [Only Here]</div>
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
						</div>
						<div class='col'>
							Map!
						</div>
					</div>
				</div>
			</div>










		</div>

@endsection
