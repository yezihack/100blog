<ul class="form ratio100">
    <li><span>用户名称</span>
        <div><i class="flaticon-user151"></i><input  id="name" type="text" autocomplete="false"></div>
    </li>
    @if($loginLevel == 1)
        <li><span>安全密码</span>
            <div><i class="flaticon-locked44"></i><input id="pass" type="password" autocomplete="false">
            </div>
        </li>
    @endif
    @if($isCaptcha > 0)
        <li class="box50"><span>验证码</span>
            <div class="box50"><i class="flaticon-letter52"></i>
                <input class="captch" PLACEHOLDER="不区分大小写" type="text" id="code">
                <img src="{{captcha_src()}}" id="captcha" class="cursor img_radius"
                     onclick="this.src='{{captcha_src()}}?'+Math.random()">
            </div>
        </li>
    @endif
    <li>
        <div><label class="line"><input  id="forget" type="checkbox">记住我</label>
        </div>
    </li>
    <li class="tc">
        <button type="button" class="btn" id="login">安全登陆</button>
    </li>
</ul>