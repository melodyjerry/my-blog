<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ckplayer网页播放视频代码 | html5视频特效| 网页特效库</title>
    <meta name="keywords" content="ckplayer视频播放器, html5视频特效, css3动画, 网页特效" />
</head>

<body>
<center><div id="video"><div id="a1"></div></div></center>

<script type="text/javascript" src="/home/js/ckplayer.js" charset="utf-8"></script>
<script type="text/javascript">
    var flashvars={
        f:'http://movie.ks.js.cn/flv/other/1_0.flv',
        c:0,
        b:1,
        p:1
    };
    var params={AllowAutoPlay:true,bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always'};
    CKobject.embedSWF('ckplayer/ckplayer.swf','a1','ckplayer_a1','600','400',flashvars,params);
    /*
    CKobject.embedSWF(播放器路径,容器id,播放器id/name,播放器宽,播放器高,flashvars的值,其它定义也可省略);
    下面三行是调用html5播放器用到的
    */
    var video=['http://movie.ks.js.cn/flv/other/1_0.mp4->video/mp4'];
    var support=['iPad','iPhone','ios','android+false','msie10+false','webKit'];
    CKobject.embedHTML5('video','ckplayer_a1',600,400,video,flashvars,support);
</script>
</body>
</html>

