<div class="header">
    <div class="nav">
        <div class="shell">
            <div data-toggle="sidebar-nav" class="sidebar-nav-btn hide show-s"><i class="flaticon-four92"></i>
            </div>
            <div class="menu drop hide-s">
                <div><a href="{{route('home')}}"><i class="fa fa-home fa-lg fa-fw"></i>首页</a></div>
                <div><a href="{{route('tags')}}"><i class="fa fa-tags fa-lg fa-fw"></i>标签云</a></div>
                <div class="hidei"><a href="{{route('favorite')}}"><i class="fa fa-star fa-lg fa-fw"></i>收藏夹</a></div>
            </div>
            {{--<div class="search">--}}
            {{--<form><input type="text" placeholder="Search"><i class="flaticon-search86"></i></form>--}}
            {{--</div>--}}
            <div class="login">
                @if(session_has('user'))
                    <a href="{{route('blog.edit')}}" class="btn xs mt2"><i class="fa fa-pencil-square-o"></i>
                        <span class="tooltip bottom bg-brown">写博客</span>
                    </a>
                    <a href="{{route('blog.list')}}" class="btn xs mt2"><i class="fa fa-gear fa-spin"></i>
                        <span class="tooltip bottom bg-brown">点击进入系统管理</span>
                    </a>
                    <a href="{{route('own.logout')}}" class="btn xs mt2 bg-green"><i class="fa fa-sign-out"></i>
                        <span class="tooltip bottom bg-green">点击退出系统</span>
                    </a>
                    @if(session_has('user') && config_value('ENABLED_REGISTER') == 1)
                        <a href="{{route('register')}}" class="btn xs mt2"><i class="fa fa-send-o"></i>
                            <span class="tooltip bottom bg-green">点击注册用户</span>
                        </a>
                    @endif
                @else
                    <a href="{{route('login')}}" class="btn xs mt2"><i class="fa fa-sign-in"></i>
                        <span class="tooltip bottom bg-green">点击登入系统</span>
                    </a>
                    @if(session_has('user') && config_value('ENABLED_REGISTER') == 1)
                        <a href="{{route('register')}}" class="btn xs mt2"><i class="fa fa-send-o"></i>
                            <span class="tooltip bottom bg-green">点击注册用户</span>
                        </a>
                    @elseif(config_value('ENABLED_REGISTER') == 2)
                        <a href="{{route('register')}}" class="btn xs mt2"><i class="fa fa-send-o"></i>
                            <span class="tooltip bottom bg-green">点击注册用户</span>
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
<!-- sidebar-nav begin -->
<div id="sidebar-nav" class="sidebar-nav relative">
    <div class="menu accordion">
        <div><a href="{{route('home')}}"><i class="flaticon-home140"></i>轻博客</a></div>
        <div><a href="{{route('tags')}}"><i class="fa fa-tags"></i>标签云</a></div>
        <div><a>热门标签<span class="badge bg-red">Hot</span></a></div>
        <div class="hot-tags" id="hot_id">
            <a v-for="item in list" :href="item.url" :class="item.class" class="btn xs ibi mt3 ml3">@{{item.name}}</a>
        </div>
    </div>
    <i data-close="sidebar-nav" class="flaticon-cross89"></i>
</div>
<!-- sidebar-nav end -->