<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('desc')" />
    <title>@yield('title'){{config_value('site_name', config('blog.title'))}}</title>
    <meta property="og:type" content="@yield('type', 'website')">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:url" content="{{getenv('APP_URL')}}">
    <meta property="og:site_name" content="{{config_value('site_name', null, true)}}">
    <meta property="og:description" content="@yield('desc')">
    <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('static/css/diquick.css')}}">
    <link rel="stylesheet" href="{{asset('static/fancybox/fancybox.css')}}">
    <link rel="stylesheet" href="{{asset('static/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('static/css/main.css')}}">
    <link href="{{asset('static/css/msg_666.css')}}" rel="stylesheet">
    @yield('style')
    <!--[if lt IE 9]>
    <script src="{{asset('static/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('static/js/respond.min.js')}}"></script>
    <![endif]-->
    {!! config_value('STAT_CODE') !!}
</head>
<body>
@section('body')
    {{--主体--}}
@show
<div id="loading" class="mask">
    <div class="content">
        <div class="loading inverse">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
    <i data-close="mask" class="flaticon-cross89"></i>
</div>
<script src="{{asset('static/js/jquery.js')}}"></script>
<script src="{{asset('static/fancybox/fancybox.js')}}"></script>
<script src="{{asset('static/js/diquick.js')}}"></script>
<script src="{{asset('static/layer/layer.js')}}"></script>
<script src="{{asset('static/js/vue.min.js')}}"></script>
<script src="{{asset('static/js/main.js')}}"></script>
<script>
$.ajaxSetup({headers:{"X-CSRF-TOKEN":"{{ csrf_token() }}"}});$(function(){layer.config({skin:"msg_666"})});console.log("欢迎来到时光博客.");console.log("站长的码云地址:https://gitee.com/sgfoot");
</script>
@yield('body-bottom')
@yield('script')
</body>
</html>