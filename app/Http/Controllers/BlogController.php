<?php

namespace App\Http\Controllers;


use App\Models\Blogs;
use App\Models\BlogTags;
use App\Models\Tags;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    /**
     * 获取文章列表
     * @return $this|Response
     */
    public function lists()
    {
        if ($this->isGet()) {
            $status_list = Blogs::$config['status'];
            return view('blog.list', compact('status_list'));
        }
        $page     = $this->request->input('page', 1);
        $keywords = $this->request->input('keywords');
        $status   = $this->request->input('status');

        $page_size = config('blog.page_size.blog');
        $offset    = $page_size * ($page - 1);
        $whereRaw  = '1';
        if ($keywords != '') {
            $whereRaw .= " and (instr(title,'{$keywords}') or instr(content, '{$keywords}'))";
        }
        if (intval($status) > 0) {
            $whereRaw .= ' and status = ' . $status;
        }
        $data = Blogs::offset($offset)
            ->limit($page_size)
            ->whereRaw($whereRaw)
            ->select('id', 'title', 'read_count', 'created_at', 'status', 'type', 'first_tag_id')
            ->orderBy('updated_at', 'desc')
            ->get();
        if ($data) {
            $i = 1;
            foreach ($data as $item) {
                $item->sid = $i;
                $i++;
                $item->format_date   = $item->created_at->diffForHumans();
                $item->format_status = Blogs::$config['status'][$item->status];
                $item->first_tag     = Tags::getName($item->first_tag_id);
            }
        }
        $total = Blogs::whereRaw($whereRaw)->count();
        return $this->setJson(0, 'ok', [
            'page'      => $page,
            'pages'     => ceil($total / $page_size),
            'page_size' => $page_size,
            'total'     => $total,
            'list'      => $data,
        ]);
    }

    /**
     * rss
     * @return mixed
     */
    public function rss()
    {
        $view = Cache::remember('rss-feed', config('blog.cache.rss'), function () {
            $posts = Blogs::where('status', 1)
                ->orderBy('updated_at', 'desc')
                ->take(config('blog.rss_size'))
                ->get();
            foreach ($posts as $item) {
                $item->category = BlogTags::getTags($item->id, false);
            }
            return view('services.feed', compact('posts'))->render();
        });
        return response($view)->header('Content-Type', 'text/xml');
    }

    /**
     * xmlmap
     * @return mixed
     */
    public function siteMap()
    {
        $view = Cache::remember('site-map', config('blog.cache.sitemap'), function () {
            $posts = Blogs::where('status', 1)
                ->orderBy('updated_at', 'desc')
                ->take(config('blog.sitemap_size'))
                ->get();
            return view('services.sitemap', compact('posts'))->render();
        });
        return response($view)->header('Content-Type', 'text/xml');
    }

    /**
     * 点赞
     * @return $this|Response
     */
    public function star()
    {
        $id = $this->request->input('id', 0);
        if ($id > 0) {
            $blog = Blogs::find($id);
            $blog->star_count++;
            $bool = $blog->save();
            if ($bool) {
                return $this->setJson(0, '点赞成功', $blog->star_count);
            }
        }
        return $this->setJson(500);
    }

    /**
     * 推荐喜欢的
     * @return $this|Response
     */
    public function like()
    {
        $id   = $this->request->input('id', 0);
        $list = Blogs::getLikeList($id);
        if ($list) {
            return $this->setJson(0, 'ok', $list);
        }
        return $this->setJson(500);
    }

    /**
     * 上传图片
     */
    public function uploadImage()
    {
        $allowExt = ["jpg", "jpeg", "gif", "png", "bmp", "webp"];
        $dirPath  = public_path('uploads/' . date('Ym'));
        //如果上传的是图片
        if (isset($_FILES['editormd-image-file'])) {
            //如果没有开启图片上传
            if (!env('UPLOAD_IMAGE_ENABLE', '0')) {
                $data['success'] = 0;
                $data['message'] = '没有开启图片上传功能';
                return $this->response->json($data);
            }
            $file     = $this->request->file('editormd-image-file');
            $allowExt = explode('|', env('UPLOAD_IMAGE_EXT', 'jpg|jpeg|gif|png'));
        } elseif (isset($_FILES['editormd-file-file'])) {
            //如果没有开启文件上传
            if (!env('UPLOAD_FILE_ENABLE', '0')) {
                $data['success'] = 0;
                $data['message'] = '没有开启文件上传功能';
                return $this->response->json($data);
            }
            $file     = $this->request->file('editormd-file-file');
            $allowExt = explode('|', env('UPLOAD_FILE_EXT', 'txt|doc|docx|xls|xlsx|ppt|pptx|pdf|7z|rar'));
        }
        //如果目标目录不能创建
        if (!is_dir($dirPath) && !mkdir($dirPath)) {
            $data['success'] = 0;
            $data['message'] = '上传目录没有创建文件夹权限';
            return $this->response->json($data);
        }
        //如果目标目录没有写入权限
        if (is_dir($dirPath) && !is_writable($dirPath)) {
            $data['success'] = 0;
            $data['message'] = '上传目录没有写入权限';
            return $this->response->json($data);
        }
        //校验文件
        if (isset($file) && $file->isValid()) {
            $ext = $file->getClientOriginalExtension(); //上传文件的后缀
            //判断是否是图片
            if (empty($ext) or in_array(strtolower($ext), $allowExt) === false) {
                $data['success'] = 0;
                $data['message'] = '不允许的文件类型';
                return $this->response->json($data);
            }
            //生成文件名
            $fileName = uniqid() . '_' . dechex(microtime(true)) . '.' . $ext;
            try {

                $path    = $file->move('uploads/' . date('Ym'), $fileName);
                $webPath = '/' . $path->getPath() . '/' . $fileName;

                $data['success'] = 1;
                $data['message'] = 'ok';
                $data['alt']     = $file->getClientOriginalName();
                $data['url']     = url($webPath);

                if (isset($_FILES['editormd-file-file'])) {
                    $data['icon'] = resolve_attachicons($ext);
                }
                return $this->response->json($data);

            } catch (Exception $ex) {
                $data['success'] = 0;
                $data['message'] = $ex->getMessage();

                return $this->response->json($data);
            }

        }
        $data['success'] = 0;
        $data['message'] = '文件校验失败';

        return $this->response->json($data);
    }

    /**
     * 粘贴上传图片
     */
    public function pasteImage()
    {
        $path    = 'uploads/' . date('Ym') . '/';
        $dirPath = public_path($path);
        //如果目标目录不能创建
        if (!is_dir($dirPath) && !mkdir($dirPath, 0777, true)) {
            return $this->setJson(1, '上传目录没有创建文件夹权限');
        }
        //如果目标目录没有写入权限
        if (is_dir($dirPath) && !is_writable($dirPath)) {
            return $this->setJson(1, '上传目录没有写入权限');
        }
        //如果没有开启图片上传
        if (!env('UPLOAD_IMAGE_ENABLE', '0')) {
            return $this->setJson(1, '没有开启图片上传功能');
        }
        $allowExt = explode('|', env('UPLOAD_IMAGE_EXT', 'jpg|jpeg|gif|png'));
        //粘贴上传图片
        if (isset($_POST['base'])) {
            if (!preg_match('/^(data:image\/(\w+);base64,(.*))/', $_POST['base'], $result)) {
                return $this->setJson(1, '解析失败');
            }
            $extension = $result[2];
            if (!in_array($extension, $allowExt)) {
                return $this->setJson(1, '上传的格式不支持');
            }
            $base_url = $result[3];
            //生成文件名
            $fileName = uniqid() . '_' . dechex(microtime(true)) . '.' . $extension;
            $url      = asset($path . $fileName);
            $bool     = file_put_contents($dirPath . $fileName, base64_decode($base_url));//返回的是字节数
            if ($bool) {
                return $this->setJson(0, '上传成功', ['title' => $fileName, 'path' => $url]);
            }
        }
        return $this->setJson(1, '异常请求');
    }

    /**
     * 新增或编辑
     * @return $this|Response
     * @throws \Exception
     */
    public function save()
    {
        $blog_id   = $this->request->input('id', 0);
        $title     = $this->request->input('title', '');
        $tags      = $this->request->input('tags', '');
        $first_tag = $this->request->input('first_tag', '');
        $type      = $this->request->input('type', 1);
        $status    = $this->request->input('status', 0);
        $content   = $this->request->input('content', '');

        if (empty($title)) {
            return $this->setJson(1000, '标题不能为空');
        }
        if (mb_strlen($title, 'utf-8') < 3) {
            return $this->setJson(1001, '标题不能少于3个字');
        }
        if (empty($tags)) {
            return $this->setJson(1002, '标签不能为空');
        }
        if (empty($content)) {
            return $this->setJson(1003, '内容不能为空');
        }
        if (!in_array($type, array_keys(Blogs::$config['type']))) {//类型分原创还是转载
            return $this->setJson(1004, '类型ID非法');
        }
        if (!in_array($status, array_keys(Blogs::$config['status']))) {
            return $this->setJson(1005, '状态ID非法');
        }
        //处理首标签
        if ($first_tag != '') {
            $tags = $first_tag . ',' . $tags;
        }
        //编辑
        if ($blog_id > 0) {
            $item = Blogs::find($blog_id);
            if (empty($item)) {
                return $this->setJson(100, '资源不存在');
            }
            if ($item->user_id != session('user.id')) {
                return $this->setJson(101, '权限不足');
            }
            $is_new = false;
            if($item->status == 2) {
                $is_new = true;
            }
            $item->title   = $title;
            $item->content = $content;
            $item->type    = $type;
            $item->status  = $status;
            $bool          = $item->save();
            if ($bool) {
                BlogTags::del($blog_id);
                $tag_ids = Tags::getIds($tags);
                if ($tag_ids) {
                    BlogTags::addData($blog_id, $tag_ids);
                    $item->first_tag_id = Tags::getId(current(explode(',', $tags)));
                    $item->save();
                }
                if ($status == 1) {//发布
                    //进行百度推送数据
                    $urls = [
                        get_host() . '/view/' . $blog_id,
                    ];
                    if($is_new) {
                       $result = Blogs::BaiduPush($urls);
                    } else {
                       $result = Blogs::BaiduUpdate($urls);
                    }
                    sglogs($result, 'result');
                    if (isset($result['status']) && $result['status'] == 0) {
                        $item->is_push = 1;
                        $item->save();
                    }
                    return $this->setJson(0, '发布成功' . $result['msg'], route('blog.list'));
                } else {
                    return $this->setJson(0, '草稿存储成功', $blog_id);
                }
            }
        } else {//新增
            $data = [
                'user_id' => session('user.id'),
                'title'   => $title,
                'type'    => $type,
                'status'  => $status,
                'content' => $content,
            ];
            $item = Blogs::create($data);
            if ($item) {
                $blog_id = $item->id;
                $tag_ids = Tags::getIds($tags);
                if ($tag_ids) {
                    BlogTags::addData($blog_id, $tag_ids);
                    $item->first_tag_id = Tags::getId(current(explode(',', $tags)));
                    $item->save();
                }
                if ($status == 1) {//发布
                    //进行百度推送数据
                    $urls = [
                        get_host() . '/view/' . $blog_id,
                    ];
                    $result = Blogs::BaiduPush($urls);
                    sglogs($result, 'push');
                    $item->is_push = 1;
                    $item->save();
                    return $this->setJson(0, '发布成功' . $result['msg'], route('blog.list'));
                } else {
                    return $this->setJson(0, '草稿存储成功', $blog_id);
                }
            }
        }
        return $this->setJson(400);
    }

    /**
     * 更新状态
     * @return $this|Response
     */
    public function changeStatus()
    {
        $id     = $this->request->input('id', 0);
        $status = $this->request->input('status', 0);
        if (!in_array($status, array_keys(Blogs::$config['status']))) {
            return $this->setJson(10, '状态ID非法');
        }
        $blog = Blogs::find($id);
        if (empty($blog)) {
            return $this->setJson(500);
        }
        $blog->status = $status;
        $bool         = $blog->save();
        if ($bool) {
            return $this->setJson(0, '更新成功');
        } else {
            return $this->setJson(11, '更新失败');
        }
    }


    /**
     * 编辑或新增
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = 0)
    {
        $tags = Tags::getList();
        natcasesort($tags);
        return view('blog.edit', ['id' => $id, 'tags' => $tags]);
    }

    /**
     * 获取文章内容
     * @param $id
     * @return $this|Response
     */
    public function get($id)
    {
        if ($id > 0) {
            $item = Blogs::find($id);
            if ($item) {
                $tag_string      = BlogTags::getTags($id);
                $item->first_tag = Tags::getName($item->first_tag_id);
                $tag_list = explode(',', str_replace($item->first_tag, '', $tag_string));
                $item->tags      = $item->first_tag . ',' . join(',', array_filter($tag_list));
                return $this->setJson(0, 'ok', $item);
            }
        }
        return $this->setJson(10, '加载失败');
    }

    /**
     * 删除文章
     * @return $this|Response
     * @throws \Exception
     */
    public function del()
    {
        $id = $this->request->input('id', 0);
        if ($id <= 0) {
            return $this->setJson(404);
        }
        $item = Blogs::find($id);
        if ($item) {
            BlogTags::where('blog_id', $id)->delete();
            $bool = $item->delete();
            if ($bool) {
                $urls = [get_host() . '/view/' . $id];
                $result = Blogs::BaiduDelete($urls);
                sglogs($result, 'baidu_del');
                return $this->setJson(0, '删除成功' . $result['msg']);
            }

        }
        return $this->setJson(400);
    }

    /**
     * 推送全部
     * @return bool|mixed
     */
    public function pushAll() {
        $result = Blogs::PushAll();
        return $result;
    }
}
