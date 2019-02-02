<aside class='widget card widget--sidebar widget_filter-sublevel' data-type='multiple' hiddenx>
	<form action='#' class='filter-rarity-form'>
		<div class='widget__title card__header card__header--has-btn'>
			<h4>
				<i class='fas fa-star mr-1'></i>
				Recipe Difficulty
			</h4>
			<button class='btn btn-primary btn-xs card-header__button'><i class='fas fa-check'></i></button>
			<button class='btn btn-link btn-xs card-header__button mr-2'><i class='fas fa-trash'></i></button>
		</div>
		<div class='widget__content card__content'>
			<div class='row'>
				@foreach (range(0, config('game.maxDifficulty')) as $sublevel)
					<div class='col-md-6'>
						<div class='form-group form-group--xs'>
							<label class='checkbox checkbox-inline'>
								<input type='checkbox' name='sublevel[]' id='sublevel-{{ $sublevel }}' value='{{ $sublevel }}' checked>
								<span class='checkbox-indicator'></span>
								@if ($sublevel == 0)
									Default
								@else
									@foreach (range(1, $sublevel) as $star)
									<span class='sublevel-icon'></span>
									@endforeach
								@endif
							</label>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</form>
</aside>
