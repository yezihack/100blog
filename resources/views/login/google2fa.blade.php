<ul class="form ratio100">
    <li><span>动态密钥</span>
        <div><i class="flaticon-locked44"></i><input maxlength="6" id="google_code" type="text" autocomplete="false"></div>
    </li>
    @if($loginLevel == 3 && $isCaptcha > 0)
        <li class="box50"><span>验证码</span>
            <div class="box50"><i class="flaticon-letter52"></i>
                <input class="captch" autocomplete="new-password" PLACEHOLDER="不区分大小写" type="text" id="capCode">
                <img src="{{captcha_src()}}" id="captcha" class="cursor img_radius"
                     onclick="this.src='{{captcha_src()}}?'+Math.random()">
            </div>
        </li>
    @endif
    <li class="tc">
        <button type="button" class="btn bg-green" id="google2fa">准备验证</button>
    </li>
</ul>