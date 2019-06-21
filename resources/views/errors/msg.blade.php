@extends('layout')
@section('title', '标签'.config_value('site_name', config('blog.title')))
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
                    <div class="panel">
                        <div class="title">系统提示</div>
                        <div class="content"><h3>{{$msg}}</h3></div>
                    </div>
                </div>
            </ul>
        </div>
        <!-- container end -->
    </div>
    @include('widget.footer')
@stop
