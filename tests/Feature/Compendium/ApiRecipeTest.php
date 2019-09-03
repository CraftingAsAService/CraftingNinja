<?php

namespace Feature\Compendium;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Recipe;
use Tests\GameTestCase;

class ApiRecipeTest extends GameTestCase
{

	/** @test */
	public function user_can_make_ajax_call_for_recipes()
	{
		factory(Recipe::class)->create([
			'item_id' => factory(Item::class)->create([
				'name' => 'Beta Item',
			])->id,
		]);

		$this->withoutExceptionHandling();
		$response = $this->call('POST', '/api/recipe');

		$response->assertStatus(200);
		$response->assertSee('Beta Item');
	}

}
