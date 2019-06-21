@extends('layout')
@section('style')
@stop
@section('title', '登陆')
@section('body')
    @include('widget.header')
    <!-- wrapper begin -->
    <div class="wrapper">
        <!-- main begin -->
        <div class="main">
            <!-- login begin -->
            <div class="box33 offset33 box-s100 offset-s0 plr">
                <div class="panel login">
                    <div class="title"><h1 class="fs7 b">登入LOGIN</h1></div>
                    <div class="content">
                        <form>
                            <ul class="form ratio-s100">
                                <li class="box100"><span>用户名称</span>
                                    <div class="box100"><i class="flaticon-user151"></i><input name="name" id="name"
                                                                                               type="text"
                                                                                               placeholder="用户名"
                                                                                               autocomplete="false">
                                    </div>
                                </li>
                                <li class="box100"><span>安全密码</span>
                                    <div class="box100"><i class="flaticon-password14"></i><input name="pass" id="pass"
                                                                                                  placeholder="安全密码"
                                                                                                  type="password"
                                                                                                  autocomplete="false">
                                    </div>
                                </li>
                                @if($isCaptcha > 0)
                                    <li class="box50"><span>验证码</span>
                                        <div class="box100"><i class="flaticon-letter52"></i><input PLACEHOLDER="不区分大小写"
                                                                                                    type="text"
                                                                                                    name="code"
                                                                                                    id="code"></div>
                                    </li>
                                    <li class="box50 plr"><span>&nbsp;</span>
                                        <div class="box100">
                                            <img src="{{captcha_src()}}" id="captcha" class="cursor"
                                                 onclick="this.src='{{captcha_src()}}?'+Math.random()">
                                        </div>
                                    </li>
                                @endif
                                <li class="box100">
                                    <div><label class="line"><input name="remember" id="remember"
                                                                    type="checkbox">记住我</label></div>
                                </li>
                                <li class="">
                                    <button type="button" class="btn" id="login">安全登陆</button>
                                    <input type="hidden" value="{{$isCaptcha}}" id="isCaptcha">
                                </li>
                            </ul>
                        </form>
                    </div>
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
                var _name = $("input[name='name']");
                var _pass = $("input[name='pass']");
                var _code = $("input[name='code']");
                var _remember = $("input[name='remember']");
                if ($.trim(_name.val()) === '') {
                    layer.tips("请输入您的用户名称", _name, {tips: [1, '#666']});
                    _name.focus();
                    return false;
                }
                if ($.trim(_pass.val()) === '') {
                    layer.tips("请输入您的密码", _pass, {tips: [1, '#666']});
                    _pass.focus();
                    return false;
                }
                if ($("#isCaptcha").val() > 0) {
                    if ($.trim(_code.val()) === '') {
                        layer.tips("请输入验证码", _code, {tips: [1, '#666']});
                        _pass.focus();
                        return false;
                    }
                }
                var params = {};
                params.name = _name.val();
                params.pass = _pass.val();
                params.code = _code.val();
                params.remember = _remember.is(':checked') ? 1 : 0;
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