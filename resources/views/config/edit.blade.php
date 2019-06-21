@extends('managerLayout')
@section('title', $title)
@section('style')
@stop

@section('body')
    <div class="panel">
        <div class="title">{{$title}}</div>
        <div class="content">
            <ul class="form ratio100 ratio-s100 tl">
                <li><span>名称</span><i class="c-red">*</i><span class="c-999 o4">(不能超50个字符)</span>
                    <div><input type="text" name="name" id="name" value="{{$config->name or ''}}" placeholder="名称" @if(isset($config) && $config->id > 0) disabled @endif></div>
                </li>
                @if(isset($config) && $config->id > 0)
                    <li><span>类型</span>
                        <div><input type="text" value="{{$config->config_type}}" disabled></div>
                    </li>
                @endif
                <li><span>键名</span><i class="c-red">*</i><span class="c-999 o4">(必须以英文字母开头且只能包含英文字母数字和下划线)</span>
                    <div><input type="text" name="key" id="key" value="{{$config->key or ''}}" placeholder="键名" @if(isset($config) && $config->id > 0) disabled @endif></div>
                </li>
                <li><span>键值</span><i class="c-red">*</i><span class="c-999 o4">(键值不能超过200个字符)</span>
                    <div>
                        <textarea placeholder="键值" id="value">{{$config->value or ''}}</textarea>
                    </div>
                </li>
                <li><span>备注</span><span class="c-999 o4">(字符不能超65535个字符)</span>
                    <div><textarea placeholder="备注" id="remark">{{$config->remark or ''}}</textarea></div>
                </li>
                <li class="box100 tc">
                    <button type="button" id="ok" class="btn">确认</button>
                    <input type="hidden" value="{{$config->id or 0}}" id="id">
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $("#ok").click(function () {
                var _id = $("#id");
                var _name = $("#name");
                var _key = $("#key");
                var _value = $("#value");
                var _remark = $("#remark");
                if ($.trim(_name.val()) === '') {
                    layer.tips("请输入用户名称", "#name", {tips: 1});
                    _name.focus();
                    return false;
                }
                if ($.trim(_key.val()) === '') {
                    layer.tips("请输入确认密码", "#key", {tips: 1});
                    _key.focus();
                    return false;
                }
                if ($.trim(_value.val()) === '') {
                    layer.tips("请输入确认密码", "#value", {tips: 1});
                    _value.focus();
                    return false;
                }
                var load = layer.load(2, {shade: false}); //0代表加载的风格，支持0-2
                var params = {
                    id:_id.val(),
                    name: _name.val(),
                    key: _key.val(),
                    value: _value.val(),
                    remark: _remark.val()
                }
                $.post("{{route('config.edit')}}", params).done(function (rev) {
                    layer.close(load);
                    if (rev.status === 0) {
                        layer.msg(rev.msg, {icon: 6, time: 1200}, function () {
                            window.location.href = rev.data;
                        });//一个笑脸
                    } else {
                        layer.msg(rev.msg, {icon: 5, time: 1100, shift: 6});//一个哭脸
                    }
                });
            });
        })
    </script>
@endsection