<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\Configs;
use App\Models\Users;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GoogleController extends Controller
{

    public function test() {
        abort(404);
        dd();
        $id = $this->request->session()->getId();
        dump($id);
        dd();

        $code = $this->request->input('code', '565627');
        $g = new Google2FA();
        $token = 'M5CSSHZUUEHS5FCO';
        $bool = $g->verifyKey($token, $code, 1);
        dump($bool);


        $a = $g->getCurrentOtp($token);
        dump($a);
        dump($g->getKeyRegeneration());
    }

    /**
     * google 生成key
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function google2fa() {
        $user = Users::find(session('user.id'));
        $qrInfo = $key = null;
        if($user->google2fa_key == '') {
            $g = new Google2FA();
            $key = $g->generateSecretKey(16);
            session(['google2fa_key'=> $key]);
            $name = session('user.name');
            $qrInfo = $g->getQRCodeUrl(Configs::getSiteName(), $name, $key);
        }
        $title = '两步验证';
        return view('own.google2fa', compact('title', 'qrInfo', 'key'));
    }

    /**
     * google code 验证
     * @return GoogleController|Response
     */
    public function checkGoogle2fa() {
        $g = new Google2FA();
        $token = session('google2fa_key');
        $code = $this->request->input('code');
        if (!$token) {
            return $this->setJson(9, 'token已过期');
        }
        if (empty($code)) {
            return $this->setJson(10, '请输入动态验证码');
        }
        $bool = $g->verifyKey($token, $code, 1);
        if($bool) {
            //todo
            $bool = Users::where('id', session('user.id'))->update(['google2fa_key' => $token]);
            return $bool == true ? $this->setJson(0, '添加成功') : $this->setJson(12, '添加数据失败');
        }
        return $this->setJson(11, '验证失败');
    }

    /**
     * 解绑验证
     * @return GoogleController|Response
     */
    public function relieveGoogle2fa() {
        $user = Users::find(session('user.id'));
        $bool = false;
        if($user) {
            $user->google2fa_key = '';
            $bool = $user->save();
        }
        return $bool == true ? $this->setJson(0, '解绑成功') : $this->setJson(12, '解绑失败');
    }
}
