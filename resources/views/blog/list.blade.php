@extends('managerLayout')
@section('title', '博客列表')
@section('style')
@stop
@section('body')
    <form onsubmit="return false;" id="search_form">
        <ul class="form">
            <li>
                <div><input type="search" name="keywords" placeholder="搜索内容"></div>
            </li>
            <li>
                <div>
                    <select name="status">
                        <option value="">选择状态</option>
                        @foreach($status_list as $sid => $sval)
                            <option value="{{$sid}}">{{$sval}}</option>
                        @endforeach
                    </select>
                </div>
            </li>
            <li>
                <button type="button" id="search" class="btn xs">搜索</button>
                <button type="button" id="push" class="btn xs bg-green">百度推送</button>
            </li>
        </ul>
    </form>
    <table class="table hover" id="list">
        <thead>
        <tr>
            <th>序列</th>
            <th>标题</th>
            <th>状态</th>
            <th class="hide-s">首标签<span class="tooltip top">首标签用于网站首页,突出显示</span>
            </th>
            <th class="hide-s">创建时间</th>
            <th class="hide-s">阅读次数</th>
            <th class="tc">操作中心</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in list" :id="'tr-'+item.id">
            <td>@{{item.sid}}</td>
            <td>@{{ item.title }}</td>
            <td><i class="fa fa-lg toggle" :class="item.status==1?'fa-toggle-on':'fa-toggle-off'"></i>
            </td>
            <td class="hide-s">@{{ item.first_tag }}</td>
            <td class="hide-s">@{{ item.format_date }}</td>
            <td class="hide-s">@{{ item.read_count }}</td>
            <td class="tc">
                <div class="btn-group">
                    <button title="点击浏览" class="btn xs icon" @click="view(item)"><i class="flaticon-upper11"></i>
                        <button title="点击发布" :class="item.status == 1 ? 'hidei':''" class="btn xs bg-orange icon hide-s"
                                @click="publish(item, this)"><i class="fa fa-bullhorn"></i>
                        </button>
                        <button title="点击编辑" class="btn xs bg-green icon" @click="edit(item)"><i
                                    class="flaticon-settings46"></i></button>
                        <button title="点击删除" class="btn xs bg-red icon hide-s" @click="del(item, this)"><i
                                    class="flaticon-black393"></i></button>
                </div>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6" class="tc pt4" id="page">
            </td>
        </tr>
        </tfoot>
    </table>
@endsection
@section('script')
    <script src="{{asset('static/js/jquery.simplePagination.js')}}?v=1230"></script>
    <script>
        var vm = new Vue({
            el: '#list',
            data: {
                list: []
            },
            methods: {
                view: function (item) {
                    var href = "{{route('blog.view')}}/" + item.id;
                    window.open(href);
                },
                edit: function (item) {
                    window.location.href = "{{route('blog.edit')}}/" + item.id;
                },
                del: function (item, obj) {
                    layer.confirm('您确定要删除吗?', {btn: ['确定', '取消']}, function (e) {
                        $.post("{{route('blog.del')}}", {id: item.id}, function (rev) {
                            if (rev.status === 0) {
                                $("#tr-" + item.id).remove();
                            } else {
                                layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                            }
                        }, 'json');
                        layer.close(e);
                    })
                }
                , publish: function (item, obj) {//发布
                    $.post("{{route('blog.changeStatus')}}", {id: item.id, status: 1}, function (rev) {
                        if (rev.status === 0) {
                            $("#tr-" + item.id).find('td:eq(2) i').addClass('fa-toggle-on').removeClass("fa-toggle-off");
                        }
                    });
                }
                , load_list: function (page, params) {
                    if (typeof(params) === 'undefined') {
                        var params = {};
                    }
                    params['page'] = page;
                    $.post("{{route('blog.list')}}", params, function (rev) {
                        if (rev.status === 0) {
                            var data = rev.data;
                            vm.list = data.list;
                            $("#page").pagination({
                                items: data.total,
                                itemsOnPage: data.page_size,
                                currentPage: page,
                                prevText: '上一页',
                                nextText: '下一页',
                                onPageClick: function (pageNumber, event) {
                                    vm.load_list(pageNumber);
                                }
                            });
                        } else {
                            layer.msg(rev.msg);
                        }
                        $("#list").click(function (e) {
                            var cls = e.target.className;
                            var index = cls.indexOf('toggle');
                            if (index > 0) {
                                console.log($(this).html());
                            }
                        });
                    }, 'json');
                }
            }
            ,mounted:function () {
                this.load_list(1);
            }
        });
        $(function () {
            $("#search").click(function () {
                var data = $("#search_form").serializeArray();
                var params = {};
                $.each(data, function (key, item) {
                    params[item.name] = item.value;
                });
                vm.load_list(1, params);
            });
            $("#push").click(function () {
                $.getJSON('{{route('blog.pushAll')}}', function (rev) {
                    layer.msg(rev.msg);
                })
            });
        });
    </script>
@endsection