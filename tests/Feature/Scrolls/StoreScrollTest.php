<?php

namespace Feature\Scrolls;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Concepts\Scroll;
use App\Models\User;
use Tests\ScrollTestCase;

class StoreScrollTest extends ScrollTestCase
{

	public function validParams($overrides = [])
	{
		return array_merge([
			'name'        => 'Awesome Scroll',
			'description' => 'Use this scroll to level up',
		], $overrides);
	}

	/** @test */
	function users_can_submit_scroll_creation()
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

		$response = $this->be($user)->call('POST', '/scrolls', [
			'name'        => 'Awesome Scroll',
			'description' => 'Use this scroll to level up',
			'job_id'      => 17,
			'min_level'   => 1,
			'max_level'   => 255,
		]);

		$scroll = Scroll::first();

		$this->assertEquals('Yeet McGee', $scroll->user->name);
		$this->assertEquals('Awesome Scroll', $scroll->name);
		$this->assertEquals('Use this scroll to level up', $scroll->description);
		$this->assertEquals(17, $scroll->job_id);
		$this->assertEquals(1, $scroll->min_level);
		$this->assertEquals(255, $scroll->max_level);

		$response->assertRedirect(route('compendium', [
			'chapter' => 'scrolls',
			'filter'  => 'mine',
		]));
	}

	/** @test */
	function invalid_names_will_not_create_a_scroll()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$job = factory(Job::class)->create();
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)
			->from('/scrolls/create')
			->call('POST', '/scrolls', $this->validParams([
				'name' => '',
			]));

		$response->assertRedirect('/scrolls/create');
		$response->assertSessionHasErrors('name');
		$this->assertEquals(0, Scroll::count());
	}

	/** @test */
	function invalid_description_will_not_create_a_scroll()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$job = factory(Job::class)->create();
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)
			->from('/scrolls/create')
			->call('POST', '/scrolls', $this->validParams([
				'description' => 'This description is more than 140 characters. This description is more than 140 characters. This description is more than 140 characters. This description is more than 140 characters.',
			]));

		$response->assertRedirect('/scrolls/create');
		$response->assertSessionHasErrors('description');
		$this->assertEquals(0, Scroll::count());
	}

	/** @test */
	function invalid_job_ids_will_not_create_a_scroll()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)
			->from('/scrolls/create')
			->call('POST', '/scrolls', $this->validParams([
				'job_id' => 999,
			]));

		$response->assertRedirect('/scrolls/create');
		$response->assertSessionHasErrors('job_id');
		$this->assertEquals(0, Scroll::count());
	}

	/** @test */
	function invalid_levels_will_not_create_a_scroll()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$this->addItemToNinjaCartCookie($item);

		$response = $this->be($user)
			->from('/scrolls/create')
			->call('POST', '/scrolls', $this->validParams([
				'min_level' => 999,
				'max_level' => 1,
			]));

		$response->assertRedirect('/scrolls/create');
		$response->assertSessionHasErrors('min_level');
		$response->assertSessionHasErrors('max_level');
		$this->assertEquals(0, Scroll::count());
	}

	/** @test */
	function empty_carts_will_not_create_a_scroll()
	{
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		// Not setting NinjaCart cookie

		$response = $this->be($user)->call('POST', '/scrolls', $this->validParams());

		$response->assertRedirect('/sling');
		$this->assertEquals(0, Scroll::count());
	}

}
