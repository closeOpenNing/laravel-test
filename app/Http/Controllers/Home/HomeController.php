<?php

namespace App\Http\Controllers\Home;
use Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Show the application index.
     *
     * @return Response
     */
    public function __construct()
    {
        
    }

    /**
     * cookie set
     * @param Request $request
     */
    public function index(Request $request)
    {
//        $val = $request->session()->get('key','default');
//        dd($val);
        $cookie = $request->cookie();
        dd($cookie);
//        return \Response::make('layouts.app')->withCookie($cookie);
    }

    /**
     * cache set
     */
    public function cache1(){
        Cache::put('key','val',10);//键 值 有效时间（分钟）
        //判断是否存在
        if(Cache::has('key')){
            dd(1);
        }else{
            Cache::forget('key');//删除缓存
        }
    }
    public function cache2(){
        $data = Cache::pull('key1');//取值后删除
        dd($data);
    }

    /**
     * example store cookie
     * @param Request $request
     */
    public function handle(Request $request)
    {
        $userName = $request->name;  //id 1, 张三  -> token, 1 => token， 时效，
        $pwd = $request->pwd;

        //TODO: 查询数据库，根据 name 查询

        //通过

        //userName, nick cookie  name loginName保存起来，时效
    }
    public function mid()
    {
        // 获取设置的 cookie name 是否存在，状态

        //
    }
}
