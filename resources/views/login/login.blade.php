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
                    @include('login.login_form')
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
            $("#login").click(function () {
                var _name = $("#name");
                var _pass = $("#pass");
                var _code = $("#code");
                var _forget = $("#forget");
                if ($.trim(_name.val()) === '') {
                    layer.tips("请输入您的用户名称", "#name", {tips: [1, '#FF5722']});
                    _name.focus();
                    return false;
                }
                var params = {};
                params.name = _name.val();
                params.pass = _pass.val();
                params.code = _code.val();
                params.forget = _forget.is(':checked') ? 1 : 0;
                $.post("{{route('login')}}", params, function (rev) {
                    if (rev.status === 0) {
                        window.location.href = rev.data;
                    } else {
                        $("#captcha").attr("src", '{{captcha_src()}}?' + Math.random());
                        layer.msg(rev.msg, {icon: 5, anim: 6});
                    }
                });
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