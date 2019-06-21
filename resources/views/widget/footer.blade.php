<div class="footer">
    <div class="shell">
        <p class="fs1 c-999">
            Copyright © 2017.12-{{date('Y.m')}}
            by {{str_replace('-', '', config_value('site_name'))}}
            <a href="https://validator.w3.org/feed/check.cgi?url={{route('rss')}}">
                <img src="{{asset('static/images/valid-rss-rogers.png')}}" alt="[Valid RSS]"
                     title="Validate my RSS feed"/></a>
            <a href="{{ url('sitemap') }}" title="SiteMap 站点地图">
              <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x" style="color:#01AAED"></i>
                <i class="fa fa-map-marker fa-stack-1x fa-inverse"></i>
              </span>
            </a>
            <a href="{{ url('rss') }}" title="RSS feed">
              <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x" style="color: #FFB800;"></i>
                <i class="fa fa-rss fa-stack-1x fa-inverse"></i>
              </span>
            </a>
        </p>
    </div>
</div>
@section('body-bottom')
    <div id="top_div"><a id="top_a" onClick="return false;" title="回到顶部"></a></div>
    <script>
        $(function () {
            $(window).scroll(function () {
                var scrolltop = $(this).scrollTop();
                if (scrolltop >= 100) {
                    $("#top_div").show();
                } else {
                    $("#top_div").hide();
                }
            });
            $("#top_a").click(function () {
                $("html,body").animate({scrollTop: 0}, 500);
            });
        })
    </script>
@stop
{!! config_value('BAIDU_AUTO_PUSH_CODE') !!}
