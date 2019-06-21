@extends('layout')
@section('title', '编辑区')
@section('style')
    <link rel="stylesheet" href="{{asset('static/editormd/css/editormd.min.css')}}"/>
    {{--    <link rel="stylesheet" href="{{asset('static/editormd/css/editormd.preview.min.css')}}"/>--}}
    <link rel="stylesheet" href="{{asset('static/editormd/css/editormd.i.css')}}?v=1"/>
    <style>
        .titleCls {
            min-height: 3.429rem !important;
            font-size: 1.3rem !important;
        }

        .badge {
            margin-top: 2px;
        }

        ul.form {
            margin-bottom: 0;
        }

        ul.form > li {
            margin-bottom: 2px;
        }

        ul.form.ratio100 > li > span {
            padding: 0;
        }
    </style>
    <link href="{{asset('static/jtagsinput/jquery.tagsinput.css')}}" rel="stylesheet">
@stop
@section('body')
    @include('widget.header')
    <div class="wrapper">
        <!-- main begin -->
        <div class="main">
            <div class="box65 fc box-s100 plr">
                <ul class="form ratio100">
                    <li>
                        <div><input type="text" class="titleCls active" placeholder="标题:一句话写明白" id="title"
                                    name="title" value=""></div>
                    </li>
                    <li>
                        <div><input type="text" id="tags" value="" placeholder="标签:" name="tags"></div>
                    </li>
                    <li>
                        <div><input type="text" value="" placeholder="首标签,只允许设置一个" name="first_tag"></div>
                    </li>
                    <li id="tags-list">
                        <span><i class="fa fa-arrow-circle-down"></i>
                            已创建的标签:<span class="tooltip bottom">可点击已有标签,或输入,如:php 可使用逗号,分号,回车和Tab键分隔</span></span>
                        <div>
                            @foreach($tags as $tag)
                                <a href="#" class="badge bg-999">#{{$tag}}</a>
                            @endforeach
                        </div>
                    </li>
                </ul>
            </div>
            <div id="content-editormd"></div>
            <div class="box25 offset60 offset-s10 box-s100 plr">
                <label><input value="1" name="type" id="type" type="radio" checked="checked">原创</label>
                <label><input value="2" name="type" id="type" type="radio">转载</label>
                <button class="btn bg-green" id="draft">草稿</button>
                <button class="btn bg-navy" id="publish">发布</button>
                <input type="hidden" id="article_id" value="{{$id or 0}}"/>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('static/jtagsinput/jquery.tagsinput.js')}}"></script>
    <script src="{{asset('static/editormd/editormd.js')}}?v={{time()}}"></script>
    <script>
        var editorObj;
        var article_id = 0;
        var cache_key = 'article_key';
        var cache_cnt_key = 'cache_cnt_key';
        var cacheCnt = 0;
        var winCache = window.localStorage;
        $(function () {
            $("#tags-list span").click(function () {
                var _this = $(this);
                if (_this.next('div').is(':hidden')) {
                    _this.next('div').show();
                    _this.find('i').addClass('fa-arrow-circle-down').removeClass('fa-arrow-circle-up');
                } else {
                    _this.next('div').hide();
                    _this.find('i').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-up');
                }
            });
            editorObj = editormd("content-editormd", {
                width: "65%",
                height: 540,
                min_height: 540,
                placeholder: "本编辑器支持Markdown编辑，左边编写，右边预览",
                delay: 300,//延时加载
                previewTheme: "dark",
                editorTheme: "pastel-on-dark",
                // syncScrolling: "single",
                taskList: true,
                tocm: true,          // Using [TOCM]
                htmlDecode: "style,script,iframe|on*",            // 开启 HTML 标签解析，为了安全性，默认不开启
                toolbarIcons: ["undo", "redo", "clear", "|", "h1", "h2", "h3", "h4", "bold", "hr", "italic", "quote", "|",
                    "list-ul", "list-ol", "|", "link", "reference-link", "image", "file", "|", "code", "html-entities", "preformatted-text", "code-block", "|",
                    "table", "toc", "tocm", "|", "theme_default", "theme_dark", "|", "fullscreen", "preview", "watch", "add_height"],
                toolbarIconsClass: {
                    bold: "fa-bold",
                    toc: 'fa-navicon',
                    tocm: 'fa-tasks',
                    theme_default: 'fa-square-o',
                    theme_dark: 'fa-square',
                    add_height: 'fa-bicycle'
                },
                toolbarIconTexts: {
                    bold: 'a'
                },
                toolbarHandlers: {//工具栏事件
                    toc: function (cm, icon, cursor, selection) {
                        console.log(icon);
                        var cursor = cm.getCursor();     //获取当前光标对象，同cursor参数
                        var selection = cm.getSelection();  //获取当前选中的文本，同selection参数
                        console.log(cursor);
                        console.log(selection);
                        this.insertValue("**目录导航**\n\n");
                        this.insertValue("[TOC]\n");
                    }
                    , tocm: function (cm, icon, cursor, selection) {
                        this.insertValue("[TOCM]\n");
                    }
                    , theme_default: function (cm, icon, cursor, selection) {
                        this.setTheme('default');
                        this.setEditorTheme('default');
                        this.setPreviewTheme('default')
                    }
                    , theme_dark: function (cm, icon, cursor, selection) {
                        this.setTheme('default');
                        this.setEditorTheme('pastel-on-dark');
                        this.setPreviewTheme('dark')
                    },
                    add_height: function (cm, icon, cursor, selection) {
                        var h = this.editor.height();
                        console.log(h);
                        this.height(h + 50);
                    }
                },
                lang: {
                    toolbar: {
                        toc: "章节导航",
                        tocm: "章节导航(下拉式)",  // 自定义按钮的提示文本，即title属性
                        theme_default: "设置默认主题",
                        theme_dark: "设置黑色主题",
                        add_height: "增加高试"
                    }
                },
                fileUpload: true,
                imageUpload: true,
                imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL: "{{route('blog.uploadImage')}}",
                fileUploadURL: "{{route('blog.uploadImage')}}",
                saveHTMLToTextarea: true,
                onresize: function () {

                },
                onload: function () {//加载完成后的操作
                    var _this = this;
                    var width = $(window).width();
                    if (width < 800) {
                        _this.width('98%');
                        _this.config({
                            toolbarIcons: ["undo", "redo", "|", "watch", "preview", "fullscreen", "|", "clear"]
                        });
//                        _this.toolbarModes({mini: ["undo", "redo", "|", "watch", "preview", "fullscreen", "|", "clear"]});
                        _this.hideToolbar();
                        _this.unwatch();
                    } else {
                        _this.showToolbar();
                        _this.watch();
                    }
                    var keyMap = {
                        "Ctrl-E": function (cm) {
                            var cursor = cm.getCursor();     //获取当前光标对象，同cursor参数
                            _this.insertValue("\n```\n\n```\n");
                            cursor.line += 2;
                            _this.setCursor(cursor);
                        }
                        , "Ctrl-A": function (cm) { // default Ctrl-A selectAll
                            cm.execCommand("selectAll");
                        }
                        , "Ctrl-S": function (cm) {
                            $("#draft").trigger('click');
                        }
                    };
                    this.addKeyMap(keyMap);
                    article_id = $("#article_id").val();
                    if (article_id > 0) {
                        $.post("{{route('blog.get')}}/" + article_id).done(function (rev) {
                            if (rev.status === 0) {
                                $("#title").val(rev.data.title);
                                $("#tags").importTags(rev.data.tags);
                                $("input[name='first_tag']").val(rev.data.first_tag);
                                $(":radio[name='type'][value='" + rev.data.type + "']").prop("checked", "checked");
                                editorObj.setMarkdown(rev.data.content);
                            }
                        }).fail(function () {
                            layer.close(index);
                            layer.msg("加载失败");
                        });
                    }
                    //缓存处理逻辑
                    var cacheCntVal = winCache.getItem(cache_cnt_key);
                    if (parseInt(cacheCntVal) > 0 && article_id <= 0) {
                        layer.confirm('您有缓存,需要恢复吗?', {btn: ['恢复', '取消']}, function (e) {
                            var cache = winCache.getItem(cache_key);
                            var obj = JSON.parse(cache);
                            $("#title").val(obj.title);
                            $("#tags").importTags(obj.tags);
                            editorObj.setMarkdown(obj.content);
                            layer.close(e);
                            setInterval(function () {
                                auto_cache()
                            }, 1000);
                        }, function () {
                            setInterval(function () {
                                auto_cache()
                            }, 1000);
                        });
                    } else {
                        setInterval(function () {
                            auto_cache()
                        }, 1000);
                    }
                },
                onchange: function () {
//                    if(win.isEditorChange) {
//                        win.isEditorChange = false;
//                    }else{
//                        $("#markdown-save").removeClass('disabled').addClass('change');
//                    }
                },
                path: "{{asset('static/editormd/lib')}}/"
            });
            /**
             * 粘贴上传图片
             */
            $("#content-editormd").on('paste', function (ev) {
                var data = ev.clipboardData;
                var items = (event.clipboardData || event.originalEvent.clipboardData).items;
                for (var index in items) {
                    var item = items[index];
                    if (item.kind === 'file') {
                        var blob = item.getAsFile();
                        var reader = new FileReader();
                        reader.onload = function (event) {
                            var base64 = event.target.result;
                            //ajax上传图片
                            $.post("{{route('blog.pasteImage')}}", {base: base64}, function (ret) {
                                if (ret.status === 0) {
                                    //新一行的图片显示
                                    editorObj.insertValue("\n![" + ret.data.title + "](" + ret.data.path + ")");
                                } else {
                                    layer.msg(ret.msg);
                                }
                            });
                        }; // data url!
                        reader.readAsDataURL(blob);
                    }
                }
            });
            //标签管理
            $("#tags").tagsInput({
                'width': 'auto',//宽度
                'height': 'auto',//高度
                'autocomplete': {selectFirst: true},
                'defaultText': '标签',//提示文字
                'removeText': '删除标签',//提示文字
                'placeholderColor': '#ccc',//提示文字背景色
                'maxCount': 10, //最大个数
                'maxChars': 20,
                onChange: function (elem, elem_tags) {
                    $('.tag', elem_tags).each(function () {
                        // $(this).css('background-color', '#999');
                        // $(this).css('color', '#000');
                    });
                },
                'onAddTag': function (elem, elem_tags) {
                    var tags = $("#tags").val().split(',');
                    if (tags.length >= this.maxCount) {
                        return false;
                    }
                }
            });
            //标签管理
            $("#tags-list a").click(function () {
                var _this = $(this);
                var tag = _this.text().replace('#', '');
                if (!$("#tags").tagExist(tag)) {
                    $("#tags").addTag(tag);
                } else {
                    $("#tags").removeTag(tag);
                }
            }).dblclick(function () {
                var _this = $(this);
                var tag = _this.text().replace('#', '');
                $("input[name='first_tag']").val(tag);
            });
            //标签提示
//            $("#tags-list a").hover(function () {
//                layer.tips("点击创建标签", this, {tips: [3, '#666']});
//            });
            //发布
            $("#publish").click(function () {
                blog(1);
            });
            $("#draft").click(function () {
                blog(2);
            });

        });

        function blog(status) {
            var data = {};
            var _title = $("#title");
            var _tags = $("#tags");
            if ($.trim(_title.val()) === '') {
                _title.focus();
                layer.tips('输入您的标题', '#title', {tips: [1, '#666']});
                return false;
            }
            if ($.trim(_tags.val()) === '') {
                _tags.focus();
                layer.tips('请输入标签,或点击已有的标签', '#tags_tagsinput', {tips: [1, '#666']});
                return false;
            }
            $("#loading").mask("open");
            data.id = $("#article_id").val();
            data.title = _title.val();
            data.tags = _tags.val();
            data.first_tag = $("input[name='first_tag']").val();
            data.status = status;
            data.type = $('input:radio[name="type"]:checked').val();
            data.content = editorObj.getMarkdown();
            $.post("{{route('blog.save')}}", data, function (rev) {
                $("#loading").mask("close");
                if (rev.status === 0) {
                    if (status === 1) {
                        window.location.href = rev.data;
                    } else {
                        $("#article_id").val(rev.data);
                        layer.msg(rev.msg, {icon: 6, time: 2000, shift: 1});
                    }
                } else {
                    layer.msg(rev.msg, {icon: 5, time: 2000, shift: 6});//一个哭脸
                }
            }, 'json');
        }

        var auto_cache = function () {
            var data = {};
            data.title = $("#title").val();
            data.tags = $("#tags").val();
            data.content = editorObj.getMarkdown();
            if ($.trim(data.title) !== '')
                cacheCnt++;
            if ($.trim(data.tags) !== '')
                cacheCnt++;
            if ($.trim(data.content) !== '')
                cacheCnt++;
            var json = JSON.stringify(data);
            winCache.setItem(cache_key, json);
            winCache.setItem(cache_cnt_key, cacheCnt);
        }
        $("#title").focus(function () {
            $(this).addClass('active');
        }).blur(function () {
            $(this).removeClass('active');
        });
    </script>
@endsection