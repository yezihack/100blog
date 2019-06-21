<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title'){{config_value('site_name', config('blog.title'))}}</title>
    <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{asset('static/css/diquick.css')}}">
    <link rel="stylesheet" href="{{asset('static/fancybox/fancybox.css')}}">
    <link rel="stylesheet" href="{{asset('static/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('static/css/main.css')}}?v={{time()}}">
    <link href="{{asset('static/css/msg_666.css')}}" rel="stylesheet">
    @yield('style')
    <!--[if lt IE 9]>
    <script src="{{asset('static/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('static/js/respond.min.js')}}"></script>
    <![endif]-->
    {!! config_value('STAT_CODE') !!}
</head>
<body>
<div class="wrapper">
    @include('widget.header')
    <!-- main begin -->
    <div class="main">
        <div class="box65 box-s100 fc">
            <div class="box20 box-s10">
                @include('widget.sidebar')
            </div>
            <div class="box80 box-s90 plr">
                @yield('body')
            </div>
        </div>
        <!-- main end -->
    </div>
</div>
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
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });
    $(function () {
//        layer.config({
//            skin: 'msg_666'
//        });
    });
</script>
<script src="{{asset('static/js/main.js')}}"></script>
@yield('script')
</body>
</html>