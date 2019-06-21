@extends('layout')
@section('title', '注册中心')
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
    </style>
@endsection
@section('body')
    @include('widget.header')
    <!-- wrapper begin -->
    <div class="wrapper">
        <!-- main begin -->
        <div class="main">
            <!-- login begin -->
            <div class="login">
                <div class="title">轻博客注册</div>
                <div class="content">
                    <form>
                        <ul class="form ratio100">
                            <li><span>用户名称</span>
                                <div><i class="flaticon-user151"></i><input name="name" id="name" type="text"
                                                                            autocomplete="false"></div>
                            </li>
                            <li><span>安全密码</span>
                                <div><i class="flaticon-locked44"></i><input name="pass" id="pass" type="password"
                                                                             autocomplete="false"></div>
                            </li>
                            <li><span>确认密码</span>
                                <div><i class="flaticon-locked44"></i><input name="pass2" id="pass2" type="password"
                                                                             autocomplete="false"></div>
                            </li>
                            <li class="tc">
                                <button type="button" class="btn" id="register">免费注册</button>
                            </li>
                        </ul>
                    </form>
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
            $("#register").click(function () {
                var _name = $("#name");
                var _pass = $("#pass");
                var _pass2 = $("#pass2");
                if ($.trim(_name.val()) === '') {
                    layer.tips("请输入您的用户名称", "#name", {tips: [1, '#FF5722']});
                    _name.focus();
                    return false;
                }
                if ($.trim(_pass.val()) === '') {
                    layer.tips("请输入密码", "#pass", {tips: [1, '#FF5722']});
                    _pass.focus();
                    return false;
                }
                if ($.trim(_pass2.val()) === '') {
                    layer.tips("请输入确认密码", "#pass2", {tips: [1, '#FF5722']});
                    _pass2.focus();
                    return false;
                }
                var params = {};
                params.name = _name.val();
                params.pass = _pass.val();
                params.pass2 = _pass2.val();
                $.post("{{route('register')}}", params, function (rev) {
                    if (rev.status === 0) {
                        layer.alert(rev.msg, {}, function () {
                            window.location.href = rev.data;
                        });
                    } else {
                        layer.msg(rev.msg, {icon: 5, anim: 6});
                    }
                });
            });
        });
        $(document).keyup(function(event){
            if(event.keyCode ==13){
                $("#register").trigger("click");
            }
        });
    </script>
@endsection