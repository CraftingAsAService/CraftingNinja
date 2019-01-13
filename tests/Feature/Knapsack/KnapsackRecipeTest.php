<?php

namespace Feature\Knapsack;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\GameTestCase;

class KnapsackRecipeTest extends GameTestCase
{

	/** @test */
	function users_can_see_recipes_in_their_knapsack()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$recipe = factory(Recipe::class)->create([
			'item_id' => $item->id,
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();
		$listing->recipes()->save($recipe, [ 'quantity' => 888 ]);

		// Act
		$response = $this->actingAs($listing->user)->call('GET', $this->gamePath . '/knapsack');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Beta Item');
		$response->assertSee('Unpublished');
		$response->assertSee('888');
	}

	/** @test */
	function users_can_add_recipes_to_their_knapsack()
	{
		// Arrange
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$recipe = factory(Recipe::class)->create([
			'item_id' => $item->id,
		]);

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/knapsack', [
			'id' => $recipe->id,
			'type' => 'recipe',
		]);

		// Pull back the active listing
		$listing = Listing::with('recipes')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		$this->assertEquals(1, $listing->recipes()->count());
	}

}
