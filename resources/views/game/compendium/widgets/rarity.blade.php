<aside class='widget card widget--sidebar widget_filter-rarity' data-type='multiple' hidden>
	<form action='#' class='filter-rarity-form'>
		<div class='widget__title card__header card__header--has-btn'>
			<h4>
				<i class='fas fa-registered mr-1'></i>
				Rarity
			</h4>
			<button class='btn btn-primary btn-xs card-header__button'><i class='fas fa-check'></i></button>
			<button class='btn btn-link btn-xs card-header__button mr-2'><i class='fas fa-trash'></i></button>
		</div>
		<div class='widget__content card__content'>
			<div class='row'>
				@foreach (config('game.rarity') as $rarityKey => $rarity)
					<div class='col-md-6'>
						<div class='form-group form-group--xs'>
							<label class='checkbox checkbox-inline'>
								<input type='checkbox' name='rarity[]' id='rarity-{{ $rarityKey }}' value='{{ $rarityKey }}' checked> {{ $rarity }}
								<span class='checkbox-indicator'></span>
							</label>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</form>
</aside>
