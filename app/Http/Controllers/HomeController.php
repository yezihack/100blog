<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\BlogTags;
use App\Models\Tags;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class HomeController extends Controller
{

    public function index()
    {
        $pages = Blogs::getPaginate();
        list($tags, $news, $views, $stars) = Blogs::getBarList();
        return view('home.index', compact('pages', 'news', 'views', 'tags', 'stars'));
    }

    public function tag($tag_name)
    {
        $tag_id = Tags::getId($tag_name);
        if (intval($tag_id) <= 0) {
            abort(404);
        }
        $blog_ids = BlogTags::getBlogIdsByTagId($tag_id);
        $where    = 'id in (' . implode(',', $blog_ids->toArray()) . ')';
        $pages    = Blogs::getPaginate($where);
        list($tags, $news, $views, $stars) = Blogs::getBarList();
        return view('home.index', compact('pages', 'news', 'views', 'tags', 'tag_name', 'stars'));
    }

    /**
     * 浏览文章
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($id)
    {
        $blog = Blogs::CacheBlog($id);
        if (empty($blog)) {
            abort(404);
        }
        //1个ip阅读次数只+1
        $read_key = config('blog.cache_prefix_key.read_count') . $id .  $this->request->session()->getId();
        if(!Cache::has($read_key)) {
            $blog->read_count++;
            $blog->save();
            Blogs::CacheBlog($id, true);
            Cache::put($read_key, $this->request->getClientIp(), Carbon::tomorrow()->subSecond(1));
        }
        $blog->format_type      = Blogs::$config['type'][$blog->type];
        $blog->format_type_tips = Blogs::$config['format_type'][$blog->type];
        $blog->username         = Users::find($blog->user_id)->value('name');
        $prev                   = Blogs::where('id', '<', $id)->select('id', 'title')->orderByDesc('id')->limit(1)->first();
        $next                   = Blogs::where('id', '>', $id)->select('id', 'title')->orderBy('id')->limit(1)->first();
        $blog->prev_url         = empty($prev) ? false : route('blog.view', ['id' => $prev->id]);
        $blog->prev_title       = empty($prev) ?: $prev->title;
        $blog->next_url         = empty($next) ? false : route('blog.view', ['id' => $next->id]);
        $blog->next_title       = empty($next) ?: $next->title;
        $blog->first_tag        = Tags::getName($blog->first_tag_id);
        list($tags, $news, $views, $stars) = Blogs::getBarList();
        $blog_tags = Blogs::getTags($id);
        $tag_list  = [];
        if ($blog_tags->count() > 0) {
            foreach ($blog_tags as $item) {
                $tag_list[] = $item->name;
            }
        }
        array_push($tag_list, $blog->first_tag);
        $tag_list       = array_unique($tag_list);
        $blog->keywords = join(',', $tag_list);
        $blog->desc     = $blog->title . ',' . Str::substr(replace_editor($blog->content), 0, 200);
        return view('blog.view', compact('blog', 'news', 'views', 'tags', 'blog_tags', 'stars'));
    }

}
