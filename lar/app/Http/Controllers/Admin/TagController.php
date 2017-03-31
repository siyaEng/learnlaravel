<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Tag;
class TagController extends Controller
{
    public function index()
    {
    	$tags = Tag::all();
    	return view('admin.tag.index')->withTags($tags);
    }

    protected $fields = [
    	'tag' => '',
    	'title' => '',
    	'subtitle' => '',
    	'meta_description' => '',
    	'page_image' => '',
    	'layout' => 'blog.layout.index',
    	'reverse_direction' => 0,
    ];

    //替换create()方法如下
    /**
     * Show form for creating new tag
     */
    public function create()
    {
    	$data = [];
    	foreach ($this->fields as $field => $default) {
    		$data[$field] = old($field, $default);
    	}

    	return view('admin.tag.create', $data);
    }
}
