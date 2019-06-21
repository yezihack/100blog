<?php

use Illuminate\Database\Seeder;
use \App\Models\Configs;

class ConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configs::truncate();
        $at = time();
        $js = <<<js
<script>
(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https') {
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
    }
    else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();
</script>
js;

        $list = [
            [
                'id' => 1,
                'name' => '站点名称',
                'key' => 'SITE_NAME',
                'value' => '时光轻博客',
                'config_type' => 'system',
                'remark' => '站点名称',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 2,
                'name' => '启用登录验证码',
                'key' => 'ENABLED_CAPTCHA',
                'value' => '1',
                'config_type' => 'system',
                'remark' => '是否启用登录验证码：0 否/1 是',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 3,
                'name' => '邮件有效期',
                'key' => 'MAIL_TOKEN_TIME',
                'value' => '3600',
                'config_type' => 'system',
                'remark' => '找回密码邮件有效期,单位为秒',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 4,
                'name' => '是否启用注册',
                'key' => 'ENABLED_REGISTER',
                'value' => '1',
                'config_type' => 'system',
                'remark' => '是否启用注册：0 否/1 是站内注册/2 为站外注册',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 5,
                'name' => '站点开启状态',
                'key' => 'SITE_STATE',
                'value' => '1',
                'config_type' => 'system',
                'remark' => '站点开启状态：0 关闭/1 正常运行/2 系统升级中',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 6,
                'name' => '统计代码',
                'key' => 'STAT_CODE',
                'value' => '',
                'config_type' => 'system',
                'remark' => '统计代码,如CNZZ，百度，腾讯等',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 7,
                'name' => '百度主动推送代码',
                'key' => 'BAIDU_PUSH_CODE',
                'value' => '',
                'config_type' => 'system',
                'remark' => '百度：https://ziyuan.baidu.com/linksubmit/index， 链接提交，选择主动推送（实时），填入推送接口地址，注意数据类型： 推送代码',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 8,
                'name' => '百度主动更新代码',
                'key' => 'BAIDU_UPDATE_CODE',
                'value' => '',
                'config_type' => 'system',
                'remark' => '百度：https://ziyuan.baidu.com/linksubmit/index， 链接提交，选择主动推送（实时），填入推送接口地址，注意数据类型： 更新代码',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 9,
                'name' => '百度主动删除代码',
                'key' => 'BAIDU_DELETE_CODE',
                'value' => '',
                'config_type' => 'system',
                'remark' => '百度：https://ziyuan.baidu.com/linksubmit/index， 链接提交，选择主动推送（实时），填入推送接口地址，注意数据类型： 删除代码',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 10,
                'name' => '百度自动推送代码',
                'key' => 'BAIDU_AUTO_PUSH_CODE',
                'value' => $js,
                'config_type' => 'system',
                'remark' => '百度：https://ziyuan.baidu.com/linksubmit/index， 链接提交，选择自动推送，填入JS代码片段',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 11,
                'name' => '登陆级别设置',
                'key' => 'LOGIN_LEVEL',
                'value' => '1',
                'config_type' => 'system',
                'remark' => '1普通登陆，需要输入帐号，密码。
2快捷登陆，只需输入帐号和谷歌动态码（前提必须绑定谷歌验证）。
3光速登陆，仅需输入谷歌验证动态码（前提必须绑定谷歌验证）
--- 设置方法：系统设置-> 两步验证 -> 添加密钥',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 12,
                'name' => '安全锁次数',
                'key' => 'LOGIC_LOCK_NUMBER',
                'value' => '5',
                'config_type' => 'system',
                'remark' => '设置登陆安全锁的错误次数，超出则锁住登陆，0表示不开启，大于1则表示次数。只能是数字',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 13,
                'name' => '安全锁时间',
                'key' => 'LOGIN_LOCK_HOUR',
                'value' => '1',
                'config_type' => 'system',
                'remark' => '设置安全锁时间，单位：小时。',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 14,
                'name' => 'SEO关键词',
                'key' => 'KEYWORDS',
                'value' => '',
                'config_type' => 'system',
                'remark' => 'keywords一般不超过3个，每个关键词不宜过长，而且词语间要用英文“,”隔开',
                'created_at' => $at,
                'updated_at' => $at,
            ],
            [
                'id' => 14,
                'name' => 'SEO内容摘要',
                'key' => 'DESCRIPTION',
                'value' => '',
                'config_type' => 'system',
                'remark' => '内容摘要',
                'created_at' => $at,
                'updated_at' => $at,
            ],
        ];
        Configs::insert($list);
    }
}
