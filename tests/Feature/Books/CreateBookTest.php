<?php

namespace Feature\Books;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\BookTestCase;

class CreateBookTest extends BookTestCase
{

	/** @test */
	function user_can_access_creation_form_with_cart_contents()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)->call('GET', $this->gamePath . '/books/create');

		$response->assertOk();
		$response->assertSee('Beta Item');
	}

	/** @test */
	function user_cannot_access_creation_form_without_cart_contents()
	{
		$user = factory(User::class)->create();
		// Not setting NinjaCart cookie

		$response = $this->be($user)->call('GET', $this->gamePath . '/books/create');

		$response->assertRedirect('/knapsack');
	}

	/** @test */
	function guests_cannot_access_creation_form()
	{
		// Not setting User
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$this->addItemToNinjaCartCookie($item);

		$response = $this->call('GET', $this->gamePath . '/books/create');

		$response->assertRedirect('/login');
	}

}
