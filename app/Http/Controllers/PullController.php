<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use QL\QueryList;

class PullController extends Controller
{
    public function getList()
    {
        $page   = $this->request->input('page', 1);
        $url    = 'https://shop.m.jd.com/search/searchWareAjax.json?r=1515121471699';
        $params = [
            'shopId'     => 51269,
            'searchPage' => $page,
            'searchSort' => 0,
            'jdDeliver'  => 0,
        ];
        $json   = curlPost($url, $params);
        if ($json) {
            $data = json_decode($json, true);
            if (isset($data['results'])) {
                $wareInfo = $data['results']['wareInfo'];
                if ($wareInfo) {
                    $insert = [];
                    foreach ($wareInfo as $item) {
                        $url      = sprintf('https://item.m.jd.com/product/%s.html', $item['wareId']);
                        $name     = $item['wname'];
                        $mPrice   = $item['mPrice'];
                        $insert[] = [
                            'name'  => $name,
                            'price' => $mPrice,
                            'url'   => $url,
                        ];
                    }
//                    mydump($insert);
                    $bool = DB::table('goods')->insert($insert);
                    mydump($bool);
                }
            } else {
                echo 'finish';
            }
        }
    }

    public function image()
    {
        $sid    = $this->request->input('sid', 0);
        $result = [];
        $item   = DB::table('goods')->where('id', '>', $sid)->orderBy('id')->limit(1)->first();
        $sid ++;
        mydump($item);
        if (empty($item)) {
            exit('结束了');
        }
        $result['name']  = $item->name;
        $result['price'] = $item->price;
        $result['url']   = $item->url;
        $id              = pathinfo($item->url, PATHINFO_FILENAME);
        $html            = curlPost($item->url);
        $rules           = [
            'src'  => ['.scroll-imgs li img', 'src'],
            'src2' => ['.scroll-imgs li img', 'imgsrc']
        ];
        $hj              = QueryList::Query($item->url, $rules);
        $data            = $hj->getData();
        //橱装图
        $image_list   = [];
        $image_list[] = array_shift($data)['src'];
        foreach ($data as $_temp) {
            $image_list[] = $_temp['src2'];
        }
        $result['board'] = $image_list;
        //详细
        $str       = 'https://item.m.jd.com/ware/detail.json?wareId=' . $id;
        $json      = curlPost($str);
        $json_data = json_decode($json, true);
        if (isset($json_data['wdis'])) {
            $wd_html = $json_data['wdis'];
            $rule    = [
                'src' => ['img', 'src'],
            ];
            $list    = QueryList::Query($wd_html, $rule)->getData();
            if ($list) {
                $image_list2 = [];
                foreach ($list as $_temp2) {
                    $image_list2[] = $_temp2['src'];
                }
                $result['details'] = $image_list2;
            }
        }
        $path = public_path('goods_list2/');
        mydump($result);
        if ($result) {
            //创建目录
            $_path = $path . $sid . '-'. $this->replaceChina($result['name']);
            if (!is_dir($_path) && @mkdir($_path)) {
                $content = <<<eot
商品原地址:{$result['url']} \r\n
价格:{$result['price']} \r\n
商品名称:{$result['name']} \r\n
eot;
                $txt     = $_path . '/readme.txt';
                file_put_contents($txt, $content);
            }
            if (is_dir($_path)) {
                //橱窗图
                $board      = $result['board'];
                $board_path = $_path . '/' . $this->replaceChina('橱窗图');
                if (!is_dir($board_path) && mkdir($board_path)) {
                    foreach ($board as $key => $src) {
                        $basename = pathinfo($src, PATHINFO_BASENAME);
                        $path     = $board_path . '/' . ($key + 1) . '-' . $basename;
                        file_put_contents($path, file_get_contents($src));
                    }
                }
                //详情
                $details      = $result['details'];
                $details_path = $_path . '/' . $this->replaceChina('详情图');
                if (!is_dir($details_path) && mkdir($details_path)) {
                    foreach ($details as $key => $src) {
                        if (stripos($src, 'http') === false) {
                            $src = 'http:' . $src;
                        }
                        $basename = pathinfo($src, PATHINFO_BASENAME);
                        $path     = $details_path . '/' . ($key + 1) . '-' . $basename;
                        file_put_contents($path, file_get_contents($src));
                    }
                }
            }
        }
        $url = 'http://sgblog.cc/image?sid=' . $sid;
        echo $url;
        $script = "<script>window.location.href = '{$url}'</script>";
        echo $script;
    }

    private function replaceChina($name)
    {
        $preg    = ['# #', '#\*#', '#\（#', '#\）#', '#\【#', '#\】#', '#\/#'];
        $replace = ['', '', '', '', '', '', '', ''];
        $name    = preg_replace_array($preg, $replace, $name);
        return iconv("UTF-8", "gbk", $name);
    }
}
