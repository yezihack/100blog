@extends('layout')
@section('body')
<div class="wrapper">
    <!-- header begin -->
    @include('widget.header')
    <!-- header end -->
    <!-- main begin -->
    <div class="main">
        <div class="shell">
            <!-- sidebar begin -->
            @include('widget.rightbar')
            <!-- sidebar end -->
            <!-- container begin -->
            <div class="container fl">
                <div class="mini-list">
                    <div class="summary-li narrow box100">
                        <div class="cp">
                            <div class="votes">
                                <a class="view-tag bg-green uppercase">php</a>
                            </div>
                        </div>
                        <div class="summary">
                            <h3 class="box100">
                                <a class="question-hyperlink" href="#">履瓜田不纳履，李下不正冠。嫂叔不亲授，长幼不比肩履瓜田不纳履，李下不正冠。嫂叔不亲授，长幼不比肩履瓜田不纳履，李下不正冠。嫂叔不亲授，长幼不比肩履瓜田不纳履，李下不正冠。嫂叔不亲授，长幼不比肩履瓜田不纳。</a>
                            </h3>
                            <div class="tags t-python t-tensorflow t-deep-learning t-object-detection">
                                <a class="post-tag">pathon</a>
                                <a class="post-tag">pathon</a>
                                <a class="post-tag">pathon</a>
                                <a class="post-tag">pathon</a>
                            </div>
                            <div class="started">
                                <a href="#" class="started-link"><i class="fa fa-clock-o">1分钟前</i></a>
                                <a href="#" class="started-link"><i class="fa fa-eye">11</i></a>
                                <a href="#" class="started-link"><i class="fa fa-user-circle-o">老月</i></a>
                                <span class="reputation-score"></span>
                            </div>
                        </div>
                    </div>
                    <div class="box50 offset30 hide-s">
                        <ul class="page">
                            <li><a href="#">Prev</a></li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">...</a></li>
                            <li><a href="#">99</a></li>
                            <li><a href="#">Next</a></li>
                        </ul>
                    </div>
                    <div class="box50 offset30 show-s hide">
                        <ul class="page pr3">
                            <li><a href="#">上一页</a></li>
                            <li><a href="#">下一页</a></li>
                        </ul>
                    </div>
                </div>
                <!-- container end -->
            </div>
        </div>
        <!-- main end -->
        <!-- footer end -->
        @include('widget.footer')
        <!-- footer end -->
    </div>
</div>
@endsection