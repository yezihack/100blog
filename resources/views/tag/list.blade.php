@extends('managerLayout')
@section('title', '标签管理')
@section('style')
@stop
@section('body')
    <table class="table hover" id="list">
        <thead>
        <tr>
            <th>序列</th>
            <th>名称</th>
            <th class="hide-s">使用次数</th>
            <th class="hide-s">创建时间</th>
            <th class="tc">操作中心</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in list" v-bind:id="'tr-'+item.id">
            <td>@{{item.sid}}</td>
            <td>@{{ item.name }}</td>
            <td class="hide-s">@{{ item.use_count }}</td>
            <td class="hide-s">@{{ item.format_date }}</td>
            <td class="tc">
                <div class="btn-group">
                    <button class="btn xs icon" @click="view(item)"><i class="flaticon-upper11"></i>
                    </button>
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
@stop
@section('script')
    <script src="{{asset('static/js/jquery.simplePagination.js')}}?v=1230"></script>
    <script>
        var vm = new Vue({
            el: '#list',
            data: {
                list: []
            },
            mounted:function () {
                this.get_list(1);
            },
            methods: {
                view: function (item) {
                    // window.location.href = "/" + item.id;
                },
                edit: function (item) {
                     window.location.href= "{{route('tags.edit')}}?id=" + item.id;
                },
                del: function (item, obj) {
                    layer.confirm('您确定要删除吗?', {btn: ['确定', '取消']}, function (e) {
                        $.post("{{route('tags.del')}}/"+item.id, function (rev) {
                            if (rev.status === 0) {
                                $("#tr-" + item.id).remove();
                            } else {
                                layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                            }
                        }, 'json');
                        layer.close(e);
                    })
                },
                get_list:function (page) {
                    $.post("{{route('tags.list')}}", {page: page}, function (rev) {
                        if (rev.status === 0) {
                            var data = rev.data;
                            console.log(data);
                            vm.list = data.list;
                            $("#page").pagination({
                                items: data.total,
                                itemsOnPage: data.page_size,
                                currentPage: page,
                                prevText: '上一页',
                                nextText: '下一页',
                                onPageClick: function (pageNumber, event) {
                                    this.get_list(pageNumber);
                                }
                            });
                        } else {

                        }
                    });
                }
            }
        });
    </script>
@endsection