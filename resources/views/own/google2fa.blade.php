@extends('managerLayout')
@section('title', $title)
@section('style')
    <style type="text/css">
        .unline{
           text-decoration: none;
        }
    </style>
@stop

@section('body')
    <div class="panel">
        <div class="title">{{$title}}</div>
        <div class="content">
            @if($qrInfo && $key)
            <div class="msg correct">请使用你的身份验证器，扫描二维码</div>
            <div class="media">
                <div class="img">{!! app('qrcode')->size(180)->generate($qrInfo) !!}</div>
                <div class="content">
                    <div class="msg info">或輸入密碼: {{$key}}</div>
                </div>
            </div>
            <ul class="form">
                <li><span>然后输入验证码：</span>
                    <div>
                        <input type="text" id="code" placeholder="输入动态码">
                    </div>
                </li>
                <li class="tc">
                    <button type="button" id="ok" class="btn">确认</button>
                </li>
            </ul>
            @else
            <ul>
                <li>
                    <button type="button" id="relieve" class="btn bg-orange">解绑谷歌两步验证</button>
                </li>
            </ul>
            @endif
        </div>
        <div class="footer">
            <div class="msg info">
                <i data-close="msg" class="flaticon-cross89"></i>
                <h5>如何使用谷歌两步验证？</h5>
                <ol class="baselist">
                    <li>方法一：下载app, <a class="c-orange" href="http://s1.sgfoot.com/google2fa.apk">安卓</a>，<a target="_blank" class="c-orange" href="https://itunes.apple.com/cn/app/google-authenticator/id388497605?mt=8">苹果</a></li>
                    <li>方法二：微信小程，点击+，搜索：“Ok两步验证器”<span class="badge bg-green">推荐</span></li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $("#ok").click(function () {
                var code = $("#code");
                if ($.trim(code.val()) === '') {
                    layer.tips("请输入动态码", "#code", {tips: 1});
                    code.focus();
                    return false;
                }
                var params = {
                    code :code.val()
                };
                $.post("{{route('checkGoogle2fa')}}", params, function (rev) {
                    if(rev.status === 0) {
                        layer.msg(rev.msg, {icon: 5, time: 2000}, function () {
                            window.location.reload();
                        });//一个笑脸
                    } else {
                        layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                    }
                }, 'json');
            });
            $("#relieve").click(function () {
                layer.confirm('真的要解绑吗？', {}, function (index) {
                    $.getJSON("{{route('relieveGoogle2fa')}}", function (rev) {
                        if(rev.status === 0) {
                            layer.msg(rev.msg, {icon: 5, time: 2000}, function () {
                                window.location.reload();
                            });//一个笑脸
                        } else {
                            layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                        }
                    });
                });
            });
        })
    </script>
@endsection