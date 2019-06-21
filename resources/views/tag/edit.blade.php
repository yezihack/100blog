@extends('managerLayout')
@section('title', '参数设置')
@section('style')
@stop

@section('body')
    <div class="panel">
        <div class="title">编辑标签</div>
        <div class="content">
            <form>
                <ul class="form ratio-s100 tl">
                    <li class="box100"><span class="box15">名称:</span>
                        <div><input type="text" name="name" id="name" value="{{$tag->name}}"></div>
                    </li>
                    <li class="box100"><span class="box15">备注:</span>
                        <div><textarea cols="26" name="remark" id="remark">{{$tag->remark}}</textarea></div>
                    </li>
                    <li class="box100" style="margin-left: 8rem">
                        <button type="button" class="btn tc" id="ok">确认</button>
                    </li>
                    <li><input type="hidden" value="{{$tag->id}}" name="id" id="id"></li>
                </ul>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('static/js/jquery.simplePagination.js')}}?v=1230"></script>
    <script>
        $(function () {
            $("#ok").click(function () {
                var name = $("#name").val();
                var remark = $("#remark").val();
                if ($.trim(name) === '') {
                    layer.tips("请输入标签名称", "#name", {tips: 1});
                    $("#name").focus();
                    return false;
                }
                $("#loading").mask("open");
                var params = {
                    id:$("#id").val(),
                    name: name,
                    remark: remark
                }
                $.post("{{route('tags.edit')}}", params, function (rev) {
                    if (rev.status === 0) {
                        window.location.href = rev.data;
                    } else {
                        $("#loading").mask("close");
                        layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                    }
                });
            });
        })
    </script>
@endsection