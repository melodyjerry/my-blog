<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Comment\Store;
use App\Jobs\SendCommentEmail;
use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\FriendshipLink;
use App\Models\Notice;
use App\Models\OauthUser;
use App\Models\Tag;
use Cache, Captcha;
use Carbon\Carbon;
use function dispatch;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Input;
use Mail;
use function randomCode;
use function response;

class IndexController extends BaseController
{
    /**
     * 首页
     *
     * @param Article $articleModel
     * @return mixed
     */
    public function index(Request $request, Article $articleModel)
    {
        // 获取文章列表数据
        $article = $articleModel->getHomeList();
        $assign = [
            'article' => $article,
            'pageString' => $article->links(),
            'tagName' => '',
            'title' => '首页'
        ];
        return view('home.index.index', $assign);
    }

    public function newArticle(Article $article)
    {
        $data = $article->newArticle();
        return response()->json(['code' => 0, 'data' => $data]);
    }

    /**
     * 文章详情
     *
     * @param         $id
     * @param Article $articleModel
     * @param Comment $commentModel
     *
     * @return $this
     */
    public function article(int $id, Request $request, Article $articleModel, Comment $commentModel)
    {
        // 获取文章数据
        $data = $articleModel->getDataById($id);
        // 去掉描述中的换行
        $data->description = str_replace(["\r", "\n", "\r\n"], '', $data->description);
        // 同一个用户访问同一篇文章每天只增加1个访问量  使用 ip+id 作为 key 判别
        $ipAndId = 'articleRequestList' . $request->ip() . ':' . $id;
        if (!Cache::has($ipAndId)) {
            cache([$ipAndId => ''], 1440);
            // 文章点击量+1
            $data->increment('click');
        }

        // 获取上一篇
        $prev = $articleModel
            ->select('id', 'title')
            ->orderBy('created_at', 'asc')
            ->where('id', '>', $id)
            ->limit(1)
            ->first();

        // 获取下一篇
        $next = $articleModel
            ->select('id', 'title')
            ->orderBy('created_at', 'desc')
            ->where('id', '<', $id)
            ->limit(1)
            ->first();

        // 获取评论
        $comment = $commentModel->getDataByArticleId($id);
//        dd($comment);
        $category_id = $data->category_id;
        $title = '文章详情';
        $assign = compact('category_id', 'data', 'prev', 'next', 'comment', 'title');
        return view('home.index.article', $assign);
    }

    /**
     * 获取栏目下的文章
     *
     * @param Article $articleModel
     * @param $id
     * @return mixed
     */
    public function category(Article $articleModel, $id)
    {
        $map = [
            'articles.category_id' => $id
        ];
        $article = $articleModel->getHomeList($map);
        $total = count($article);
        $pageString = $article->links();
        $categoryName = Category::where('id', $id)->value('category_name');
        $dispatch = ['title' => $categoryName];

        switch ($categoryName) {
            case '关于':
                return view('home.index.about', $dispatch);
                break;
            case '留言':
                return view('home.index.message', $dispatch);
                break;
            case '下载':
                return view('home.index.downlist', $dispatch);
                break;
            case '导航':
                return view('home.index.navigate', $dispatch);
                break;
        }

        $assign = [
            'total' => $total,
            'pageString' => $pageString,
            'category_id' => $id,
            'article' => $article,
            'tagName' => '',
            'title' => $categoryName
        ];
        return view('home.index.category', $assign);
    }

    /**
     * 获取标签下的文章
     *
     * @param $id
     * @param Article $articleModel
     * @return mixed
     */
    public function tag($id, Article $articleModel)
    {
        // 获取标签名
        $tagName = Tag::where('id', $id)->value('name');
        // 获取此标签下的文章id
        $articleIds = ArticleTag::where('tag_id', $id)
            ->pluck('article_id')
            ->toArray();
        // 获取文章数据
        $map = [
            'articles.id' => ['in', $articleIds]
        ];
        $article = $articleModel->getHomeList($map);
        $assign = [
            'pageString' => $article->links(),
            'category_id' => 'index',
            'article' => $article,
            'tagName' => $tagName,
            'title' => $tagName
        ];
        return view('home.index.index', $assign);

    }

