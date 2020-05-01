<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function show(Category $category)
    {
        // 读取分类 ID 关联的话题，并按每 20 条分页
        $topics = Topic::where('category_id', $category->id)->paginate(20);
        // 传参变量话题和分类到模板中
        return view('topics.index', compact('topics', 'category'));
    }

    public function show1(Request $request)
    {
        $res = $request->input();
        if(!empty($res)){
            info($res);
            return response()->json(['result'=>'success']);
        }else{
            var_dump('error');
        }

        return response()->json($request->input());
    }

    public function show2(Request $request, Topic $topic)
    {
        $topics = $topic->withOrder($request->order)
            ->with('user', 'category')
            ->paginate(20);

        return $topics;
    }

}
