@extends('layout')
@section('title', $blog->title)
@section('keywords', $blog->keywords)
@section('desc', $blog->desc)
@section('type', 'article')
@section('style')
    <link rel="stylesheet" href="{{asset('static/editormd/css/editormd.preview.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('static/editormd/css/editormd.i.css')}}"/>
@stop
@section('body')
    <div class="wrapper" id="view_index">
        <!-- header begin -->
    @include('widget.header')
    <!-- header end -->
        <!-- main begin -->
        <div class="main">
            <ul class="shell">
                <!-- sidebar begin -->
            @include('widget.rightbar')
            <!-- sidebar end -->
                <!-- container begin -->
                <div class="container fl">
                    <ul class="breadcrumb">
                        <li><a href="{{route('home')}}">首页</a></li>
                        <li><a href="{{route('tag', [$blog->first_tag])}}">{{$blog->first_tag}}</a></li>
                        <li><a href="{{route('blog.view', $blog->id)}}">{{$blog->title}}</a></li>
                    </ul>
                    <div class="news box100 plr">
                        <h1 class="title tc">{{$blog->title}}</h1>
                        <div class="box100 mb5 tr">
                            <i class="fa fa-eye"></i>
                            <small>{{$blog->read_count}}<span class="tooltip top">浏览次数</span></small>
                            <i class="fa fa-thumbs-o-up"></i>
                            <small class="star">{{$blog->star_count}}<span class="tooltip top">获赞数量</span></small>
                            <i class="fa fa-clock-o"></i>
                            <small>{{$blog->created_at->diffForHumans()}}
                                <span class="tooltip top">发表于:{{$blog->created_at->format('Y-m-d H:i:s')}}</span>
                            </small>
                            {{--<i class="fa fa-comments"></i>--}}
                            {{--<small>22</small>--}}
                            <i class="fa fa-user-circle-o"></i>
                            <small>{{$blog->username}}</small>
                            <i class="fa fa-info-circle"></i>
                            <small>{{$blog->format_type}}<span class="tooltip top">{{$blog->format_type_tips}}</span>
                            </small>
                        </div>
                        <div class="box100" id="content-editormd-view">
                            <textarea style="display:none;" name="editormd-markdown-doc">
{{$blog->content}}
                            </textarea>
                        </div>
                    </div>
                    {{--<div class="separator"></div>--}}
                    @if(session_has('user'))
                        <div class="box100 tr plr mt5">
                            <a class="xs" href="{{route('blog.edit', [$blog->id])}}"><i class="flaticon-pencil91"></i>
                                <span class="tooltip bottom">点击编辑</span>
                            </a>
                        </div>
                    @endif
                    @if($blog->type == 1)
                        <div class="box100 mt5 plr">
                            <blockquote style="border-left:6px solid #ff6c60;background-color:#f5f5f1">
                                <h5>如果觉得文章对您有用，请给予本站支持！</h5>
                                <cite>
                                    <small>---sgfoot原创文章,未经许可，禁止转载!</small>
                                </cite>
                            </blockquote>
                        </div>
                    @endif
                    <div class="box100 tc" id="help_extension">
                        <button class="btn bg-orange" v-on:click.once="star"><i
                                    class="fa fa-thumbs-o-up"></i>&nbsp;赞(<span
                                    class="star">{{$blog->star_count}}</span>)
                            <span class="tooltip right">手码文字不易,动动手指,给作者一个赞,谢谢</span>
                        </button>
                        <button class="hidei btn bg-lime money "><i class="fa fa-cny"></i>&nbsp;打赏</button>
                    </div>
                    <div class="box100 plr">
                        <i class="fa fa-tags"></i>
                        @foreach($blog_tags as $item)
                            <a href="{{$item->url}}" class="tags">#{{$item->name}}</a>
                        @endforeach
                    </div>
                    <div class="box100 plr mt5 mb5">
                        @if($blog->prev_url)
                            <a class="btn xs fl" href="{{$blog->prev_url}}"><i class="flaticon-left178"></i>上一篇
                                <span class="tooltip top">{{$blog->prev_title}}</span>
                            </a>
                        @endif
                        @if($blog->next_url)
                            <a class="btn xs fr" href="{{$blog->next_url}}">下一篇
                                <span class="tooltip top">{{$blog->next_title}}</span>
                                <i class="flaticon-arrow607"></i>
                            </a>
                        @endif
                    </div>
                    <div class="box100 mt5 mb5 like" id="like">
                        <h3 class="fs4"><i class="flaticon-interface52"></i>你可能感兴趣的文章</h3>
                        <ul class="box-advance list1 list-s1 lh200 fs2 o9 plr" v-for="item in list">
                            <li><i class="fa fa-dot-circle-o fs1"></i><a class="ml2"
                                                                         :href="item.url">@{{item.title}}</a></li>
                        </ul>
                    </div>
                </div>
            </ul>
        </div>
        <!-- container end -->
    </div>
    @include('widget.footer')
@stop
@section('script')
    @include('widget.editorJs')
    <script>
        $(function () {
            var blog_id = "{{$blog->id}}";
            var editor = editormd.markdownToHTML("content-editormd-view", {
                htmlDecode: "style,script,iframe",  // you can filter tags decode
                emoji: true,
                taskList: true,
                tex: true,  // 默认不解析
                flowChart: true,  // 默认不解析
                sequenceDiagram: true,  // 默认不解析
                tocm: true,//菜单
                tocContainer: "",
                tocDropdown: false
            });
            new Vue({
                el: "#help_extension"
                , methods: {
                    star: function () {
                        $.post("{{route('blog.star')}}", {id: blog_id}, function (rev) {
                            if (rev.status === 0) {
                                $(".star").html(rev.data);
                            }
                        });
                    }
                }
            });
            var likeVue = new Vue({
                el: "#like"
                , data: {
                    show: false,
                    list: []
                }
                , mounted: function () {
                    this.load_like();
                }
                , methods: {
                    load_like: function () {
                        $.post("{{route('blog.like')}}", {id: blog_id}, function (rev) {
                            if (rev.status === 0) {
                                likeVue.show = true;
                                likeVue.list = rev.data;
                            } else {
                                console.log(rev.msg);
                            }
                        });
                    }
                }
            });
        });
    </script>
@stop