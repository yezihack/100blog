<?php

namespace App\Http\Controllers;


use App\Models\Blogs;
use App\Models\Users;

class OwnController extends Controller
{
    /**
     * 显示 修改密码
     * @return $this|Response|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pass()
    {
        if ($this->isPost()) {
            $username = $this->request->input('username', '');
            $pass     = $this->request->input('pass', '');
            $pass2    = $this->request->input('pass2', '');
            if (empty($username)) {
                return $this->setJson(10, '用户名不能为空');
            }
            $isModify = false;
            if ($pass && $pass2) {
                if (empty($pass) || empty($pass2)) {
                    return $this->setJson(11, '密码不能为空');
                }
                if ($pass != $pass2) {
                    return $this->setJson(12, '两次输入密码不正确');
                }
                $isModify = true;
            }

            $user = Users::where('id', '<>', session('user.id'))->where('name', $username)->first();
            if ($user) {
                return $this->setJson(13, '用户名已经存在');
            } else {
                $user = Users::find(session('user.id'));
            }

            if ($isModify) {
                $salt       = getNonce();
                $makePass   = makePass($pass, $salt);
                $user->salt = $salt;
                $user->pass = $makePass;
            }
            $user->name = $username;
            $bool       = $user->save();
            if ($bool) {
                return $this->setJson(0, ' 修改成功');
            } else {
                return $this->setJson(20, '修改失败');
            }
        }
        return view('own.pass');
    }


    public function statistics()
    {
        if ($this->isPost()) {
            $type = $this->request->input('type', 'day');
            $data = Blogs::stat($type);
            return $this->setJson(0, 'ok', $data);
        }
        $title = '数据统计';
        return view('own.statistics', compact('title'));
    }
}
