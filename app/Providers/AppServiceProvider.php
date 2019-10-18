<?php

namespace App\Providers;

use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Npc;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Aspects\Shop;
use App\Models\Game\Concepts\Scroll;
use App\Models\Game\Concepts\Niche;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Resource::withoutWrapping();

		Builder::macro('addSubSelect', function($column, $query) {
			if (is_null($this->getQuery()->columns))
				$this->select($this->getQuery()->from . '.*');

			return $this->selectSub($query->limit(1)->getQuery(), $column);
		});

		Builder::macro('orderBySub', function($query, $direction = 'asc') {
			return $this->orderByRaw("({$query->limit(1)->toSql()}) {$direction}");
		});

		Builder::macro('orderBySubDesc', function($query) {
			return $this->orderBySub($query, 'desc');
		});

		Relation::morphMap([
			'job'       => Job::class,
			'niche'     => Niche::class,
			'category'  => Category::class,
			'shop'      => Shop::class,
			'npc'       => Npc::class,
			'recipe'    => Recipe::class,
			'objective' => Objective::class,
			'item'      => Item::class,
			'node'      => Node::class,
			'scroll'    => Scroll::class,
			'zone'      => Zone::class,
		]);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
