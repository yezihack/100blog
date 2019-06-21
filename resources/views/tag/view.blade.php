@extends('layout')
@section('title', '标签')
@section('body')
    <div class="wrapper">
        <!-- header begin -->
    @include('widget.header')
    <!-- header end -->
        <!-- main begin -->
        <div class="main mb5">
            <ul class="shell">
                <!-- sidebar begin -->
                <div class="box100">
                    <blockquote><h1 class="fs4"><i class="fa fa-tags"></i>标签云</h1></blockquote>
                    <ul class="accordion">
                        <li class="active">
                            <div class="title"><i class="flaticon-search86"></i>快捷导航</div>
                            <div class="content">
                                @foreach($list['nav_tag'] as $item)
                                    <a class="btn xs @unless($item['count']) bg-999 @endif mb3"
                                       href="#{{$item['char']}}">#{{$item['char']}}
                                        <div class="tooltip top">{{$item['count']}}个签标</div>
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                    <div class="" style="padding: .5rem 1.5rem;">
                        @foreach($list['tags'] as $char => $tag)
                            <div class="separator mt8"><i class="fa fa-tag"></i>：<a name="{{$char}}">{{$char}}
                                    <span class="tooltip top">{{$char}}标签</span>
                                </a></div>
                            @foreach($tag as $item)
                                <a class="btn xs" href="{{route('tag', [$item['name']])}}">{{$item['name']}}
                                    <div class="tooltip top">{{$item['count']}}篇文章</div>
                                </a>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </ul>
        </div>
        <!-- container end -->
    </div>
    @include('widget.footer')
@stop
