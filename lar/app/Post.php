<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $dates = ['published_at'];

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
}
