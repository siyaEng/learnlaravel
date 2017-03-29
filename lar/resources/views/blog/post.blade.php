<!DOCTYPE html>
<html>
<head>
	<title>{{ config('blog.title')}}</title>
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>{{ config('blog.title')}}</h1>
		<h5>{{ $post->published_at}}</h5>
		<hr>
			{{!! nl2br(e($post->content)) !!}}
		<hr>
		<button class="btn btn-primary" onclick="history.go(-1)">
			<< Back
		</button>
	</div>
</body>
</html>