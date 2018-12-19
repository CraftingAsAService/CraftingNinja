<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\Games as GamesCollection;

use App\Models\Game;

class PortalController extends Controller
{

	public function index()
	{
		$games = Game::withTranslation()->orderByName()->get();
		$news = $this->getRecentPosts(5);

		return view('portal.index', compact('games', 'news'));
	}

	/**
	 * Get posts from the subreddit made by me
	 * @param  integer $limit [description]
	 * @return [type]         [description]
	 */
	private function getRecentPosts($limit = 10)
	{
		return \Cache::remember('reddit-posts', 30, function() use($limit)
		{
			$subreddit = 'craftingasaservice';

			$posts = [];

			try {
				$client = new \GuzzleHttp\Client();

				$res = $client->request('GET', 'https://api.reddit.com/search.json', [
					'headers' => [
						'User-Agent' => 'User-Agent: php:caas:v1 (by /u/tickthokk)',
					],
					'query' => [
						'q' => 'subreddit:' . $subreddit . ' author:' . config('services.reddit.username'),
						'limit' => 100, // We'll take it down to $limit later
						'sort' => 'new',
					],
				]);

				if ($res->getStatusCode() == '200')
				{
					// string declaration necessary
					$response = json_decode((string) $res->getBody());

					foreach ($response->data->children as $child)
					{
						if ($child->data->distinguished !== null)
						{
							$posts[] = [
								'title' => $child->data->title,
								'url' => $child->data->url == 'self' ? 'https://reddit.com' . $child->data->permalink : $child->data->url,
								'flair' => collect($child->data->link_flair_richtext)->map(function($item, $key) {
									return $item->t;
								})->toArray(),
								'thumbnail' => $child->data->thumbnail == 'self' ? null : $child->data->thumbnail,
								'created' => \Carbon\Carbon::createFromTimeStamp($child->data->created)->format('M d, Y'),
								'date' => \Carbon\Carbon::createFromTimeStamp($child->data->created)->format('Y-m-d')
							];
						}

						if (count($posts) == $limit)
							break;
					}
				}
			} catch (Exception $e) {
				// Do nothing
			}

			return $posts;
		});
	}

}
