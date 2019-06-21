<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\Configs;
use App\Models\Users;
use App\User;
use Illuminate\Support\Facades\Cookie;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    public function home()
    {
        return view('home');
    }

    /**
     * 注册
     */
    public function register()
    {
        $isReg = config_value('ENABLED_REGISTER');
        if ($isReg == 1) {//站内注册
            if (!session_has('user')) {
                return errors('只允许站内注册');
            }
        } elseif ($isReg == 0) {
            return errors('未开放注册');
        }
        if ($this->isPost()) {
            $name = $this->request->input('name', '');
            $pass = $this->request->input('pass', '');
            $pass2 = $this->request->input('pass2', '');
            if (empty($name)) {
                return $this->setJson(20, '用户名不能为空');
            }
            if (mb_strlen($name, 'utf-8') < 3) {
                return $this->setJson(21, '用户名不能少于3个字符');
            }
            if (empty($pass) || empty($pass2)) {
                return $this->setJson(22, '密码不能为空');
            }
            if ($pass != $pass2) {
                return $this->setJson(23, '两次输入的密码不一致');
            }
            //判断是否重复
            $check = Users::where('name', $name)->first();
            if (!empty($check)) {
                return $this->setJson(24, '用户名已经存在');
            }
            $salt = getNonce(32);
            $mPass = makePass($pass, $salt);
            $user = new Users();
            $user->name = $name;
            $user->pass = $mPass;
            $user->salt = $salt;
            if ($user->save()) {
                return $this->setJson(0, '注册成功', route('login'));
            }
            return $this->setJson(400, '注册失败');
        }
        return view('common.register');
    }

    /**
     * 登陆
     * @return $this|\App\Http\Controllers\Response|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        $isCaptcha = config_value('ENABLED_CAPTCHA', 1);
        //1普通登陆，需要输入帐号，密码（如果有谷歌验证就验证）。
        //2快捷登陆，只需输入帐号和谷歌动态码（前提必须绑定谷歌验证）。
        //3光速登陆，仅需输入谷歌验证动态码（前提必须绑定谷歌验证）
        $loginLevel = config_value('LOGIN_LEVEL', 1);
        if ($this->isPost()) {
            if (Users::LockLogin($this->request->getClientIp(), true)) {
                return $this->setJson(99, '帐号已被锁，1小时后再试');
            }
            if ($loginLevel == 3) { //3时不应该请求这个
                return $this->setJson(9, '非法操作');
            }
            $name = $this->request->input('name', '');
            $pass = $this->request->input('pass', '');
            $code = $this->request->input('code', '');
            $remember = $this->request->input('remember', '');
            if (empty($name)) {
                return $this->setJson(10, '用户名称不能为空');
            }
            if ($loginLevel == 1) {
                if (empty($pass)) {
                    return $this->setJson(11, '密码不能为空');
                }
                if ($isCaptcha && $code == '') {
                    return $this->setJson(12, '请输入验证码');
                }
            }
            if ($isCaptcha && !captcha_check($code)) {
                if (Users::LockLogin($this->request->getClientIp())) {
                    return $this->setJson(99, '帐号已被锁，1小时后再试');
                }
                return $this->setJson(13, '验证码错误');
            }
            $user = Users::where('name', $name)->first();
            if (empty($user)) {
                return $this->setJson(12, '用户名不存在');
            }
            if ($loginLevel == 1) {
                $mPass = makePass($pass, $user['salt']);
                if ($mPass !== $user['pass']) {
                    if (Users::LockLogin($this->request->getClientIp())) {
                        return $this->setJson(99, '帐号已被锁，1小时后再试');
                    }
                    return $this->setJson(12, '密码输入不正确');
                }
            } elseif ($loginLevel == 2 && $user->google2fa_key == '') {
                return $this->setJson(20, '未设置谷歌两步验证');
            }
            //判断不同的情况
            if ($user->google2fa_key != '') {
                session(['login_user' => $user, 'remember' => $remember]);
                return $this->setJson(0, 'ok', route('google2fa'));
            }
            //1的情况
            $this->saveUser($user, $remember);
            return $this->setJson(0, '登陆成功', route('home'));
        }
        $title = Configs::getSiteName();
        if ($loginLevel == 3) {
            return view('login.google', compact('isCaptcha', 'title', 'loginLevel'));
        }
        return view('login.login', compact('isCaptcha', 'loginLevel', 'title'));
    }

    /**
     * 谷歌验证二步
     *
     * @return LoginController|Response|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function google2fa()
    {
        if ($this->isGet()) {
            $title = Configs::getSiteName();
            return view('login.google', compact('title'));
        }
        if (Users::LockLogin($this->request->getClientIp(), true)) {
            return $this->setJson(99, '帐号已被锁，1小时后再试');
        }
        //1普通登陆，需要输入帐号，密码。
        //2快捷登陆，只需输入帐号和谷歌动态码（前提必须绑定谷歌验证）。
        //3光速登陆，仅需输入谷歌验证动态码（前提必须绑定谷歌验证）
        $loginLevel = config_value('LOGIN_LEVEL', 1);
        $isCaptcha = config_value('ENABLED_CAPTCHA', 1);
        $code = $this->request->input('code');
        if ($code == '') {
            return $this->setJson(40, '请输入动态验证码');
        }
        $user = null;
        $remember = '';
        switch ($loginLevel) {
            case 1:
            case 2:
                $user = session('login_user');
                $remember = session('remember');
                break;
            case 3:
                if($isCaptcha > 0) {
                    $capCode = $this->request->input('capCode');
                    if (!captcha_check($capCode)) {
                        return $this->setJson(13, '验证码错误');
                    }
                }
                $user = Users::orderBy('id')->limit(1)->first();
                break;
            default:
                return $this->setJson(49, '非法操作');
        }
        if (is_null($user)) {
            return $this->setJson(45, '用户不存在');
        }
        if ($user->google2fa_key == '') {
            return $this->setJson(42, '两步验证密钥未设置');
        }
        $bool = Users::CheckGoogle2faOnly($user->google2fa_key, $code);
        if (!$bool) {
            if (Users::LockLogin($this->request->getClientIp())) {
                return $this->setJson(99, '帐号已被锁，1小时后再试');
            }
            return $this->setJson(43, '验证码不正确');
        }
        $this->saveUser($user, $remember);
        return $this->setJson(0, '登陆成功', route('home'));
    }

    /**
     * 保存信息
     * @param Users $user
     * @param $remember
     */
    private function saveUser(Users $user, $remember)
    {
        $user->login_count++;
        $user->login_ip = $this->request->getClientIp();
        if ($remember) {
            $token = getNonce();
            $user->token = $token;
            $bToken = bcrypt($token);
            Cookie::queue(Users::$remember_key, $user->id . '|' . $bToken, config('blog.cache.remember'));
        }
        $user->save();
        session_users($user);
    }

    /**
     * 退出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        $this->request->session()->flush();
        $cookie = Cookie::forget(Users::$remember_key);
        return redirect(route('login'))->withCookie($cookie);
    }
}
