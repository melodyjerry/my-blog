﻿<!--导航开始-->
@include('home.index.common.top')
<!--导航结束-->

<!--主题框架开始-->
<div class="container">
    <!--左侧开始-->
    <section>
        <article >
            <div class="mbnav"><i class="el el-home"></i><a href="/" title="">首页</a>&gt;<a href="/category/{{ $data->category_id }}" >{{ $data->category_name }}</a>&gt;正文</div>
            <h3 class="arc-title index-title">{{ $data->title }}</h3>
            <div class="post-line bg-color">
                <ul>
                    <li><a title="{{ $data->author }}发表于{{ $data->created_at }}"><i class="el-time"></i><time>{{ $data->created_at }}</time></a></li>
                    <li><a href="#" title="本文作者：{{ $data->author }}"><i class="el-user"></i>{{ $data->author }}</a></li>
                    <li><a href="#Comment" title="转到评论"><i class="el-comment"></i>0条</a></li>
                    <li><a title="已有 {{ $data->click }} 次浏览"><i class="el-eye-open"></i>({{ $data->click }})</a></li>
                </ul>
            </div>

            <div class="article-content bg-color">
                <!-- <div class="article-content bg-color" id="Hotbg"> -->
                <!--文章正文-->
                <div class="article-body">
                    {!! $data->html !!}
                </div>
                <!--END 文章正文->
                <!--分享-->
                <div class="article-fx"><span class="img-circle" href="javascript:void(0)" onClick="dashangToggle()" class="img-circle">打赏</span>&nbsp;&nbsp;&nbsp;<a class="fx-btn img-circle" href="javascript:;" class="img-circle">分享</a>
                    <div class="bd-fx arc-bdfx">
                        <i class="el-remove fx-close"></i>
                        <ul class="bdsharebuttonbox">
                            <li><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></li>
                            <li><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a></li>
                            <li><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a></li>
                            <li><a href="#" class="bds_tieba" data-cmd="tieba" title="分享到百度贴吧"></a></li>
                        </ul>
                        <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>

                    </div>
                </div>
                <!--END 分享-->
                <hr>
                <!--标签-->
                <div class="article_tag">
                    <ul >
                        <!--		<li><a title="封寒紫 当前位于：浙江省金华市">浙江省金华市</a></li>-->
                        <!-- <li><a href="./Index/category.html" title="归类："></a></li> -->
                        <li><a href="/category/{{ $data->category_id }}" title="归类：{{ $data->category_name }}">{{ $data->category_name }}</a></li>
                        @foreach($data->tag as $k => $v)
                            <li>
                                <a class="b-tag-name" title="标签：{{ $v->name }}" href="/tag/{{ $v->id }}">{{ $v->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </article>
        <!--上一篇，下一篇-->
        <div class="shangyip bg-color">
            @if(!is_null($prev))
                <span><i class="el-arrow-up"></i>上一篇：<a class='blue-text' href="/article/{{ $prev->id }}" title='上一篇：{{ $prev->title }}'>{{ $prev->title }}</a></span>
            @else
                <span><i class="el-arrow-up"></i>上一篇：<a class='blue-text' href="javascript: void(0);" >没有了</a></span>
            @endif

            @if(!is_null($next))
                <span><i class="el-arrow-down"></i>下一篇：<a class='blue-text' href="/article/{{ $next->id }}" title='上一篇：{{ $next->title }}'>{{ $next->title }}</a></span>
            @else
                <span><i class="el-arrow-down"></i>下一篇：<a class='blue-text' href="javascript: void(0);" >没有了</a></span>
            @endif
        </div>
        <!--淘宝橱窗广告-->
        {{--<div>--}}
            {{--<script type="text/javascript">--}}
                {{--document.write('<a style="display:none!important" id="tanx-a-mm_128084981_40056229_151324323"></a>');--}}
                {{--tanx_s = document.createElement("script");--}}
                {{--tanx_s.type = "text/javascript";--}}
                {{--tanx_s.charset = "gbk";--}}
                {{--tanx_s.id = "tanx-s-mm_128084981_40056229_151324323";--}}
                {{--tanx_s.async = true;--}}
                {{--tanx_s.src = "http://p.tanx.com/ex?i=mm_128084981_40056229_151324323";--}}
                {{--tanx_h = document.getElementsByTagName("head")[0];--}}
                {{--if(tanx_h)tanx_h.insertBefore(tanx_s,tanx_h.firstChild);--}}
            {{--</script>--}}
            {{--<div id="authorad" style="width:20px;height:90px;background-color:#ff4400;float:right;font-size:10px;color:#fff;">--}}
                {{--<ul style="text-align:center;height:90px;">--}}
                    {{--<li style="height:22.5px;">博</li>--}}
                    {{--<li style="height:22.5px;">主</li>--}}
                    {{--<li style="height:22.5px;">推</li>--}}
                    {{--<li style="height:22.5px;">荐</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
        {{--</div>--}}
        <!--淘宝橱窗广告end-->
        <!--随机推荐-->
        <div class="maybe-love">
            <h4 class="index-title"><i class="el-heart"></i>您还可能喜欢</h4>
            <ul>
                <li>
                    <a href="/article/71.html">
                        <img src="/home/images/2017032958db625a684a9.png"  alt="jquery ajax异步加载分类文章实例" title="jquery ajax异步加载分类文章实例" />
                        <span >jquery ajax异步加载分类文章实例</span>
                    </a>
                </li><li>
                    <a href="/article/34.html">
                        <img src="/home/images/201612295864dab12c7bd.jpg"  alt="ecos框架入门之留言板开发" title="ecos框架入门之留言板开发" />
                        <span >ecos框架入门之留言板开发</span>
                    </a>
                </li><li>
                    <a href="/article/19.html">
                        <img src="/home/images/585949886ec58.jpg"  alt="10个吸引百度蜘蛛爬行网站的技巧" title="10个吸引百度蜘蛛爬行网站的技巧" />
                        <span >10个吸引百度蜘蛛爬行网站的技巧</span>
                    </a>
                </li><li>
                    <a href="/article/38.html">
                        <img src="/home/images/20170118587f0307db632.jpg"  alt="一个php程序员的学习过程" title="一个php程序员的学习过程" />
                        <span >一个php程序员的学习过程</span>
                    </a>
                </li>
            </ul>
        </div>
        <!--评论列表-->

        <!--END评论列表-->
        <!--评论表单-->
        <h3 class="form-btn blue-text" ><a href="javascript:;" ><i class="el-edit"></i>我要留言 / 展开表单</a></h3>
        <!-- 通用评论开始 -->
        <script>
            var userEmail='';
        </script>
        <style type="text/css">

            .b-head-img{
                width: 45px;
                height: 45px;
                position: absolute;
                left: 15px;top: 5px;
            }
            .b-box-textarea{
                margin: 5px 0;
                height: 120px;
                border-radius: 4px;
                position: relative;z-index: 1;
                border:#ddd 1px solid;
                box-sizing:border-box;
                box-shadow:inset 0 0 2px rgba(0,0,0,0.05);
                font-family:'Microsoft YaHei';
                /*text-indent:10px;*/
                background:rgba(255,255,255,0.5);transition:all 0.5s;
                -webkit-transition:all 0.5s;
            }
            .b-box-content{
                padding: 5px;
                height: 75px;
                border: none;
                border-bottom: 1px solid #E6EAED;
                color: #999;overflow-y: auto;
                font-size: 12px;
            }
            .b-box-content:focus{
                outline:none;border:#ff6700 1px solid;box-shadow: 0 0 8px rgba(255, 103, 0,0.7);
            }
            .b-submit-button{
                position: absolute;
                right: 0.5px;
                bottom: 1px;
            }
            .b-submit-button input{
                height:30px;
                width:60px;
            }
        </style>
        <div class="b-box-textarea" >
            <div class="b-box-content" contenteditable="true" onfocus="delete_hint(this)">请先登陆后发表评论</div>
            <ul class="b-emote-submit">
                <li class="b-emote">
                    <i class="el el-reddit" onclick="getTuzki(this)"></i>
                    <input style="height:30px;width:20%;font-size:12px;margin-top:1px;" class="form-control b-email" type="text" name="email" @if(empty(session('user.email'))) placeholder="邮箱未认证" value="" @else placeholder="" value="{{ session('user.email') }}"  @endif disabled>
                    <div class="b-tuzki">

                    </div>
                </li>
                <li class="b-submit-button">
                    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" id="csrf_token" />
                    <input type="button" style="cursor: pointer;" value="评 论" aid="{{ request()->id}}" pid="0" onclick="comment(this)">
                </li>
                <li class="b-clear-float"></li>
            </ul>
        </div>

        <!-- 新的评论 -->
        <div class="comment-area" id="Comment">
            <h4 class="index-title"><i class="el el-comment-alt"></i> 当前共有<span>0</span> 条评论
                <a href="Comment/?48-1.html"><i class="el el-th-list"></i>浏览所有评论</a>
            </h4>
            <ul class="b-user-comment">
                <li class="bg-color">
                    <div class="comment-ava">
                        <a href="#" id="Comment-137" target="_blank" rel="nofollow" title="蓝笑灵晨">
                            <img class="img-circle" src="http://qzapp.qlogo.cn/qzapp/101370818/18298955B2ED231527EC85FE74F8DBCC/100" alt="蓝笑灵晨"/></a>
                        <!--<span><i class="el-user"></i>木杉</span>-->
                    </div>
                    <div class="comment-info ">
                        <div class="comment-line ">
                            <ul>
                                <li><a ><i class="el-user"></i>蓝笑灵晨</a></li>
                                <li><a title="发表于2016-7-8"><i class="el-time"></i>2018-02-07 16:42:32</a></li>
                                <li><a title="蓝笑灵晨 当前位于：中国浙江杭州"><i class="el-map-marker"></i>中国浙江杭州</a></li>
                            </ul>
                        </div>
                        <div class="comment-content">来来来&nbsp;<a href="javascript:;" aid="120" pid="316" username="蓝笑灵晨" onclick="reply(this)"><button style="background:#da1a8d;color:#f3efef;">回复</button></a></div>
                        <!--回复-->
                        <ul class="re-comment">
                        </ul>
                    </div>
                </li>
                <li class="bg-color">
                    <div class="comment-ava">
                        <a href="#" id="Comment-137" target="_blank" rel="nofollow" title="syf_lingchen">
                            <img class="img-circle" src="http://tvax3.sinaimg.cn/default/images/default_avatar_male_180.gif" alt="syf_lingchen"/></a>
                        <!--<span><i class="el-user"></i>木杉</span>-->
                    </div>
                    <div class="comment-info ">
                        <div class="comment-line ">
                            <ul>
                                <li><a ><i class="el-user"></i>syf_lingchen</a></li>
                                <li><a title="发表于2016-7-8"><i class="el-time"></i>2018-02-07 16:26:53</a></li>
                                <li><a title="syf_lingchen 当前位于：中国浙江杭州"><i class="el-map-marker"></i>中国浙江杭州</a></li>
                            </ul>
                        </div>
                        <div class="comment-content"><img src="http://www.100txy.com/Public/emote/tuzki/t_0006.gif" title="顶" alt="雷小天博客">laikanan!&nbsp;<a href="javascript:;" aid="120" pid="314" username="syf_lingchen" onclick="reply(this)"><button style="background:#da1a8d;color:#f3efef;">回复</button></a></div>
                        <!--回复-->
                        <ul class="re-comment">
                            <li style="border-left:none;">
                                <div class="admin-ava"><img src="http://qzapp.qlogo.cn/qzapp/101370818/18298955B2ED231527EC85FE74F8DBCC/100" alt="蓝笑灵晨" title="蓝笑灵晨" class="img-circle"></div>
                                <div class="re-info">
                                <span>
                                    <img src="/Template/xiao/Home/Public/images/icon/ok.png"><a href="#" target="_blank">蓝笑灵晨</a><time>2018-02-07 16:40:15</time> 回复 <a href="#Comment-1">syf_lingchen</a>
                                </span>
                                <div class=" re-content">不错吆&nbsp;<a href="javascript:;" aid="120" pid="315" username="syf_lingchen" childname="蓝笑灵晨" onclick="reply(this)"><button style="background:#da1a8d;color:#f3efef;">回复</button></a></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

        </div>
        <!-- 通用评论结束 -->

    </section>
    <!--左侧结束-->
    <!--=========右侧开始==========-->
    @include('home.index.common.right')
    <!--=========END右侧==========-->
</div>
<!--主题框架结束-->
<!---底部开始-->
@include('home.index.common.footer')
<!---底部结束-->