    /**
     * 随言碎语
     *
     * @return mixed
     */
    public function chat()
    {
        $chat = Chat::orderBy('created_at', 'desc')->get();
        $assign = [
            'category_id' => 'chat',
            'chat' => $chat
        ];
        return view('home.index.chat', $assign);
    }

    /**
     * 开源项目
     *
     * @return mixed
     */
    public function git()
    {
        $assign = [
            'category_id' => 'git'
        ];
        return view('home.index.git', $assign);
    }

    /**
     * 文章评论
     *
     * @param Comment $commentModel
     */
    public function comment(Store $request, Comment $commentModel, OauthUser $oauthUserModel)
    {
        $data = $request->all();
        if (ctype_alnum($data['content']) || in_array($data['content'], ['test', '测试'])) {
            return response()->json(['code' => 1, 'message' => '禁止无意义评论']);
//            return ajax_return(200, '禁止无意义评论');
        }
        // 获取用户id
        $userId = session('user.id');
        // 判断是否是自己评论自己
        $pid = $data['pid'];
        if ($pid !== 0) {
            $oauthUserId = app('db')->table('comments')->where('id', $pid)->value('oauth_user_id');
            if ($oauthUserId == $userId) {
                return response()->json(['code' => 1, 'message' => '自己不能评论自己']);
            }
        }

        // 是否是管理员
        $isAdmin = session('user.is_admin');
        // 获取当前时间戳
        $time = time();
        // 获取最近一次评论时间
        $lastCommentDate = $commentModel->where('oauth_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->value('created_at');
        $lastCommentTime = strtotime($lastCommentDate);
        // 限制1分钟内只许评论1次
        if ($isAdmin != 1 && $time - $lastCommentTime < 60) {
            return response()->json(['code' => 1, 'message' => '评论太过频繁,请稍后再试']);
//            return ajax_return(200, '评论太过频繁,请稍后再试');
        }
        // 限制用户每天最多评论10条
        $date = date('Y-m-d', $time);
        $count = $commentModel
            ->where('oauth_user_id', session('user.id'))
            ->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
            ->count();
        if ($isAdmin != 1 && $count > 10) {
            return response()->json(['code' => 1, 'message' => '每天做多评论10条']);
//            return ajax_return(200, '每天做多评论10条');
        }
        // 如果用户输入邮箱；则将邮箱记录入oauth_user表中
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if (preg_match($pattern, $data['email'])) {
            // 修改邮箱
            $oauthUserMap = [
                'id' => $userId
            ];
            $oauthUserData = [
                'email' => $data['email']
            ];
            $oauthUserModel->updateData($oauthUserMap, $oauthUserData);
            session(['user.email' => $data['email']]);
            unset($data['email']);
        }
        // 存储评论

        $id = $commentModel->storeData($data);
        // 更新缓存
        Cache::forget('common:newComment');
        $_data['id'] = $id;
        return ajax_return(200, ['id' => $id]);
    }

    /**
     * 检测是否登录
     */
    public function checkLogin()
    {
        $data['status'] = 1;
        $data['email'] = 0;
        if (empty(session('user.id'))) {
            $data['status'] = 0;
        } else {
            if (!empty(session('user.email'))) {
                $data['email'] = 1;
            }
        }

        return response()->json($data);

//        if (empty(session('user.id'))) {
//            return 0;
//        } else {
//            return 1;
//        }
    }

    /**
     * 搜索文章
     *
     * @param Article $articleModel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Article $articleModel)
    {
        $wd = request()->input('wd');
        $map = [
            'title' => ['like', '%' . $wd . '%']
        ];
        $article = $articleModel->getHomeList($map);
        Cache::put('article', $article, Carbon::now()->addSeconds(1));
        $assign = [
            'pageString' => $article,
            'category_id' => 'index',
            'article' => $article,
            'tagName' => '',
            'title' => $wd
        ];
        return view('home.index.index', $assign);
    }

    /**
     * 用于做测试的方法
     */
    public function test()
    {
        echo 3333;
    }


    /**
     * 获取指定网站公告
     * @param Request $request
     * @param Notice $notice
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNoticeById(Request $request, Notice $notice)
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return response()->json(['code' => 1, 'msg' => '参数错误']);
        }

        $noticeInfo = $notice->getNoticeById($id);
        if ($noticeInfo) {
            return response()->json(['code' => 0, 'msg' => '操作成功', 'data' => $noticeInfo]);
        }

        return response()->json(['code' => 1, 'msg' => '操作失败']);
    }


    public function zan(int $id, Article $article)
    {
        if (empty($id)) {
            return $this->error('参数错误');
        }

        $article = $article->getDataById($id);
        $likeIpAndId = 'like:' . request()->ip() . '--' . $id;
        if (!Cache::has($likeIpAndId)) {
            cache([$likeIpAndId => ''], 120);
            // 文章点击量+1
            $article->increment('like');
            return $this->success(['like' => $article->like]);
        }
    }


    public function message(Banner $banner, Message $message)
    {
        $pictures = $banner->getMsgPicture();
        $isLogin = session('user') ? 1 : 0;
        $messageWelcome = config('blog.messageWelcome');
        $assign = [
            'title' => '留言板',
            'pictures' => $pictures,
            'prefix_route' => config('blog.picture_upload_path'),
            'isLogin' => $isLogin,
            'messageWelcome' => $messageWelcome
        ];
        return view('home.index.message', $assign);
    }

    public function messageList(Message $message)
    {
        $data = $messages = $message->messageList();
        return response()->json(['code' => 0, 'msg' => 'ok', 'data' => $data]);
    }

    public function messageInsert(Request $request, Message $message)
    {
        if (!Captcha::check($request->input('verify'))) {
            return response()->json(['code' => 1, 'msg' => '请输入正确的验证码']);
        }

        $data = $request->except(['verify', 'isLogin']);


        if ($message->messageInsert($data)) {
            return response()->json(['code' => 0, 'msg' => '留言成功']);
        }

        return response()->json(['code' => 1, 'msg' => '留言成功']);

    }

    public function captcha()
    {
        /*创建验证码*/
        return Captcha::create('mini');
    }


