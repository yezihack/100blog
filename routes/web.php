<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//无需登陆的路由
Route::get('/')->uses('HomeController@index')->name('home');//首页
Route::get('tags')->uses('TagsController@view')->name('tags');//标签显示
Route::get('favorites')->uses('FavoriteController@view')->name('favorite');//收藏夹
Route::get('tag/{name}')->uses('HomeController@tag')->name('tag');//标签显示
Route::get('view/{id?}')->uses('HomeController@view')->name('blog.view')->where('id', '[0-9]+');//浏览文章
Route::post('star')->uses('BlogController@star')->name('blog.star');# ajax 点赞请求
Route::post('like')->uses('BlogController@like')->name('blog.like');#ajax 你可能喜欢的文章
Route::post('hotTags')->uses('TagsController@hotTags')->name('tag.hot');#ajax 手机端热门标签加载
Route::get('sitemap.xml')->uses('BlogController@siteMap')->name('sitemap');//sitemap 地图
Route::get('sitemap')->uses('BlogController@siteMap');//sitemap 地图
Route::get('rss')->uses('BlogController@rss')->name('rss');//rss-feed 订阅
Route::get('feed.xml')->uses('BlogController@rss')->name('feed');//rss-feed 订阅
Route::match(['get', 'post'], 'login')->uses('LoginController@login')->name('login');//登陆
Route::match(['get', 'post'], 'register')->uses('LoginController@register')->name('register');//注册
Route::match(['get', 'post'],'google2fa')->uses('LoginController@google2fa')->name('google2fa');//浏览文章
Route::post('view/recommend')->uses('BlogController@recommend')->name('blog.recommend');//浏览文章

//google 兩步驗證
Route::get('test', 'GoogleController@test');

//需要登陆才能操作的路由
Route::group(['middleware' => 'check_login'], function () {
    Route::get('c')->uses('CommonController@clear')->name('clear');//清除
    Route::get('update')->uses('CommonController@updateTagFirstId');
});

//中心管理
Route::group(['middleware' => 'check_login', 'prefix' => 'own'], function () {
    Route::match(['get', 'post'], 'pass')->uses('OwnController@pass')->name('own.pass');//修改密码
    Route::get('config')->uses('OwnController@config')->name('own.config');//设置参数
    Route::post('config_list')->uses('OwnController@config_list')->name('own.config_list');//设置参数
    Route::match(['get', 'post'], 'config_edit')->uses('OwnController@config_edit')->name('own.config_edit');//设置参数
    Route::post('config_del')->uses('OwnController@config_del')->name('own.config_del');//设置参数
    Route::get('logout')->uses('LoginController@logout')->name('own.logout');//退出

    Route::match(['get', 'post'], 'statistics')->uses('OwnController@statistics')->name('own.statistics');//统计
    Route::get('google2fa')->uses('GoogleController@google2fa')->name('own.google2fa');//两步验证
    Route::post('checkGoogle2fa')->uses('GoogleController@checkGoogle2fa')->name('checkGoogle2fa');//验证两步登陆
    Route::get('relieveGoogle2fa')->uses('GoogleController@relieveGoogle2fa')->name('relieveGoogle2fa');//删除两步登陆
});
//常量管理
Route::group(['middleware' => 'check_login', 'prefix' => 'config'], function () {
    Route::match(['get', 'post'], 'list')->uses('ConfigController@lists')->name('config.list');//ajax请求list
    Route::match(['get', 'post'], 'edit')->uses('ConfigController@edit')->name('config.edit');//设置参数
    Route::post('del')->uses('ConfigController@del')->name('config.del')->where('id', '[0-9]+');;//设置参数
    Route::get('add')->uses('ConfigController@add')->name('config.add');//设置参数
    Route::get('flush')->uses('ConfigController@flush')->name('config.flush');//设置参数
});

//博客列表管理
Route::group(['middleware' => 'check_login', 'prefix' => 'blog'], function () {
    Route::match(['get', 'post'], 'list')->uses('BlogController@lists')->name('blog.list');//列表
    Route::get('edit/{id?}')->uses('BlogController@edit')->name('blog.edit')->where('id', '[0-9]+');//编辑
    Route::any('get/{id?}')->uses('BlogController@get')->name('blog.get')->where('id', '[0-9]+');//删除
    Route::post('del/{id?}')->uses('BlogController@del')->name('blog.del')->where('id', '[0-9]+');//删除
    Route::post('save')->uses('BlogController@save')->name('blog.save');//保存
    Route::post('uploadImage')->uses('BlogController@uploadImage')->name('blog.uploadImage');//上传图片
    Route::any('pasteImage')->uses('BlogController@pasteImage')->name('blog.pasteImage');//上传图片
    Route::post('changeStatus')->uses('BlogController@changeStatus')->name('blog.changeStatus');//更新状态
    Route::get('pushAll')->uses('BlogController@pushAll')->name('blog.pushAll');//百度推送
});
//标签列表管理
Route::group(['middleware' => 'check_login', 'prefix' => 'tags'], function () {
    Route::match(['get', 'post'], 'list')->uses('TagsController@lists')->name('tags.list');//列表
    Route::post('del/{id?}')->uses('TagsController@del')->name('tags.del')->where('id', '[0-9]+');//删除
    Route::match(['get', 'post'], 'edit')->uses('TagsController@edit')->name('tags.edit');//编辑
});