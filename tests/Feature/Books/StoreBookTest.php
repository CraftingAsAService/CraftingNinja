<?php

namespace Feature\Books;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\BookTestCase;

class CreateBookTest extends BookTestCase
{

	/** @test */
	function users_can_submit_listing_creation()
	{
		$user = factory(User::class)->create([
			'name' => 'Yeet McGee',
		]);
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$job = factory(Job::class)->create([
			'id' => 17,
		]);
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)->call('POST', $this->gamePath . '/books', [
			'name'        => 'Awesome Book',
			'description' => 'Use this book to level up',
			'job_id'      => 17,
			'min_level'   => 1,
			'max_level'   => 255,
		]);

		$listing = Listing::first();

		$this->assertEquals('Yeet McGee', $listing->user->name);
		$this->assertEquals('Awesome Book', $listing->name);
		$this->assertEquals('Use this book to level up', $listing->description);
		$this->assertEquals(17, $listing->job_id);
		$this->assertEquals(1, $listing->min_level);
		$this->assertEquals(255, $listing->max_level);

		$response->assertRedirect('/compendium?chapter=books&filter=yours');
	}

	/** @test */
	function invalid_names_will_not_create_a_listing()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$job = factory(Job::class)->create();
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)
			->from($this->gamePath . '/books/create')
			->call('POST', $this->gamePath . '/books', $this->validParams([
				'name' => '',
			]));

		$response->assertRedirect($this->gamePath . '/books/create');
		$response->assertSessionHasErrors('name');
		$this->assertEquals(0, Listing::count());
	}

	/** @test */
	function invalid_job_ids_will_not_create_a_listing()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)
			->from($this->gamePath . '/books/create')
			->call('POST', $this->gamePath . '/books', $this->validParams([
				'job_id' => 999,
			]));

		$response->assertRedirect($this->gamePath . '/books/create');
		$response->assertSessionHasErrors('job_id');
		$this->assertEquals(0, Listing::count());
	}

	/** @test */
	function invalid_levels_will_not_create_a_listing()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)
			->from($this->gamePath . '/books/create')
			->call('POST', $this->gamePath . '/books', $this->validParams([
				'min_level' => 999,
				'max_level' => 1,
			]));

		$response->assertRedirect($this->gamePath . '/books/create');
		$response->assertSessionHasErrors('min_level');
		$response->assertSessionHasErrors('max_level');
		$this->assertEquals(0, Listing::count());
	}

}
