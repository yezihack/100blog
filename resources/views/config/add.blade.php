@extends('managerLayout')
@section('title', '添加常量')
@section('style')
@stop

@section('body')
    <div class="panel">
        <div class="title">添加常量</div>
        <div class="content">
            <ul class="form ratio-s100 tl">
                <li class="box100"><span class="box15">用户名称:</span>
                    <div><input type="text" name="username" id="username" value="{{session('user.name')}}"></div>
                </li>
                <li class="box100"><span class="box15">修改密码:</span>
                    <div><input type="password" name="pass" id="pass" placeholder="设置一个复杂点的密码"></div>
                </li>
                <li class="box100"><span class="box15">确认密码:</span>
                    <div><input type="password" name="pass2" id="pass2" placeholder="填写上面一样的密码"></div>
                </li>
                <li class="box100">
                    <button type="button" id="ok" class="btn">确认</button>
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $("#ok").click(function () {
                var _username = $("input[name='username']");
                var _pass = $("input[name='pass']");
                var _pass2 = $("input[name='pass2']");
                if ($.trim(_username.val()) === '') {
                    layer.tips("请输入用户名称", "#username", {tips: 1});
                    _username.focus();
                    return false;
                }
                if ($.trim(_pass.val()) !== '') {
                    if ($.trim(_pass2.val()) === '') {
                        layer.tips("请输入确认密码", "#pass2", {tips: 1});
                        _pass2.focus();
                        return false;
                    }
                    if($.trim(_pass.val()) !== $.trim(_pass2.val())) {
                        layer.tips("两次输入密码不相同", "#pass2", {tips: 1});
                        return false;
                    }
                }
                var params = {
                    username :_username.val(),
                    pass:_pass.val(),
                    pass2:_pass2.val()
                }
                $.post("{{route('own.pass')}}", params).done(function (rev) {
                    if(rev.status === 0) {
                        layer.msg(rev.msg, {icon: 5, time: 2000});//一个笑脸
                    } else {
                        layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                    }
                });
            });
        })
    </script>
@endsection