@if(isset($slug) && $slug)
<hr>
<div class="container">
  <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
    @include('blog.partials.disqus')
  </div>
</div>
@endif
<hr>
<!-- JiaThis Button BEGIN -->
<div class="jiathis_style_32x32">
  <a class="jiathis_button_qzone"></a>
  <a class="jiathis_button_tsina"></a>
  <a class="jiathis_button_tqq"></a>
  <a class="jiathis_button_weixin"></a>
  <a class="jiathis_button_renren"></a>
  <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
  <a class="jiathis_counter_style"></a>
</div>
<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- JiaThis Button END -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
        <p class="copyright">Copyright © {{ config('blog.author') }}</p>
      </div>
    </div>
  </div>
</footer>