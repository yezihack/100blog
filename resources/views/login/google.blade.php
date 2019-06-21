@extends('layout')
@section('style')
    <style>
        body {
            background-color: #f1f4f5;
        }

        form {
            color: #333;
        }

        /*--button--*/
        .btn {
            background-color: #666;
            color: #fff;
        }

        .btn:hover {
            color: #fff;
        }

        /*--login--*/
        .login {
            overflow: hidden;
            margin: 8% auto 0 auto;
            max-width: 450px;
            border-radius: .5rem;
            box-shadow: 0 5px 25px 0 rgba(0, 0, 0, 0.16);
        }

        .login > .title {
            padding: 1.5rem 0;
            background-color: #666;
            color: #fff;
            text-align: center;
            font-size: 1.75rem;
        }

        .login > .content {
            padding: 2rem;
            background-color: #fff;
        }

        .captch {
            width: 50% !important
        }

        .img_radius {
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
    </style>
@endsection
@section('title')登陆@stop
@section('body')
    @include('widget.header')
    <!-- wrapper begin -->
    <div class="wrapper">
        <!-- main begin -->
        <div class="main">
            <!-- login begin -->
            <div class="login">
                <div class="title">{{$title}}</div>
                <div class="content">
{{--                    @include('login.google2fa', ['isCaptcha' => $isCaptcha])--}}
                    @include('login.google2fa')
                </div>
            </div>
            <!-- login end -->
        </div>
        <!-- main end -->
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $("#google2fa").click(function () {
                var _code = $("#google_code");
                var capCode = $("#capCode");
                if($.trim(_code.val()) === '') {
                    layer.tips("请输入动态验证码", "#google_code", {tips: [1, '#FF5722']});
                    _code.focus();
                    return false;
                }
                $.post('{{route('google2fa')}}', {code:_code.val(), capCode:capCode.val()}, function (rev) {
                    if(rev.status === 0) {
                        window.location.href = rev.data;
                    } else {
                        layer.msg(rev.msg, {icon: 5, anim: 6});
                    }
                }, 'json')
            });
        });
        $(document).keyup(function (event) {
            if (event.keyCode == 13) {
                $("#login").trigger("click");
            }
        });
        $(".login img.cursor").mouseover(function () {
            layer.tips("点击刷新验证码", this, {tips: [1, '#666']});
        });
    </script>
@endsection