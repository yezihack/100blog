<?php

namespace App\Http\Controllers;

use App\Models\Configs;
use App\Models\Users;

class ConfigController extends Controller
{
    /**
     * 配置的列表
     * @return $this|Response|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists()
    {
        $page_size = config('blog.page_size.config');
        if ($this->isGet()) {
            return view('config.list');
        }
        $page = $this->request->input('page', 1);
        $offset = $page_size * ($page - 1);
        $data = Configs::offset($offset)
            ->limit($page_size)
            ->select('id', 'name', 'key', 'updated_at', 'created_at', 'config_type')
            ->orderBy('id')
            ->get();
        if ($data) {
            $i = 1;
            foreach ($data as $item) {
                $item->sid = $i;
                $i++;
                $item->format_date = $item->updated_at->diffForHumans();
                $item->format_created_date = $item->created_at->diffForHumans();
            }
        }
        $total = Configs::count();
        return $this->setJson(0, 'ok', [
            'page' => $page,
            'pages' => ceil($total / $page_size),
            'page_size' => $page_size,
            'total' => $total,
            'list' => $data,
        ]);
    }

    /**
     * 显示
     * @return $this
     */
    public function add()
    {
        return view('config.edit')->with('title', '添加常量');
    }

    /**
     * 添加或编辑
     * @return $this|Response
     */
    public function edit()
    {
        $id = $this->request->input('id', 0);
        if ($this->isGet()) {
            $cfg = Configs::find($id);
            return view('config.edit')->with('title', '修改常量')->with('config', $cfg);
        }
        $name = $this->request->input('name', '');
        $key = $this->request->input('key', '');
        $value = trim($this->request->input('value', ''));
        $remark = $this->request->input('remark', '1');
        if ($name == '') {
            return $this->setJson(10, '名称不能为空');
        }
        if ($key == '') {
            return $this->setJson(11, '键名不能为空');
        }
        if ($value == '') {
            return $this->setJson(12, '键值不能为空');
        }
        $key = strtoupper($key);
        $data = [
            'name' => $name,
            'key' => $key,
            'value' => $value,
            'config_type' => 'user',
            'remark' => $remark,
        ];
        if ($id > 0) {
            $cfg = Configs::find($id);
            if (!$cfg) {
                return $this->setJson(10, '常量不存在');
            }
            if ($key == 'LOGIN_LEVEL') {
                if (!in_array($value, [1, 2, 3]))
                    return $this->setJson(12, '只允许设置1，2，3之间的值');
                $user = Users::find(session('user.id'));
                if ($value == 3 && $user->google2fa_key == '')
                    return $this->setJson(13, '必须先绑定谷歌验证器，否则不允许设置3');
            }

            $bool = Configs::where('id', $id)->update($data);
            if ($bool) {
                Configs::forget($key);
                return $this->setJson(0, '修改成功', route('config.list'));
            }
        } else {
            $bool = Configs::create($data);
            if ($bool) {
                return $this->setJson(0, '添加成功', route('config.list'));
            }
        }
        return $this->setJson(20, '失败');
    }

    /**
     * 更新缓存
     * @return $this|Response
     */
    public function flush()
    {
        return $this->setJson(0, Configs::clearAll());
    }

    /**
     * 删除
     * @return $this|Response
     * @throws \Exception
     */
    public function del()
    {
        $id = $this->request->input('id', 0);
        $cfg = Configs::find($id);
        if (!$cfg) {
            return $this->setJson(10, '常量不存在');
        }
        if ($cfg->config_type == 'system') {
            return $this->setJson(13, '系统自带的常量不允许删除');
        }
        $bool = $cfg->delete();
        if ($bool) {
            return $this->setJson(0, '删除成功');
        } else {
            return $this->setJson(12, '删除失败');
        }
    }


}
