<div class="menu accordion">
    <div><a href="{{route('blog.edit')}}">添加文章</a></div>
    <div @if(checkUrlBaseName('blog')) class="active" @endif><a href="{{route('blog.list')}}">文章列表</a></div>
    <div @if(checkUrlBaseName('tags')) class="active" @endif><a href="{{route('tags.list')}}">标签管理</a></div>
    <div @if(checkUrlBaseName('own|config')) class="active" @endif><a href="#">系统设置</a>
        <ul>
            <li @if(checkUrlBaseName('pass')) class="active" @endif><a href="{{route('own.pass')}}">修改密码</a></li>
            <li  @if(checkUrlBaseName('config')) class="active" @endif><a href="{{route('config.list')}}">常量设置</a></li>
            <li  @if(checkUrlBaseName('statistics')) class="active" @endif><a href="{{route('own.statistics')}}">统计数据</a></li>
            <li  @if(checkUrlBaseName('google2fa')) class="active" @endif><a href="{{route('own.google2fa')}}">两步验证</a></li>
        </ul>
    </div>
</div>