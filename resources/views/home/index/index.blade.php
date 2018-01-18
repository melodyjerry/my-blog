
<!--
*文件名：前台
*时间：20170715
-->
<!--
文件名：首页模型
时间：20170815 更新
-->

﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="宋耀锋,个人博客,HXC,web前端,宋耀锋个人博客,web技术博文,javascript,html5,css3,layui,layui框架,前端工具导航,web框架大全,前端工具大全,前端目录,vue,node,jq"/>
    <meta name="description" content="HXC宋耀锋个人博客记录生活，关注web前端。HXC v1.0 主要基于Codeigniter + layui开发 版本：HXC v1.0 简要版，时间：2017年8月，博客托管于阿里云 服务器环境为：ECS centos 6.8 + Apache + Mysql "/>
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>HXC-宋耀锋个人博客</title>
    <link href="/home/css/animate.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="/home/css/bootstrap.min.css" rel="stylesheet">
    <link href="/home/css/style.css" rel="stylesheet">
    <link href="/home/css/banner.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<!--主体部分开始-->

<!--导航开始-->
@include('home.common.navigate')
<!--导航结束-->


﻿<!--首页banner开始-->
<div id="banner" >
    <div id="animate-layer">
        <s class="clouds cloud-01"></s>
        <s class="clouds cloud-02"></s>
        <s class="clouds cloud-03"></s>
        <!--
            <s class="clouds cloud-04"></s>
            <s class="clouds cloud-05"></s>
            <s class="clouds cloud-06"></s>
            <s class="clouds cloud-07"></s>
        -->
        <s class="balloon balloon-01"></s>

        <!--  <s class="balloon balloon-02"></s> -->
        <s class="bg"></s>
    </div>
</div>
<!--首页banner结束-->

<!--logo开始-->
<div class="logo">
    <div id="logo_img"><img src="/home/images/index_logo.jpg"></div>
    <div class="logo_title" >HXC博客欢迎你</div>
    <div class="logo_mo" >如痴如醉，乱七八糟都想整的小站</div>
    <div class="logo_btnbox" >
        <div class="btn btn_gradient" >
            <a style="color:#fff;" href="#">
                <span class="glyphicon glyphicon-certificate"></span>&nbsp;关于我
            </a>
        </div>
        <div class="btn btn_gradient2" >
            <a style="color:#fff;" href="#">
                <span class="glyphicon glyphicon-heart" ></span>&nbsp;左邻右舍
            </a>
        </div>
        <div class="btn btn_gradient3">
            <a style="color:#fff;" href="#">
                <span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>&nbsp;吐槽啦!
            </a>
        </div>

    </div>
</div>
<!--logo结束-->

<!--主体内容框开始-->
<div class="content" >
    <!--特殊导航条开始-->
    <div class="senav" >
        <div class="nav_ul">
            <a href="/">
                <li class="nav_ul_first">首页</li>
            </a>
            <!--其他栏目开始-->
            @foreach($category as $k => $v)
                <a href="{{ url('category', ['id' => $v->id]) }}"><li>{{ $v->name }}</li></a>
            @endforeach
        </div>
    </div>
    <!--特殊导航条结束-->


    <!--左侧边栏框开始-->
    @include('home.common.left')
    <!--左侧边栏框结束-->


    <!--右侧框开始-->
    <div class="right_box" >

        <!--文章列表开始-->
        @foreach($article as $k => $v)
        <a name="{{ $v->id }}" href="{{ url('article', ['id' => $v->id]) }}" id = "art_title">
            <div class="right_cell">
                <!--圆圈日期开始-->
                <div  class="round-date red ">
                    <span class="month">{{ $v->month }}月</span>
                    <span class="day">{{ $v->day }}</span>
                </div>
                <!--圆圈日期结束-->
                <div class="page_title"><h2>{{ $v->title }}</h2></div>
                <!--描述-->
                <div class="page_content">
                    <div class="page_content_left">
                        <img src="{{ $v->cover }}">
                    </div>
                    <div class="page_content_right">
                        文章摘要：{{ $v->description }}
                    </div>
                </div>

                <!--标签-->
                <div class="tag_box" >
                    <div style="display: inline-block;">
                        <span>
                            <span class="glyphicon glyphicon-user" style="color: #ff6e03;"></span>
                            &nbsp;作者：{{ $v->author }}</span>
                        <span style="margin-left:30px;">
                            <span class="glyphicon glyphicon-dashboard" style="color: #02b73b"></span>
                            &nbsp;发布时间：{{ $v->created_at }}
                        </span>
                    </div>
                    <div  class="left_box tag_block">
                        <span class="label label-primary tag_weiguan">
                            <span class="glyphicon glyphicon-eye-open" style="color: #fff"></span>
                            &nbsp;围观{{ $v->click }}</span>
                        <span class="label label-success tag_tag">
                            <span class="glyphicon glyphicon-folder-open" style="color: #fff"></span>
                            &nbsp;{{ $v->category_name }}</span>
                        <span class="label label-danger tag_moy">
                            <span class="glyphicon glyphicon-gift" style="color: #fff"></span>
                            &nbsp;赏一个</span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
        <!--文章列表结束-->
        <!--分页-->
        <div class="right_carnum">
            <nav aria-label="...">
                {{ $article->links() }}
            </nav>

        </div>
    </div>
    <!--右侧框结束-->
</div>
<!--主体内容框结束-->

﻿<!--脚部开始-->
@include('home.common.footer')
<!--脚部结束-->


<!--主体部分结束-->
</body>
</html>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>

<script type="text/javascript">
    //logo触发动画
    $(document).ready(function(){
        $('#logo_img').mouseover(function(){
            $('#logo_img').addClass('animated  rubberBand');
            //监听执行一次
            $('#logo_img').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){$('#logo_img').removeClass('animated  rubberBand');});
        });
    });

    //新浪微博触发动画
    $(document).ready(function(){
        $('#sinasite').mouseover(function(){
            $('#sinasite').addClass('animated  tada');
            //监听执行一次
            $('#sinasite').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){$('#sinasite').removeClass('animated  tada');});
        });
    });

    //博主邮箱触发动画
    $(document).ready(function(){
        $('#emailsite').mouseover(function(){
            $('#emailsite').addClass('animated  tada');
            //监听执行一次
            $('#emailsite').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){$('#emailsite').removeClass('animated  tada');});
        });
    });

    //新浪微博触发动画
    $(document).ready(function(){
        $('#appsite').mouseover(function(){
            $('#appsite').addClass('animated  tada');
            //监听执行一次
            $('#appsite').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){$('#appsite').removeClass('animated  tada');});
        });
    });

    //新浪微博触发动画
    $(document).ready(function(){
        $('#githubsite').mouseover(function(){
            $('#githubsite').addClass('animated  tada');
            //监听执行一次
            $('#githubsite').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){$('#githubsite').removeClass('animated  tada');});
        });
    });
</script>



<script type = "text/javascript">
    $(function(){
        $("#art_title").click(function(){
            var aid = $(this).attr('name');
            $.post("http://www.huxinchun.com/Home/viewnum",
                {
                    id:aid
                });
        });
    });
</script>
