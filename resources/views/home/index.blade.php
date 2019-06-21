@extends('layout')
@section('title', '首页')
@section('keywords', config_value('KEYWORDS'))
@section('desc', config_value('DESCRIPTION'))
@section('body')
    <div class="wrapper">
        <!-- header begin -->
    @include('widget.header')
    <!-- header end -->
        <!-- main begin -->
        <div class="main">
            <div class="shell">
                <!-- sidebar begin -->
            @include('widget.rightbar')
            <!-- sidebar end -->
                <!-- container begin -->
                <div class="container fl">
                    <div class="mini-list" id="home-index">
                        @if(isset($tag_name))
                        <div class="panel">
                            <div class="title"><span class="fs5"><b><i class="flaticon-tag63"></i>标签目录：{{$tag_name}}</b></span>
                                <div><span class="c-999 fs2 o7">(以下是与标签 “{{$tag_name}}” 相关联的文章)</span></div>
                            </div>
                        </div>
                        @endif
                        @foreach($pages as $item)
                            <div class="summary-li narrow box100">
                                <div class="cp">
                                    <div class="votes">
                                        <a class="view-tag bg-green uppercase" href="{{route('tag', [$item->first_tag])}}">{{$item->first_tag}}
                                        </a>
                                    </div>
                                </div>
                                <div class="summary">
                                    <h3 class="box100">
                                        <a href="{{route('blog.view', $item->id)}}"
                                           class="question-hyperlink">{{$item->title}}
                                            <span class="tooltip right">点击阅读:{{$item->title}}</span>
                                        </a>
                                    </h3>
                                    <div class="tags t-python t-tensorflow t-deep-learning t-object-detection">
                                        @foreach($item->tags as $tag)
                                            <a href="{{route('tag', [$tag])}}"
                                               class="post-tag">#{{$tag}}
                                                <span class="tooltip right">查看更多相关文章</span>
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="started">
                                        <span class="started-link"><i
                                                    class="fa fa-clock-o">{{$item->updated_at->diffForHumans()}}</i></span>
                                        <span class="started-link"><i class="fa fa-eye">{{$item->read_count}}</i></span>
                                        <a href="#" class="started-link"><i
                                                    class="fa fa-user-circle-o">{{$item->username}}</i></a>
                                        <span class="reputation-score"></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="box50 offset30 hide-s hide">
                            {!! $pages->render()  !!}
                        </div>
                        @if($pages->hasMorePages())
                            <div class="box60 offset30 show-s">
                                <ul class="page pr3">
                                    <li @unless($pages->previousPageUrl())class="disabled"@endunless><a
                                                href="{{$pages->previousPageUrl()}}">上一页</a></li>
                                    <li class="disabled"><a href="#">{{$pages->currentPage()}}</a></li>
                                    <li @unless($pages->nextPageUrl())class="disabled"@endunless><a
                                                href="{{$pages->nextPageUrl()}}">下一页</a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <!-- container end -->
                </div>
            </div>
            <!-- main end -->

        </div>
    </div>
    <!-- footer end -->
    @include('widget.footer')
    <!-- footer end -->
@endsection
@section('script')
@stop