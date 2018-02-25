<?php

namespace App\AdminApi\Controllers;

use Illuminate\Http\Request;
use League\HTMLToMarkdown\HtmlConverter;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Visit;
use Auth;

class ArticleController extends Controller
{
    /**
     * 返回所有的文章 [API]
     *
     * @return \Illuminate\Http\Response
     */
    public function index_api()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        for ($i = 0; $i < sizeof($articles); $i++) {
            $articles[$i]->key = $articles[$i]->id;
            $articles[$i]->content = str_limit(strip_tags($articles[$i]->content), 60);
            $articles[$i]->updated_at_diff = $articles[$i]->updated_at->diffForHumans();
        }
        return $articles;
    }

    /**
     * 跳转某篇文章
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        Article::update_view($id);
        $article = Article::findOrFail($id);
        Visit::record($request, '文章', $article->title);
        $article->created_at_date = $article->created_at->toDateString();
        $comments = $article->comments()->where('parent_id', 0)->orderBy('created_at', 'desc')->get();
        for ($i = 0; $i < sizeof($comments); $i++) {
            $comments[$i]->created_at_diff = $comments[$i]->created_at->diffForHumans();
            $comments[$i]->avatar_text = $comments[$i]->name[0];
            $replys = $comments[$i]->replys;
            for ($j = 0; $j < sizeof($replys); $j++) {
                $replys[$j]->created_at_diff = $replys[$j]->created_at->diffForHumans();
                $replys[$j]->avatar_text = $replys[$j]->name[0];
            }
        }
        $inputs = new CommentInputs;
        if (Auth::id()) {
            $inputs->name = Auth::user()->name;
            $inputs->email = Auth::user()->email;
            $inputs->website = Auth::user()->website;
        } else {
            $comment = Comment::where('ip', $request->ip())->orderBy('created_at', 'desc')->first();
            if ($comment) {
                $inputs->name = $comment->name;
                $inputs->email = $comment->email;
                $inputs->website = $comment->website;
            }
        }
        return view('articles.show', compact('article', 'comments', 'inputs'));
    }

    /**
     * 返回某个文章 [API]
     */
    public function show_api($id)
    {
        $article = Article::findOrFail($id);
        return $article;
    }

    /**
     * 创建或更新文章 [API]
     */
    public function store_api(Request $request)
    {
        if ($request->id) {
            $article = Article::findOrFail($request->id);
            $article->title = $request->title;
            $article->cover = $request->cover;
            $article->content = $request->input('content');
            $article->save();
            return response()->json([
                'message' => '更新成功!'
            ]);
        } else {
            $article = new Article;
            $article->title = $request->title;
            $article->cover = $request->cover;
            $article->content = $request->input('content');
            $article->save();
            return response()->json([
                'message' => '创建成功!'
            ]);
        }
    }

    /**
     * 发表（或隐藏）文章 [API]
     *
     * @return \Illuminate\Http\Response
     */
    public function publish_api($id)
    {
        $article = Article::findOrFail($id);
        if ($article->is_hidden) {
            $article->is_hidden = 0;
            $article->save();
            return response()->json([
                'message' => '文章已发表！'
            ]);
        } else {
            $article->is_hidden = 1;
            $article->save();
            return response()->json([
                'message' => '文章已切换为笔记！'
            ]);
        }
    }

    /**
     * 删除文章 [API]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy_api($id)
    {
        $article = Article::findOrFail($id);
        try {
            $article->delete();
            return response()->json([
                'message' => '删除成功!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '删除失败!'
            ]);
        }

    }

    /**
     * html 转 markdown [API]
     */
    public function markdown_api(Request $request)
    {
        $converter = new HtmlConverter();
        return $converter->convert($request->input('content'));
    }
}

