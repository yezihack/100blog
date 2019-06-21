@extends('managerLayout')
@section('title', '常量设置')
@section('style')
@stop
@section('body')
    <blockquote>
        <div class="fl"><h1 class="fs4 c-brown">设置常量</h1></div>
        <div class="fr">
            <a href="{{route('config.add')}}" class="btn xs">添加常量</a>
            <button id="config-flush" class="btn xs bg-lime">更新缓存</button>
        </div>
    </blockquote>
    <table class="table hover" id="list">
        <thead>
        <tr>
            <th>序列</th>
            <th>名称</th>
            <th>键</th>
            <th class="hide-s">更新时间</th>
            <th class="hide-s">创建时间</th>
            <th class="tc">操作中心</th>
        </tr>
        </thead>
        <tbody v-for="item in list">
        <tr v-bind:id="'tr-'+item.id">
            <td>@{{item.sid}}</td>
            <td>@{{ item.name }}</td>
            <td>@{{item.key}}</td>
            <td class="hide-s">@{{item.format_date}}</td>
            <td class="hide-s">@{{item.format_created_date}}</td>
            <td class="tc">
                <div class="btn-group">
                    <button class="btn xs bg-green icon" @click="edit(item)"><i
                                class="flaticon-settings46"></i></button>
                    <button class="btn xs bg-red icon" @click="del(item, this)"><i
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
        $(function () {
            get_list(1);
        });
        var vm = new Vue({
            el: '#list',
            data: {
                list: []
            },
            methods: {
                edit: function (item) {
                    window.location.href = "{{route('config.edit')}}?id=" + item.id;
                },
                del: function (item, obj) {
                    layer.confirm('您确定要删除吗?', {btn: ['确定', '取消']}, function (e) {
                        $.post("{{route('config.del')}}", {id: item.id}, function (rev) {
                            if (rev.status === 0) {
                                $("#tr-" + item.id).remove();
                            } else {
                                layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                            }
                        }, 'json');
                        layer.close(e);
                    })
                }
            }
        });
        $("#config-flush").click(function () {
            $.getJSON("{{route('config.flush')}}", function (rev) {
                layer.msg(rev.msg);
            });
        })

        function get_list(page) {
            $.post("{{route('config.list')}}", {page: page}, function (rev) {
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
                            get_list(pageNumber);
                        }
                    });
                } else {
                    layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                }
            }, 'json');
        }
    </script>
@endsection