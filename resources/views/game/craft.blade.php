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

			<h3>
				Collect
			</h3>

			<div class='row'>
				<div class='col'>
					<div class='card'>
						<div class='card__content'>
							<div>
								<h5>
									<i class='far fa-map -desize mr-2'></i>Unknown Locations
								</h5>
							</div>
							&hellip;
							<div>
								<h5 class='mt-3'>
								<i class='fas fa-map-pin -desize float-right'></i>
									<i class='fas fa-map-marked -desize mr-2'></i>Central Shroud - Bentbranch
								</h5>
							</div>
							<div class='row item'>
								<div class='col-auto'>
									<img src='/assets/{{ config('game.slug') }}/item/999.png' alt='' width='48' height='48'>
								</div>
								<div class='col info'>
									<big class='rarity-2'>Elm Log</big>
									<div class='sources'>
										<i class='fas fa-caret-square-down text-primary'></i>
										{{-- <i class='fas fa-expand-arrows-alt text-primary'></i> --}}
										{{-- <i class='fas fa-expand text-primary'></i> --}}
										<span class=''>
											<i class='fas fa-tree'></i>
											<i class='fas fa-skull-crossbones'></i>
										</span>
										<span class='text-muted small'>
											<i class='fas fa-mountain -desize'></i>
											<i class='fas fa-piggy-bank -desize mr-1'></i>
										</span>
									</div>
								</div>
								<div class='col-auto'>
									<div class='form-group tally'>
										<big><small class='text-muted'>x</small>7</big>
										<label class='checkbox ml-2' style='width: 24px;'>
											<input type='checkbox' value='option1' checked='checked'>
											<span class='checkbox-indicator' style='width: 24px; height: 24px; top: -10px;'></span>
										</label>
									</div>
								</div>
							</div>
							<div class='row item'>
								<div class='col-auto'>
									<img src='/assets/{{ config('game.slug') }}/item/999.png' alt='' width='48' height='48'>
								</div>
								<div class='col info'>
									<big class='rarity-2'>Elm Log</big>
									<div class='sources card p-2 mt-2'>
										<i class='fas fa-caret-square-up text-primary'></i>
										{{-- <i class='fas fa-compress-arrows-alt text-primary'></i> --}}
										{{-- <i class='fas fa-compress text-primary'></i> --}}
										<div>
											<i class='fas fa-tree'></i> <code>20,20</code> Mature Tree - <code>55%</code>
										</div>
										<div>
											<i class='fas fa-skull-crossbones'></i> <code>8,13</code> Dragon - <code>60%</code>
										</div>
										<div class='text-muted small'>
											<i class='fas fa-mountain'></i>
											<i class='fas fa-piggy-bank mr-1'></i>
											available elsewhere
										</div>
									</div>
								</div>
								<div class='col-auto'>
									<div class='form-group tally'>
										<big><small class='text-muted'>x</small>7</big>
										<label class='checkbox ml-2' style='width: 24px;'>
											<input type='checkbox' value='option1' checked='checked'>
											<span class='checkbox-indicator' style='width: 24px; height: 24px; top: -10px;'></span>
										</label>
									</div>
								</div>
							</div>
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
							&hellip;
							<div>
								<h5 class='mt-3'>
									<i class='fas fa-map-marked -desize mr-2'></i>Area B - Region Y
								</h5>
							</div>
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
							<div>
								<h5 class='mt-3'>
									<i class='fas fa-ban -desize mr-2'></i>Ignored Items
								</h5>
							</div>
							[Image] [Image]
						</div>
					</div>
				</div>
				<div class='col'>
					<div class='map-that-scrolls-with-you'>
						{{-- ?? https://travishorn.com/interactive-maps-with-vue-leaflet-5430527353c8 --}}
						<style>
							.enemy-node {
								position: absolute;
								top: 10%;
								left: 20%;
							}
							.npc-node {
								position: absolute;
								top: 20%;
								left: 50%;
							}
							.object-node {
								position: absolute;
								top: 40%;
								left: 30%;
							}
							.gathering-node {
								position: absolute;
								top: 40%;
								left: 40%;
							}
							.treasure-node {
								position: absolute;
								top: 50%;
								left: 50%;
							}
						</style>
						<img src='/assets/{{ config('game.slug') }}/mappy/enemy_2x.png' width='10' height='10' class='img-responsive enemy-node' alt=''>
						<img src='/assets/{{ config('game.slug') }}/mappy/npc_2x.png' width='10' height='10' class='img-responsive npc-node' alt=''>
						<img src='/assets/{{ config('game.slug') }}/mappy/object_2x.png' width='10' height='10' class='img-responsive object-node' alt=''>
						<img src='/assets/{{ config('game.slug') }}/mappy/gathering_2x.png' width='20' height='20' class='img-responsive gathering-node' alt=''>
						<img src='/assets/{{ config('game.slug') }}/mappy/chest1.png' width='32' height='32' class='img-responsive treasure-node' alt=''>
						<img src='/assets/{{ config('game.slug') }}/maps/g3f2.00.jpg' class='img-responsive' alt=''>
					</div>
				</div>
			</div>

			<h3>
				Craft
			</h3>

			<div class='card'>
				<div class='card__content'>
					&hellip;
				</div>
			</div>
		</div>

@endsection
