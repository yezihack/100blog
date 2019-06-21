<style>

</style>
<div class="sidebar fr hide-s" id="rightbar">
    <div class="separator"><i class="flaticon-tag64"></i>热门标签
        <small>常出现的标签</small>
    </div>
    <dl class="baselist">
        @foreach($tags as $tag)
            <a href="{{$tag->url}}" class="btn xs mb1 {{$tag->class}}"><i class="flaticon-tag63"></i>{{$tag->name}}
                <span class="tooltip top">{{$tag->count}}篇文章</span>
            </a>
        @endforeach
    </dl>
    <div class="separator"><i class="flaticon-sunny14"></i>最新文章
        <small>近一周的文章</small>
    </div>
    <dl class="baselist">
        @foreach($news as $item)
            <dd><a href="{{$item->url}}" target="_blank"><i class="flaticon-pin44 c-999"></i>{{$item->title}}
                </a>
            </dd>
        @endforeach
    </dl>
    <div class="separator"><i class="flaticon-photo190"></i>浏览最多
        <small>查看次数最多的文章</small>
    </div>
    <dl class="baselist">
        @foreach($views as $item)
            <dd><a href="{{$item->url}}" target="_blank"><i class="flaticon-pin44 c-999"></i>{{$item->title}}</a></dd>
        @endforeach
    </dl>
    <div class="separator"><i class="flaticon-star161"></i>点赞最多
        <small>获赞数量最多的文章</small>
    </div>
    <dl class="baselist">
        @foreach($stars as $item)
            <dd>
                <a href="{{$item->url}}" target="_blank"><i class="flaticon-pin44 c-999"></i>{{$item->title}}
                </a>
            </dd>
        @endforeach
    </dl>
    {{--<div class="separator"><span>友情链接</span>--}}
    {{--<small>申请加入</small>--}}
    {{--</div>--}}
    {{--<ul class="box-advance list4">--}}
    {{--<li><a href="#">博客1</a></li>--}}
    {{--<li><a href="#">博客1</a></li>--}}
    {{--<li><a href="#">博客1</a></li>--}}
    {{--<li><a href="#">博客1</a></li>--}}
    {{--<li><a href="#">博客1</a></li>--}}
    {{--</ul>--}}
</div>
