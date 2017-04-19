<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\Markdowner;
use Carbon\Carbon;

class Post extends Model
{
    protected $dates = ['published_at'];

    protected $fillable = ['title', 'subtitle', 'content_raw', 'page_image', 'meta_description', 'layout', 'is_draft', 'published_at'];
    /**
     * Tht many-to-many relationship between posts and tags
     *
     * @rerurn BelongsToMany
     */
    public function tags()
    {
    	return $this->belongsToMany('App\Tag', 'post_tag_pivot');
    }

    /**
     * Set the title attribute and automatically the slug
     * 
     * @param string $value
     */
    public function setTitleAttribute($value)
    {
    	$this->attributes['title'] = $value;

    	if(! $this->exists) {
    		//$this->attributes['slug'] = str_slug($value);
    		$this->setUniqueSlug($value, '');
    	}
    }

    /**
     * Recursive routine to set a unique slug
     *
     * @param string $title
     * @param mixed $extra
     */
    protected function setUniqueSlug($title, $extra)
    {
    	$slug = str_slug($title.'-'.$extra);

    	if (static::whereSlug($slug)->exists()) {
    		$this->setUniqueSlug($title, $extra + 1);
    		return;
    	}

    	$this->attributes['slug'] = $slug;
    }

    /**
     * Set the HTML content automatically when the raw content is set
     *
     * @param string $value
     */
    public function setContentRawAttribute($value)
    {
    	$markdown = new Markdowner();

    	$this->attributes['content_raw'] = $value;
    	$this->attributes['content_html'] = $markdown->toHTML($value);
    }

    /**
     * Sync tag relation adding new tags as needed
     *
     * @param array tags
     */
    public function syncTags(array $tags)
    {   
    	Tag::addNeededTags($tags);
       
    	if (count($tags)) {
    		$this->tags()->sync(
    			Tag::whereIn('tag', $tags)->pluck('id')->all()
    		);
    		return;
    	}

    	$this->tags()->detach();
    }

    /**
     * Return the date portion of published_at
     */
    public function getPublishDateAttibute($value)
    {
        return $this->published_at->format('M-j-Y');
    }

    /**
     * Return the time portion of published_at
     */
    public function getPublishTimeAttribute($value)
    {
        return $this->published_at->format('g:i A');
    }

    /**
     * Alias for content_raw
     */
    public function getContentAttribute($value)
    {
        return $this->content_raw;
    }

    /**
     * [url return to post]
     * @param  Tag|null $tag
     * @return string        
     */
    public function url(Tag $tag = null)
    {
        $url = url('blog/'.$this->slug);
        if ($tag) {
            $url .= '?tag='.urlencode($tag->tag);
        }

        return $url;
    }

    /**
     * [tagLinks return array of tag links]
     * @param  string $base 
     * @return array
     */
    public function tagLinks($base = '/blog?tag=%TAG%')
    {
        $tags = $this->tags()->pluck('tag');
        $return = [];
        foreach ($tags as $tag) {
            $url = str_replace('%TAG%', urldecode($tag), $bsae);
            $return[] = '<a href="'.$url.'">'.e($tag).'</a>';
        }

        return $return;
    }

    /**
     * [newerPost return next post after this one or null]
     * @param  Tag|null $tag
     * @return Post
     */
    public function newerPost(Tag $tag = null)
    {
        $query =
            static::where('published_at', '>', $this->published_at)
                ->where('published_at', '<=', Carbon::now())
                ->where('is_draft', 0)
                ->orderBy('published_at', 'asc');

        if ($tag) {
            $query = $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tag', '=', $tag->tag);
            });
        }

        return $query->first();
    }

    public function olderPost(Tag $tag = null)
    {
        $query = 
            static::where('published_at', '<', $this->publised_at)
            ->where('is_draft', 0)
            ->orderBy('published_at', 'desc');

        if ($tag) {
            $query = $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tag', '=', $tag->tag);
            });
        }

        return $query->first();
    }
}
