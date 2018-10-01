<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Game\Aspects\Attribute;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Job;

use App\Models\Game\Concepts\Equipment;

use Cache;

class SpaController extends Controller
{

	public function index()
	{
		$payload = $this->payload();
		// dd($payload['jobs']);
		return view('spa', compact('payload'));
	}

	private function payload()
	{
		return Cache::remember(config('game.slug') . '-payload', 1440, function() {
			$categories = Category::orderBy('id')->get();

			$jobs = [];
			$allJobs = Job::withTranslation()->orderBy('type')->orderBy('tier')->orderBy('id')->get();
			foreach ($allJobs->pluck('type')->unique() as $type)
				foreach (array_keys(config('game.jobTiers')) as $tier)
				{
					$jobs[$type][$tier] = $allJobs->filter(function ($job) use ($type, $tier) {
						return $job->type == $type && $job->tier == $tier;
					})->toArray();

					if (empty($jobs[$type][$tier]))
						unset($jobs[$type][$tier]);
					else
						foreach ($jobs[$type][$tier] as &$data)
							unset($data['translations']);
				}
			unset($data);

			return [
				'jobs' => $jobs,
				'categoryTree' => Category::tree($categories),
				'flatCategories' => $categories->pluck('name', 'id')->toArray(),
				'attributes' => Attribute::sortByName()->get()->map(function($attribute) {
					return [
						'id' => $attribute->id,
						'name' => $attribute->name,
						'display' => true
					];
				})->toArray(),
				'ilvlRange' => [
					'min' => Item::min('ilvl'),
					'max' => Item::max('ilvl'),
				],
				'rlvlRange' => [
					'min' => Recipe::min('level'),
					'max' => Recipe::max('level'),
				],
				'elvlRange' => [
					'min' => Equipment::min('level'),
					'max' => Equipment::max('level'),
				],
				'rslvlRangeList' => Recipe::groupBy('sublevel')->where('sublevel', '>', 0)->orderBy('sublevel')->get()->pluck('sublevel')->toArray(),
				'socketsRangeList' => Equipment::groupBy('sockets')->where('sockets', '>', 0)->orderBy('sockets')->get()->pluck('sockets')->toArray(),
			];
		});
	}

}
