<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\BlogTags;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class CommonController extends Controller
{
    public function clear()
    {
        $type = $this->request->input('type', '');
        if ($type == '') {
            mylog(1, 1, true);
        } else if ($type == 'all') {
            mylog(1, 1, 'all');
        }
        echo 'clear ok';
    }

    public function bcrypt(\Request $request)
    {

    }

    public function updateTagFirstId()
    {
        $data = Blogs::pluck('id');
        if ($data) {
            foreach ($data as $id) {
                $first_id = BlogTags::getFirstId($id);
                $bool = Blogs::where('id', $id)->update(['first_tag_id' => $first_id]);
                if ($bool) {
                    echo '成功更新,first_id:' . $first_id . '<br/>';
                } else {
                    echo '失败,blog_id:' . $id . '<br/>';
                }
            }
        }
        dump($data);
    }

    public function test()
    {
        Cache::remember('blog', 10, function () {
            return str_random(120);
        });
    }

    public function test1()
    {
        dump(Cache::pull('blog'));
    }

    public function test2()
    {
        echo Cookie::get('abc');
//        Cookie::queue(Cookie::forget('abc'));
        $cookie = Cookie::forget('abc');
        dump($cookie);
        dump($this->request->cookie());
    }
}
