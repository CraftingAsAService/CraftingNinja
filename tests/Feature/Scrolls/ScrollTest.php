<?php

namespace Feature\Scrolls;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Scroll;
use App\Models\User;
use Tests\ScrollTestCase;

class ScrollTest extends ScrollTestCase
{

	/** @test */
	function user_can_view_recipe_scrolls()
	{
		// Arrange
		factory(Scroll::class)->states('published')->create([
			'name:en' => 'Alpha Scroll',
		]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/scrolls');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Scroll');
	}

	/** @test */
	function users_can_make_ajax_call_for_scrolls()
	{
		// Arrange
		factory(Scroll::class)->states('published')->create([
			'name:en' => 'Alpha Scroll',
		]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/api/scrolls');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Scroll');
	}











	/** @test */
	function users_can_publish_their_own_scrolls()
	{
		// Arrange
		$scroll = factory(Scroll::class)->states('unpublished')->create();

		// Act
		$response = $this->actingAs($scroll->user)->call('POST', $this->gamePath . '/scrolls/' . $scroll->id . '/publish', [
			'dir' => 1
		]);

		// Assert
		$response->assertJson([
			'published' => true,
		]);
	}

	/** @test */
	function users_can_unpublish_their_own_scroll()
	{
		// Arrange
		$scroll = factory(Scroll::class)->states('published')->create([
			'name:en' => 'Alpha Scroll',
		]);

		// Act
		$response = $this->actingAs($scroll->user)->call('POST', $this->gamePath . '/scrolls/' . $scroll->id . '/publish', [
			'dir' => -1
		]);

		// Assert
		$response->assertJson([
			'published' => false,
		]);
	}

	/** @test */
	function users_cannot_publish_anothers_scroll()
	{
		// Arrange
		$scroll = factory(Scroll::class)->states('unpublished')->create();
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/scrolls/' . $scroll->id . '/publish', [
			'dir' => 1
		]);

		// Assert
		$response->assertJson([
			'published' => false,
		]);
	}

	/** @test */
	function users_cannot_unpublish_anothers_scroll()
	{
		// Arrange
		$scroll = factory(Scroll::class)->states('published')->create([
			'name:en' => 'Alpha Scroll',
		]);
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/scrolls/' . $scroll->id . '/publish', [
			'dir' => -1
		]);

		// Assert
		$response->assertJson([
			'published' => true,
		]);
	}

	/** @test */
	function user_can_view_scroll_contents()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		// Create the scroll and attach the item with a quantity of 999
		$scroll = factory(Scroll::class)->state('published')->create([
			'name:en' => 'Alpha Scroll',
		]);
		$scroll->items()->save($item, [ 'quantity' => 999 ]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/scrolls/' . $scroll->id);

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Scroll');
		$response->assertSee('Beta Item');
		$response->assertSee('999');
	}

}
