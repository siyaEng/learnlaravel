<?php

namespace App\Services;

use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

class RssFeed
{	
	/**
	 * [getRs]
	 * @return the content of the Rss fees
	 */
	public function getRss()
	{	
		if(Cache::has('rss-feed')) {
			return Cache::get('rss-feed');
		}

		$rss = $this->buildRssData();
		
		Cache::add('rss-feed', $rss, 120);

		return $rss;
	}

	/**
	 * [buildRssData return a string with the feed data]
	 * @return string
	 */
	protected function buildRssData()
	{
		$now = Carbon::now();
		$feed = new Feed();
		$channel = new Channel();
		$channel
			->title(config('blog.title'))
			->description(config('blog.description'))
			->url(url())
			->language('en')
			->copyright('Copyright (c)'.config('blog.author'))
			->lastBuildDate($now->timestamp)
			->appendTo($feed);

		$posts = Post::where('published_at', '<=', $now)
			->where('is_draft', 0)
			->orderBy('published_at', 'desc')
			->take(config('blog.rss_size'))
			->get();
		
		foreach ($posts as $post) {
			$item = new Item();

			$url = trim($post->url(), '');
			//$url = $post->url();
			$item->title($post->title)
				 ->description($post->subtitle)
				 ->url($url)
				 ->pubDate($post->published_at->timestamp)
				 ->guid($post->url(), true)
				 ->appendTo($channel);
		}
		
		$feed = (String)$feed;
		
		return $feed;
	}
}