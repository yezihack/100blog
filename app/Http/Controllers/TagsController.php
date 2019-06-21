<?php

namespace App\Http\Controllers;

use App\Models\ArticleTags;
use App\Models\Blogs;
use App\Models\BlogTags;
use App\Models\Tags;

class TagsController extends Controller
{
    /**
     * 前台显示
     */
    public function view()
    {
        $list = Tags::getListByFirst();
        return view('tag.view', compact('list'));
    }

    public function tag($name = '')
    {
        return view('blog.tag');
    }

    /**
     * 获取热门标签
     * @return $this|Response
     */
    public function hotTags()
    {
        $list = Blogs::getHotList(true);
        return $this->setJson(0, 'ok', $list);
    }

    /**
     * 编辑
     * @return $this|Response
     */
    public function edit()
    {
        $id   = $this->request->input('id', 0);
        $item = Tags::find($id);
        if ($this->isPost()) {
            if ($id <= 0) {
                return $this->setJson(405);
            }
            if (empty($item)) {
                return $this->setJson(100, '标签不存在');
            }
            $name   = $this->request->input('name', '');
            $remark = $this->request->input('remark', '');
            if (empty($name)) {
                return $this->setJson(101, '标签名称不能为空哦');
            }
            $item->name = $name;
            if ($remark != '')
                $item->remark = $remark;
            $bool = $item->save();
            if ($bool) {
                return $this->setJson(0, '保存成功', route('tags.list'));
            }
            return $this->setJson(400);
        }
        return view('tag.edit')->with('tag', $item);
    }

    /**
     * 删除
     * @param $id
     * @return $this|Response
     * @throws \Exception
     */
    public function del($id)
    {
        if ($id <= 0) {
            return $this->setJson(405);
        }
        $count = BlogTags::where('tag_id', $id)->count();
        if ($count > 0) {
            return $this->setJson(100, '目前标签正在使用,不能删除');
        }
        $bool = Tags::where('id', $id)->delete();
        if ($bool) {
            return $this->setJson(0, '删除成功');
        }
        return $this->setJson(400);
    }

    /**
     * 列表
     * @return $this|Response|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists()
    {
        if ($this->isGet()) {
            $tags = Tags::getList();
            return view('tag.list', compact('tags'));
        }
        $page      = $this->request->input('page', 1);
        $page_size = config('blog.page_size.tag');
        $offset    = $page_size * ($page - 1);
        $data      = Tags::offset($offset)
            ->limit($page_size)
            ->select('id', 'name', 'created_at')
            ->orderBy('updated_at', 'desc')
            ->get();
        if ($data) {
            $i = 1;
            foreach ($data as $item) {
                $item->sid         = $i;
                $item->format_date = $item->created_at->diffForHumans();
                $item->use_count   = BlogTags::where('tag_id', $item->id)->count();
                $i++;
            }
        }
        $total = Tags::count();
        return $this->setJson(0, 'ok', [
            'page'      => $page,
            'pages'     => ceil($total / $page_size),
            'page_size' => $page_size,
            'total'     => $total,
            'list'      => $data,
        ]);
    }

}
