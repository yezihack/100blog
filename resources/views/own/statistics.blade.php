@extends('managerLayout')
@section('title', $title)
@section('style')
@stop

@section('body')
    <div class="panel">
        <div class="title">{{$title}}</div>
        <div class="content " style="padding: 5px;">
            <div class="tab">
                <ul class="nav">
                    <li class="active">日统计</li>
                    <li>周统计</li>
                    <li>月统计</li>
                    <li>年统计</li>
                    <li>综合统计</li>
                </ul>
                <ul class="content">
                    <li class="active">
                        <div id="day" style="width: 100%;height:500px;"></div>
                    </li>
                    <li>
                        <div id="week" style="width:800px;height:500px;"></div>
                    </li>
                    <li>
                        <div id="month" style="width:800px;height:500px;"></div>
                    </li>
                    <li>
                        <div id="year" style="width:800px;height:500px;"></div>
                    </li>
                    <li>
                        <div id="multiple" style="width:800px;height:500px;"></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('static/plugins/echarts.common.min.js')}}"></script>
    <script>
        $(function () {
            stat('day');
            stat('week');
            stat('month');
            stat('year');
            stat('multiple');
        });

        function stat(type) {
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById(type));
            // 指定图表的配置项和数据
            $.post('{{route('own.statistics')}}', {type: type}, function (rev) {
                if (rev.status === 0) {
                    var option = {};
                    if (type === 'year' || type === 'multiple') {
                        option = getPieOption(rev.data.x, rev.data.y);
                    } else {
                        option = getBarOption(rev.data.x, rev.data.y);
                    }
                    myChart.setOption(option);
                }
            }, 'json');
        }

        // 使用刚指定的配置项和数据显示图表。
        function getBarOption(xList, yList) {
            var option = {
                color: ['#3398DB'],
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: xList,
                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '发表文章数',
                        type: 'bar',
                        barWidth: '60%',
                        data: yList,
                        markPoint: {
                            data: [
                                {type: 'max', name: '最大值'},
                                {type: 'min', name: '最小值'}
                            ]
                        }
                    }
                ]
            };
            return option;
        }

        function getPieOption(xList, yList) {
            var option = {
                title: {
                    text: '文章发布统计',
                    subtext: '来源于:时光脚步数据挖掘',
                    x: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: xList
                },
                series: [
                    {
                        name: '文章发表数',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: yList,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            return option;
        }
    </script>
@endsection