    public function friendLink()
    {
        $friendLinks = FriendshipLink::friendLink();
        return view('home.index.friendLink', ['title' => '左邻右舍', 'friendLinks' => $friendLinks]);
    }


    public function about(Article $article)
    {
        $data = $article->aboutMe();
        return view('home.index.article', ['data' => $data, 'title' => '关于我']);
    }


    public function vip()
    {
        return view('home.index.vip-center', ['title' => '会员中心']);
    }

    public function vipIndex()
    {
        return view('home.index.vip-index');
    }

    public function vipMember()
    {
        return view('home.index.vip-member');
    }


    public function randCode(Request $request)
    {
        $email = $request->input('email', '');
        $name = session('user.name');
        $subject = '宋耀锋博客用户认证';
        $data = [
            'code' => randomCode(),
        ];
        if (empty($email)) {
            return response()->json(['code' => 1, 'msg' => '亲，您的邮箱还没填呢']);
        }
        if (Cache::has('codeExpired')) {
            return response()->json(['code' => 1, 'msg' => '亲，验证码一分钟只能获取一次！！']);
        }
        $existEmail = app('db')->table('oauth_users')->where('email', $email)->first();
        if (!empty($existEmail)) {
            return response()->json(['code' => 1, 'msg' => '亲，该邮箱已经被注册啦！']);
        }

        dispatch(new SendCommentEmail($email, $name, $subject, $data, 'mail'));
        $expiresAt = Carbon::now()->addMinutes(1);
        Cache::put('codeExpired', $data['code'], $expiresAt);

        return response()->json(['code' => 0, 'msg' => '邮件发送成功！']);

    }

    public function vipComment()
    {
        return view('home.index.vip-comment');
    }

    public function vipMessage()
    {
        return view('home.index.vip-message');
    }

    public function vipRecharge()
    {
        return view('home.index.vip-recharge');
    }

    public function vipConsume()
    {
        return view('home.index.vip-consume');
    }

}
