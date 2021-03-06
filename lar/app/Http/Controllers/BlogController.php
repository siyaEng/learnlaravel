<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Jobs\BlogIndexData;
use App\Http\Requests;
use App\Post;
use App\Tag;
use Carbon\Carbon;
use App\Services\RssFeed;
use App\Services\SiteMap;

use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $tag = $request->get('tag');
        $data = $this->dispatchNow(new BlogIndexData($tag));
        
        //$layout = $tag ? Tag::layout($tag)[0] : 'blog.layouts.index';
        $layout = 'blog.layouts.index';

        return view($layout, $data);
    }

    public function showPost($slug, Request $request)
    {   
      
        $post = Post::with('tags')->whereSlug($slug)->firstOrFail();
        
        $tag = $request->get('tag');
        if ($tag) {
            $tag = Tag::whereTag($tag)->firstOrFail();
        }

        return view($post->layout, compact('post', 'tag', 'slug'));
    }

    public function rss(RssFeed $feed)
    {   
        $rss = $feed->getRss();
    
        return response($rss)
                ->header('Content-type', 'application/rss+xml');
    }

    public function siteMap(SiteMap $siteMap)
    {
        $map = $siteMap->getSiteMap();

        return response($map)
            ->header('Content-type', 'text/xml');
    }
}
